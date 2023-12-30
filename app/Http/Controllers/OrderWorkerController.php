<?php

namespace App\Http\Controllers;

use App\Models\NotificationMessage;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\OrderWorker;
use App\Models\Worker;
use App\Models\WorkerService;
use App\Traits\HttpErrors;
use App\Traits\NotificationTraits;
use Illuminate\Http\Request;

class OrderWorkerController extends Controller {
    use HttpErrors, NotificationTraits;
    public function assignWorker(Request $request, Worker $worker, Order $order) {
        $services = $request->input("services");
        if (!is_array($services) || count($services) < 1) {
            return $this->BAD_REQUEST(__("errors.services_required"));
        }

        foreach ($services as $service) {
            // make sure order has service 
            $order_service_exists = OrderService::where("order_id", $order->id)
                ->where("service_id", $service)
                ->exists();
            if (!$order_service_exists) {
                return $this->BAD_REQUEST(__("errors.not_order_service"));
            }
            // make sure worker can do this service 

            $worker_service_exists = WorkerService::where("worker_id", $worker->id)
                ->where("service_id", $service)
                ->exists();
            if (!$worker_service_exists) {
                return $this->BAD_REQUEST(__("errors.not_worker_service"));
            }
        }
        foreach ($services as $service) {
            $order_worker_exists = OrderWorker::where("worker_id", $worker->id)
                ->where("order_id", $order->id)
                ->where("service_id", $service)
                ->exists();
            if ($order_worker_exists) {
                // no need to throw an error
                continue;
            }
            OrderWorker::create(["order_id" => $order->id, "worker_id" => $worker->id, "service_id" => $service]);
        }
        $notification_message = NotificationMessage::create([
            "title_en" => "New Order", "title_ar" => "اوردر جديد",
            "body_en" => "A new order has been assigned to you",
            "body_ar" => "تم اضافتك الي اوردر جديد"
        ]);
        $this->sendNotification($notification_message, "worker", [$worker->id]);
        return response(["message" => __("messages.worker_assigned")]);
    }
    public function removeWorker(Request $request, Worker $worker, Order $order) {
        $order_worker = OrderWorker::where("order_id", $order->id)
            ->where("worker_id", $worker->id)
            ->first();

        if (!$order_worker) {
            return $this->NOT_FOUND(__("errors.worker_not_in_order"));
        }

        $services = $request->input("services");
        if ($services) {
            if (!is_array($services)) {
                return $this->BAD_REQUEST(__("errors.invalid_services"));
            }

            $count_of_services_in_order = OrderWorker::where("worker_id", $worker->id)
                ->where("order_id", $order->id)
                ->whereIn("service_id", $services)
                ->count();
            if ($count_of_services_in_order < count($services)) {
                return $this->BAD_REQUEST(__("errors.not_order_service"));
            }
            OrderWorker::where("worker_id", $worker->id)
                ->where("order_id", $order->id)
                ->whereIn("service_id", $services)
                ->delete();
        }
        // if no services applied deleted all worker order instances 
        if (!$services) {
            OrderWorker::where("worker_id", $worker->id)
                ->where("order_id", $order->id)
                ->delete();
        }

        return response(["message" => __("messages.worker_removed")]);
    }
}
