<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderWorker;
use App\Models\Worker;
use App\Traits\HttpErrors;
use App\Traits\NotificationTraits;
use App\Traits\SessionTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class SessionController extends Controller {
    use HttpErrors, SessionTraits, NotificationTraits;
    public function start(Request $request) {

        $order = $request->input("order");
        // add order validation;
        $this->validateSessionOrder($request);

        $key = Carbon::now() . "/$order"; // => 2021-11-01 12:03:00/1 
        $added = Redis::command("HSET", ["session", $request->user()->id, $key]);
        if (!$added) {
            return $this->FORBIDDEN(__("errors.in_session"));
        }
        $this->updateOrderOnSessionStart($order);
        $this->sendNotificationOnOrderStart($order);
        return response(["message" => __("messages.session_started")]);
    }
    public function end(Request $request) {
        $session = Redis::command("HGET", ["session", $request->user()->id]);
        if (!$session) {
            return $this->BAD_REQUEST(__("errors.not_in_session"));
        }
        Redis::command("HDEL", ["session", $request->user()->id]);

        [$start_time, $order_id]  = explode("/", $session); // [ 2021-11-01 12:03:00, 1]
        // update order worker 
        // if order has start_time keep the old start_time 
        $this->updateOrderOnSessionEnd($order_id);
        $this->updateOrderWorkerOnSessionEnd($order_id, $request->user()->id, $start_time);
        $this->sendNotificationOnOrderEnd($order_id);
        return response(["message" => __("messages.session_ended")]);
    }
    public function myStatus(Request $request) {
        $status = Redis::command("HGET", ["session", $request->user()->id]);
        if (!$status) {
            return response(["in_session" => false,  "started_at" => null, "order" => null]);
        }
        [$start_time, $order_id]  = explode("/", $status);
        $order = Order::where("id", $order_id)
            ->with(["services", "user:id,name,phone,profile_picture"])
            ->first();
        return response(["in_session" => true,   "started_at" => $start_time, "order" => $order]);
    }
    public function status(Request $request) {
        $session_data = Redis::command("HGETALL", ["session"]);
        $status_data = [];
        $admin = $request->user();
        foreach ($session_data as $key => $value) {
            $worker = Worker::where("id", $key)
                ->select(["id", "name", "phone", "branch_id", "profile_picture"])
                ->first();
            if ($admin->role === "normal" && $admin->branch_id !== $worker->branch_id) {
                continue;
            }
            [$start_time, $order_id]  = explode("/", $value);
            $order = Order::where("id", $order_id)
                ->with(["services", "user:id,name,phone,profile_picture"])
                ->first();
            $status_data = ["worker" => $worker, "started_at" => $start_time, "order" => $order];
        }
        return response(["session_data" => $status_data]);
    }
}
