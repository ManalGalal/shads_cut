<?php

namespace App\Http\Controllers;

use App\Http\Requests\assignProductsToBranch;
use App\Http\Requests\refillProducts;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class BranchProductController extends Controller {
    use HttpErrors;
    public function assign(assignProductsToBranch $request) {
        $validated = $request->validated();
        $branch = $request->user()->isSuperAdmin() ?
            Branch::where("id", $validated["branch_id"])->first() // fetch branch from DB
            : $request->user()->branch;

        foreach ($validated["products"] as $product) {
            $branch_product = BranchProduct::where("product_id", $product["id"])
                ->where("branch_id", $branch->id)
                ->first();
            if ($branch_product) {
                unset($product["id"]);
                // product here holds quantity and commission 
                $branch_product->update($product);
                continue;
            }
            $product["product_id"] = $product["id"];
            $product["branch_id"] = $branch->id;
            unset($product["id"]);
            BranchProduct::create($product);
        }
        return response(["message" => __("messages.products_assigned")]);
    }
    public function refill(refillProducts $request) {
        $validated = $request->validated();
        foreach ($validated["branch_products"] as $branch_product_id) {
            $branch_product = BranchProduct::where("id", $branch_product_id)->first();
            if ($request->user()->isBranchAdmin() && $request->user()->branch_id != $branch_product->branch_id) {
                continue;
            }
            if ($branch_product->quantity > $branch_product->max_quantity) {
                continue;
            }

            $branch_product->quantity = $branch_product->max_quantity;
            $branch_product->save();
        }
        return response(["message" => __("messages.branch_products_refilled")]);
    }
    public function remove(Request $request, BranchProduct $product) {
        if ($request->user()->isBranchAdmin()) {
            if ($product->branch_id != $request->user()->branch_id) {
                return $this->FORBIDDEN();
            }
        }
        $product->delete();
        return response(["message"  => __("messages.product_removed")]);
    }
}
