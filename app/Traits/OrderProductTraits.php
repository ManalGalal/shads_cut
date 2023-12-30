<?php


namespace App\Traits;

use App\Models\Additive;
use App\Models\BranchProduct;
use App\Models\OrderProduct;
use App\Models\OrderWorker;
use App\Models\Product;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait OrderProductTraits {
    /**
     * Return BranchProduct or throws an Error If the product is invalid.
     * @return App\Models\BranchProduct::class
     */

    public function validateOrder($request, $order) {
        if ($request->user()->isWorker()) {
            $worker_has_order = OrderWorker::where("order_id", $order->id)
                ->where("worker_id", $request->user()->id)
                ->exists();
            return $worker_has_order;
        }
        return true;
    }
    public function validateProduct($request, $product, $order) {
        // make sure the branch or worker have this product 
        // Since there is A BranchOrder middleware before this request then we are sure ...
        // about the branch_id is the same as the user doing that request.
        $branch_product = BranchProduct::where("product_id", $product["id"])
            ->where("branch_id", $order->branch_id)
            ->first();
        if (!$branch_product) {
            throw new NotFoundHttpException(__("errors.product_not_in_branch"));
        }
        if (OrderProduct::where("product_id", $product["id"])
            ->where("order_id", $order->id)->exists()
        ) {
            throw new BadRequestHttpException(__("errors.product_exists_in_order"));
        }
        if ($branch_product->quantity < $product["quantity"]) {
            throw new BadRequestHttpException(__("errors.not_enough_products"));
        }
        return $branch_product;
    }
    public function applyProductToOrder($branch_product, $order, $quantity) {
        // check if order has coupon

        // adding price to $branch_product
        $branch_product->price = Product::where("id", $branch_product->product_id)
            ->first()->price  * $quantity;

        if ($order->coupon_id) {
            $coupon = $order->coupon;
            // if the coupon type is percentage => then apply the coupon to the branch_product price and added it to order
            if ($coupon->type === "percentage") {
                $branch_product_discounted_amount = $branch_product->price * $coupon->value / 100;
                $branch_product_total_amount = $branch_product->price - $branch_product_discounted_amount;
                return $this->updateOrder($branch_product_total_amount, $branch_product_discounted_amount, $order);
            }
            // if coupon is fixed and it's value are bigger than the discounted amount
            // example order total_amount = 0 and discounted_amount = 60 and coupon value = 70 meaning there are more discount
            if ($coupon->value > $order->discounted_amount) {
                $remaining_coupon_value = $coupon->value - $order->discounted_amount;
                if ($remaining_coupon_value > $branch_product->price) {
                    $branch_product_total_amount = 0;
                    $branch_product_discounted_amount = $branch_product->price;
                    return $this->updateOrder($branch_product_total_amount, $branch_product_discounted_amount, $order);
                }
                $branch_product_total_amount = $branch_product->price - $remaining_coupon_value;
                $branch_product_discounted_amount = $remaining_coupon_value;
                return $this->updateOrder($branch_product_total_amount, $branch_product_discounted_amount, $order);
            }
        }
        $this->updateOrder($branch_product->price, 0, $order);
        unset($branch_product["price"]);
        $this->updateBranchProduct($branch_product, $quantity);

        return true;
    }
    public function addAdditiveForWorker($product_id, $quantity, $worker) {
        $product = Product::where("id", $product_id)->first();
        if ($product->commission <= 0) {
            return;
        }
        $value = ($product->price * $quantity * $product->commission) / 100;
        Additive::create([
            "worker_id" => $worker->id,
            "product_id" => $product_id,
            "branch_id" => $worker->branch_id,
            "value" => $value,
            "note" => "$value EGP Commission for selling $quantity of $product->name"
        ]);
    }
    public function removeProductEffects($product, $order) {
        // check if order has coupon 
        $branch_product = BranchProduct::where("product_id", $product->id)
            ->where("branch_id", $order->branch_id)
            ->first();
        $order_product = OrderProduct::where("product_id", $product->id)
            ->where("order_id", $order->id)
            ->first();

        if ($order->coupon_id) {
            $coupon = $order->coupon;
            // if the coupon type is percentage => then apply the coupon to the order_product price and added it to order
            // TODO: This needs to be tested
            if ($coupon->type === "percentage") {
                $order_product_discounted_amount = $order_product->price * $coupon->value / 100;
                $order_product_total_amount = $order_product->price - $order_product_discounted_amount;
                return $this->updateOrder(-$order_product_total_amount, -$order_product_discounted_amount, $order);
            }
            // if coupon is fixed 
            // first case that the order_product price are lower than or equal total_amount meaning it wont take anything 
            // from the discounted amount of the order 
            // NOTE: you should try this with a paper and a pen cause it's somehow tricky but i assure you the logic is correct
            if ($order_product->price <= $order->total_amount) {
                $order->total_amount -= $order_product->price;
                $order->save();
                return true;
            }
            if ($order_product->price > $order->total_amount) {
                $order_product_old_discounted_amount = $order_product->price - $order->total_amount;
                $order->total_amount = 0;
                $order->discounted_amount -= $order_product_old_discounted_amount; // 130 => 130 - 60 = 70 meaning this was the real value before adding the order_product
                $order->save();
            }
        }
        // no coupon
        // make sure this if condition exists incase the order_product was upped in prices 

        if ($order_product->price > $order->total_amount) {
            $order->total_amount = 0;
            $order->save();
            return true;
        }

        // normal case 
        $order->total_amount -= $order_product->price;
        $order->save();
        // adding the quantity back to the branc_product again
        $this->updateBranchProduct($branch_product, -$order_product->quantity);
        return true;
    }
    private function updateOrder($branch_product_total_amount, $branch_product_discounted_amount, $order) {
        $order->total_amount += $branch_product_total_amount;
        $order->discounted_amount += $branch_product_discounted_amount;
        $order->save();
        return true;
    }
    private function updateBranchProduct($branch_product, $quantity) {
        $branch_product->quantity -= $quantity;
        $branch_product->save();
    }
    public function addProductToOrder($product_id, $order_id, $quantity) {
        OrderProduct::create([
            "order_id" => $order_id,
            "product_id" => $product_id,
            "quantity" => $quantity,
            "price" => Product::where("id", $product_id)
                ->first()->price  * $quantity
        ]);
    }
}
