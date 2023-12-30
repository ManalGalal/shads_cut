<?php

namespace App\Traits;

use App\Models\NotificationMessage;
use App\Models\Order;
use App\Models\OrderWorker;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait SessionTraits {
    use NotificationTraits;
    public function validateSessionOrder($request) {
        $worker = $request->user();
        $order = $request->input("order"); // order id 
        if (!$order) {
            throw new HttpException(400, __("errors.order_required"));
        }
        $worker_has_order = OrderWorker::where("order_id", $order)
            ->where("worker_id", $worker->id)
            ->exists();
        if (!$worker_has_order) {
            throw new HttpException(400, __("errors.invalid_session_order"));
        }
    }
    public function updateOrderOnSessionStart($order_id) {
        $order = Order::where("id", $order_id)
            ->first();

        if (!$order->started_at) {
            $order->started_at = Carbon::now();
        }
        $order->status = "in_progress";
        $order->save();
    }
    public function updateOrderOnSessionEnd($order_id) {
        Order::where("id", $order_id)
            ->update(["status" => "completed", "ended_at" => Carbon::now()]);
    }

    public function updateOrderWorkerOnSessionEnd($order_id, $worker_id, $start_time) {
        $order_has_started_before =  OrderWorker::where("worker_id", $worker_id)
            ->where("order_id", $order_id)
            ->whereNotNull("start_time")
            ->exists();
        $this->markOrderWorkerAsCompleted($order_id, $worker_id);
        if ($order_has_started_before) {
            OrderWorker::where("worker_id", $worker_id)
                ->where("order_id", $order_id)
                ->update(["end_time" => Carbon::now()]);
            return response(["message" => __("messages.session_ended")]);
        }
        // first time that order starts ;
        OrderWorker::where("worker_id", $worker_id)
            ->where("order_id", $order_id)
            ->update(["start_time" => $start_time, "end_time" => Carbon::now()]);
    }
    public function sendNotificationOnOrderStart($order_id) {
        // should be sent only once. 
        if (Order::statusOccurrence($order_id, "in_progress") > 1) {
            return;
        }
        $order = Order::where("id", $order_id)
            ->first();
        $notification_message = NotificationMessage::create([
            "title_en" =>   "Session  Started",
            "title_ar" =>  "تم بدء الطلب",
            "body_en" =>  "Order session has started",
            "body_ar" => "تم بدء العمل في الاوردر"
        ]);
        $this->sendNotification(
            $notification_message,
            "user",
            [$order->user_id]
        );
        $worker_ids = $order->workers->map(function ($worker) {
            return $worker->id;
        })->toArray();
        $notification_message = NotificationMessage::create([
            "title_en" =>   "Session  Started",
            "title_ar" =>  "تم بدء الطلب",
            "body_en" =>  "Order session has started",
            "body_ar" => "تم بدء العمل في الاوردر"
        ]);
        $this->sendNotification(
            $notification_message,
            "worker",
            $worker_ids
        );
    }
    public function markOrderWorkerAsCompleted($order_id, $worker_id) {
        $order_workers = OrderWorker::where("completed", false)
            ->where("worker_id", $worker_id)
            ->where("order_id", $order_id)
            ->get();
        foreach ($order_workers as $order_worker) {
            $order_worker->update(["completed" => true]);
        }
    }
    /**
     * Should only work when all orderWorkers are compeleted
     */
    public function sendNotificationOnOrderEnd($order_id) {
        $count_all_order_workers = OrderWorker::where("order_id", $order_id)
            ->count();
        $count_completed_order_workers = OrderWorker::where("order_id", $order_id)
            ->where("completed", true)
            ->count();
        if ($count_all_order_workers !== $count_completed_order_workers) {
            return;
        }
        $order = Order::where("id", $order_id)
            ->first();
        $notification_message = NotificationMessage::create([
            "title_en" =>   "Session  Ended",
            "title_ar" =>    "تم الانتهاء من الطلب",
            "body_en" =>  "Order session has ended",
            "body_ar" => "تم الانتهاء من العمل في الاوردر"
        ]);
        $this->sendNotification(
            $notification_message,
            "user",
            [$order->user_id]
        );
        $worker_ids = $order->workers->map(function ($worker) {
            return $worker->id;
        })->toArray();
        $this->sendNotification(
            $notification_message,
            "worker",
            $worker_ids
        );
    }
}
