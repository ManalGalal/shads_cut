<?php

namespace App\Http\Controllers;

use App\Http\Requests\addProductsToOrder;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Traits\HttpErrors;
use App\Traits\OrderProductTraits;
use Illuminate\Http\Request;

class OrderProductController extends Controller {
    use OrderProductTraits, HttpErrors;
    public function add(addProductsToOrder $request, Order $order) {

        $validated = $request->validated();
        if (!$this->validateOrder($request, $order)) {
            return $this->BAD_REQUEST(__("errors.invalid_order"));
        }
        foreach ($validated["products"] as $product) {
            $branch_product = $this->validateProduct($request, $product, $order);
            $this->applyProductToOrder($branch_product, $order, $product["quantity"]);
            $this->addProductToOrder($product["id"], $order->id, $product["quantity"]);
            if ($request->user()->isWorker()) {
                $this->addAdditiveForWorker($product["id"], $product["quantity"], $request->user());
            }
        }
        return response(["message" => __("messages.products_added")]);
    }
    public function remove(Product $product, Order $order) {
        $found = OrderProduct::where("order_id", $order->id)
            ->where("product_id", $product->id)
            ->first();
        if (!$found) {
            return $this->NOT_FOUND(__("errors.product_not_in_order"));
        }
        $this->removeProductEffects($product, $order);
        $found->delete();
        return response(["message" => __("messages.product_removed")]);
    }
}
