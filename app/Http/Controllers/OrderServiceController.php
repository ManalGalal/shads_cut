<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\Worker;
use App\Traits\HttpErrors;
use App\Traits\OrderServiceTraits;
use Illuminate\Http\Request;
use Throwable;

class OrderServiceController extends Controller {
    use HttpErrors, OrderServiceTraits;
    public function addServiceToOrder(Request $request, Service $service, Order $order) {
        if (!$this->validateOrder($request, $order)) {
            return $this->BAD_REQUEST(__("errors.invalid_order"));
        }
        if (!$this->validateService($request, $service, $order)) {
            return $this->BAD_REQUEST(__("errors.invalid_service"));
        }

        $exists = OrderService::where("order_id", $order->id)
            ->where("service_id", $service->id)
            ->exists();
        if ($exists) {
            return $this->BAD_REQUEST(__("errors.service_in_order"));
        }
        $this->applyServiceEffects($service, $order);
        $added_by = $request->user()->isWorker() ? "workers" : "admins";

        OrderService::create([
            "service_id" => $service->id,
            "order_id" => $order->id,
            "added_by" => $added_by,
            "added_by_id" => $request->user()->id
        ]);
        $request->merge(["services" => [$service->id]]);

        $worker = null;
        $worker_added = false;
        
        if ($added_by === "workers") {
            $worker = Worker::where("id", $request->user()->id)
                ->first();
        }
        if ($worker) {
            try {
                $response = (new OrderWorkerController())->assignWorker($request, $worker, $order);
                // Indication that worker is added;
                if ($response->isSuccessful()) {
                    $worker_added = true;
                }
            } catch (Throwable $th) {
                // Do nothing for now 
            }
        }

        return response(["message" => __("messages.service_assigned"), "worker_added" => $worker_added]);
    }
    public function removeServiceFromOrder(Service $service, Order $order) {
        $found = OrderService::where("order_id", $order->id)
            ->where("service_id", $service->id)
            ->first();
        if (!$found) {
            return $this->NOT_FOUND(__("errors.service_not_in_order"));
        }
        $this->removeServiceEffects($service, $order);
        $this->removeOrderWorker($order, $service);
        $found->delete();
        return response(["message" => __("messages.service_removed")]);
    }
}
