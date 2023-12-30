<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceStockController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [ServiceController::class, "create"]);
    Route::patch("/update/{service}", [ServiceController::class, "update"])
        ->middleware("BranchService");
    Route::delete("/delete/{service}", [ServiceController::class, "delete"])
        ->middleware("BranchService");
    Route::post("/assign/stocks/to/{service}", [ServiceStockController::class, "assign"])
        ->middleware("BranchService");
    Route::delete("/remove/stock/{stock}/from/{service}", [ServiceStockController::class, "remove"])
        ->middleware(["BranchService", "BranchStock"]);
});


Route::get("/all", [ServiceController::class, "getAll"]);
Route::get("/{service}", [ServiceController::class, "getById"]);
