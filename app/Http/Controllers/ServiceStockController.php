<?php

namespace App\Http\Controllers;

use App\Http\Requests\assignServiceStocks;
use App\Models\Service;
use App\Models\ServiceStock;
use App\Models\Stock;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class ServiceStockController extends Controller {
    use HttpErrors;
    public function assign(assignServiceStocks $request, Service $service) {
        $validated = $request->validated();
        foreach ($validated["stocks"] as $stock) {
            if ($request->user()->role == "normal") { // branch admin
                $branch_stock = Stock::where("id", $stock["id"])
                    ->where("branch_id", $request->user()->branch_id)
                    ->exists();
                if (!$branch_stock) {
                    return $this->FORBIDDEN();
                }
            }

            $service_stock_exists = ServiceStock::where("stock_id", $stock["id"])
                ->where("service_id", $service->id)
                ->exists();
            if ($service_stock_exists) {
                // (<-/?_?\->) 
                continue;
            }
            ServiceStock::create([
                "service_id" => $service->id,
                "stock_id" => $stock["id"],
                "used_amount" => $stock["used_amount"]
            ]);
        }
        return response(["message" => __("messages.service_stocks_added")]);
    }
    public function remove(Stock $stock, Service $service) {
        ServiceStock::where("service_id", $service->id)
            ->where("stock_id", $stock->id)
            ->delete();
        return response(["message" => __("messages.service_stock_removed")]);
    }
}
