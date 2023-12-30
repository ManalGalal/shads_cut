<?php

namespace App\Traits;

use App\Models\AppSetting;
use App\Models\Order;

trait OrderChanges {
    public function addTax($id) {
        $TAX = AppSetting::where("name", "TAX")
            ->first();
        if (!$TAX) {
            return;
        }
        $order = Order::where("id", $id)
            ->first();
        $old_tax = $order->tax;
        $order->total_amount -= $old_tax;
        $new_tax = ($TAX->value / 100) * $order->total_amount;
        $order->tax = $new_tax;
        $order->total_amount += $new_tax;
        // It must be saved quietly to no go into infinte recurrsion.
        $order->saveQuietly();
    }
}
