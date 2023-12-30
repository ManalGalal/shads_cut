<?php

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;



Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [StockController::class, "create"]);
    Route::post("/update/{stock}", [StockController::class, "update"])->middleware("BranchStock");
    Route::delete("/delete/{stock}", [StockController::class, "delete"])->middleware("BranchStock");
    Route::post("/refill", [StockController::class, "refill"]);
});

//TODO: add another middleware to verfiy that Worker&admin has the same branch
Route::middleware(["auth:api-admin,api-worker"])->group(function () {
    Route::get("/branch/{branch}", [StockController::class, "getForBranch"]);
    Route::get("/{stock}", [StockController::class, "getById"]);
});

Route::middleware("auth:api-worker")->prefix("worker")->group(function () {
    Route::patch("/update/{stock}", [StockController::class, "updateStockForWorker"]);
});
