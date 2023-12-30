<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\NotificationMessage;
use App\Models\OrderService;
use App\Models\OrderWorker;
use App\Models\WorkerService;
use Illuminate\Support\Arr;

trait OrderCreationTraits {
    use NotificationTraits;
    public function addOrderServices($validated, $order) {
        foreach ($validated["services"] as $service) {
            OrderService::create(["order_id" => $order->id, "service_id" => $service]);
        }
    }
    public function addOrderWorkers($validated, $order) {
        if (Arr::has($validated, "workers")) {
            foreach ($validated["workers"] as $worker) {
                $worker_added = false;
                foreach ($validated["services"] as $service) {
                    if ($this->workerHasService($worker, $service)) {
                        OrderWorker::create(["worker_id" => $worker, "order_id" => $order->id, "service_id" => $service]);
                        $worker_added = true;
                    }
                }
                if ($worker_added) {
                    $notification_message = NotificationMessage::create([
                        "title_en" => "New Order", "title_ar" => "اوردر جديد",
                        "body_en" => "A new order has been assigned to you",
                        "body_ar" => "تم اضافتك الي اوردر جديد"
                    ]);
                    $this->sendNotification($notification_message, "worker", [$worker]);
                }
            }
        }
    }
    public function sendNotificationForAdmins($order) {
        $admin_ids = Admin::where("role", "super")
            ->orWhere("branch_id", $order->branch_id)
            ->get()
            ->map(function ($admin) {
                return $admin->id;
            }) ?? [];
        $notification_message = NotificationMessage::create([
            "title_en" => "New Order", "title_ar" => "اوردر جديد",
            "body_en" => "A new order #$order->id has been created from Mobile",
            "body_ar" => "تم حجز اوردر جديد #$order->id من الموبايل"
        ]);
        $this->sendNotification($notification_message, "admin", $admin_ids);
    }
    /**
     * @return bool
     */
    private function workerHasService($worker, $service) {
        return WorkerService::where("worker_id", $worker)->where("service_id", $service)->exists();
    }
    public function wallet($request, $validated, $order) {
        $user = $request->user();
        $total_amount = $validated["total_amount"];
        if ($user->wallet < $total_amount) {
            $order->update(["payment_method" => "cash"]);
            return;
            //throw new HttpException(400, __("errors.not_enough_money"));
        }
        $user->wallet -= $total_amount;
        $user->save();
        $order->update(["total_paid" => $total_amount]);
    }
}
