<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStock;
use App\Models\Stock;
use App\Traits\HttpErrors;
use App\Traits\ServiceStockTraits;
use Illuminate\Http\Request;

class OrderStockController extends Controller {
    use HttpErrors, ServiceStockTraits;
    public function addStockToOrder(Request $request, Stock $stock, Order $order) {
        $used_amount = $request->input("used_amount");
        if (!is_numeric($used_amount) || $used_amount < 0) {
            return $this->BAD_REQUEST(__("errors.invalid_used_amount"));
        }
        //calculate max amount that can be used; 
        $max_amount = ($stock->use_times * $stock->quantity) + $stock->left_over;
        if ($used_amount > $max_amount) {
            return $this->BAD_REQUEST(__("errors.large_used_amount"));
        }
        $this->updateStock($stock, $used_amount);
        $order_stock = OrderStock::where("stock_id", $stock->id)
            ->where("order_id", $order->id)
            ->first();

        if (!$order_stock) {
            $order_stock = OrderStock::create(["stock_id" => $stock->id, "order_id" => $order->id]);
        }
        $order_stock->update(["used_amount" => $used_amount]);
        return response(["message" => __("messages.stock_added")]);
    }
    public function removeStockFromOrder(OrderStock $orderStock) {
        $orderStock->delete();
        return response(["message" => __("messages.stock_removed")]);
    }
}
