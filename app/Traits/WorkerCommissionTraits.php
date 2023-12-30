<?php

namespace App\Traits;

use App\Models\Additive;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\Worker;

trait WorkerCommissionTraits {
    public function workerDefaultCommission($order_id, $worker_id, $service_id) {

        $service = Service::where("id", $service_id)
            ->first();
        $worker = Worker::where("id", $worker_id)
            ->first();

        if ($service->default_commission != 0) {
            $value =  ($service->default_commission / 100) * $service->price;
            Additive::create([
                "worker_id" => $worker->id,
                "branch_id" => $worker->branch_id,
                "order_id" => $order_id,
                "value" => $value,
                "note" => "$value EGP commission for completing $service->name"
            ]);
        }
    }
    public function workerAddedServiceCommission($order_id, $service_id) {

        $order_service = OrderService::where("order_id", $order_id)
            ->where("service_id", $service_id)
            ->where("added_by", "workers")
            ->first();
        if (!$order_service) {
            return;
        }
        $service = Service::where("id", $service_id)
            ->first();
        if ($service->commission != 0) {
            $value =  ($service->commission / 100) * $service->price;
            $worker = Worker::where("id", $order_service->added_by_id)
                ->first();
            Additive::create([
                "worker_id" => $worker->id,
                "branch_id" => $worker->branch_id,
                "order_id" => $order_id,
                "value" => $value,
                "note" => "$value EGP commission for adding $service->name to an order"
            ]);
        }
    }
}
