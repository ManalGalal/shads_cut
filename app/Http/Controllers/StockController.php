<?php

namespace App\Http\Controllers;

use App\Http\Requests\createStock;
use App\Http\Requests\refillStocks;
use App\Http\Requests\updateStock;
use App\Http\Requests\workerUpdateStock;
use App\Models\Branch;
use App\Models\Stock;
use App\Traits\DeleteFiles;
use Illuminate\Http\Request;

class StockController extends Controller {
    use DeleteFiles;
    public function create(createStock $request) {
        $validated = $request->validated();
        if ($request->user()->role === "normal") {
            $validated["branch_id"] = $request->user()->branch_id;
        }
        if ($request->hasFile("image")) {
            $validated["image"] = $request->file("image")->store("/stock/images");
        }
        $stock = Stock::create($validated);
        return response(["message" => __("messages.stock_created"), "stock" => $stock], 201);
    }
    public function update(updateStock $request, Stock $stock) {
        $validated = $request->validated();
        if ($request->hasFile("image")) {
            $this->deleteFile($stock->image);
            $validated["image"] = $request->file("image")->store("/stock/images");
        }
        $stock->update($validated);
        return response(["message" => __("messages.stock_updated"), "stock" => $stock]);
    }
    public function refill(refillStocks $request) {
        $validated = $request->validated();
        foreach ($validated["stocks"] as $stock_id) {
            $stock = Stock::where("id", $stock_id)->first();
            if ($request->user()->isBranchAdmin() && $request->user()->branch_id != $stock->branch_id) {
                continue;
            }
            if ($stock->quantity > $stock->max_quantity) {
                continue;
            }

            $stock->quantity = $stock->max_quantity;
            $stock->save();
        }
        return response(["message" => __("messages.stocks_refilled")]);
    }
    public function delete(Stock $stock) {
        $stock->delete();
        return response(["messsage" => __("messages.stock_deleted")]);
    }
    public function getForBranch(Request $request, Branch $branch) {
        $stocks = Stock::where("branch_id", $branch->id)
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["stocks" => $stocks]);
    }
    public function getById(Stock $stock) {
        return response(["stock" => $stock]);
    }
    public function updateStockForWorker(workerUpdateStock $request, Stock $stock) {
        $validated = $request->validated();
        $stock->update($validated);
        return response(["message" => __("messages.stock_updated"), "stock" => $stock]);
    }
}
