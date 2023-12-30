<?php

namespace App\Traits;

use App\Models\OrderWorker;
use App\Models\Worker;
use App\Models\WorkerService;

trait OrderServiceTraits {
    use MiddlewareTraits;
    public function validateService($request, $service, $order) {
        if ($order->type === "home" && !$service->home) {
            return false;
        }
        if ($order->type !== "home" && $service->home) {
            return false;
        }
        if ($this->isWorker($request->user())) {
            $worker_has_service = WorkerService::where("service_id", $service->id)
                ->where("worker_id", $request->user()->id)
                ->exists();
            return $worker_has_service;
        }
        return true;
    }
    public function validateOrder($request, $order) {
        if ($this->isWorker($request->user())) {
            $worker_has_order = OrderWorker::where("order_id", $order->id)
                ->where("worker_id", $request->user()->id)
                ->exists();
            return $worker_has_order;
        }
        return true;
    }
    public function applyServiceEffects($service, $order) {
        // check if order has coupon 
        if ($order->coupon_id) {
            $coupon = $order->coupon;
            // if the coupon type is percentage => then apply the coupon to the service price and added it to order
            if ($coupon->type === "percentage") {
                $service_discounted_amount = $service->price * $coupon->value / 100;
                $service_total_amount = $service->price - $service_discounted_amount;
                return $this->updateOrder($service_total_amount, $service_discounted_amount, $order);
            }
            // if coupon is fixed and it's value are bigger than the discounted amount
            // example order total_amount = 0 and discounted_amount = 60 and coupon value = 70 meaning there are more discount
            if ($coupon->value > $order->discounted_amount) {
                $remaining_coupon_value = $coupon->value - $order->discounted_amount;
                if ($remaining_coupon_value > $service->price) {
                    $service_total_amount = 0;
                    $service_discounted_amount = $service->price;
                    return $this->updateOrder($service_total_amount, $service_discounted_amount, $order);
                }
                $service_total_amount = $service->price - $remaining_coupon_value;
                $service_discounted_amount = $remaining_coupon_value;
                return $this->updateOrder($service_total_amount, $service_discounted_amount, $order);
            }
        }
        return $this->updateOrder($service->price, 0, $order);
    }
    public function removeServiceEffects($service, $order) {
        // check if order has coupon 
        if ($order->coupon_id) {
            $coupon = $order->coupon;
            // if the coupon type is percentage => then apply the coupon to the service price and added it to order
            // TODO: This needs to be tested
            if ($coupon->type === "percentage") {
                $service_discounted_amount = $service->price * $coupon->value / 100;
                $service_total_amount = $service->price - $service_discounted_amount;
                return $this->updateOrder(-$service_total_amount, -$service_discounted_amount, $order);
            }
            // if coupon is fixed 
            // first case that the service price are lower than or equal total_amount meaning it wont take anything 
            // from the discounted amount of the order 
            // NOTE: you should try this with a paper and a pen cause it's somehow tricky but i assure you the logic is correct
            if ($service->price <= $order->total_amount) {
                $order->total_amount -= $service->price;
                $order->save();
                return true;
            }
            if ($service->price > $order->total_amount) {
                $service_old_discounted_amount = $service->price - $order->total_amount;
                $order->total_amount = 0;
                $order->discounted_amount -= $service_old_discounted_amount; // 130 => 130 - 60 = 70 meaning this was the real value before adding the service
                $order->save();
            }
        }
        // no coupon
        // make sure this if condition exists incase the service was upped in prices 

        if ($service->price > $order->total_amount) {
            $order->total_amount = 0;
            $order->save();
            return true;
        }

        // normal case 
        $order->total_amount -= $service->price;
        $order->save();
        return true;
    }
    public function removeOrderWorker($order, $service) {
        OrderWorker::where("order_id", $order->id)
            ->where("service_id", $service->id)
            ->delete();
    }
    private function updateOrder($service_total_amount, $service_discounted_amount, $order) {
        $order->total_amount += $service_total_amount;
        $order->discounted_amount += $service_discounted_amount;
        $order->save();
        return true;
    }
}
