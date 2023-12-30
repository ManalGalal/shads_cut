<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\OrderServiceController;
use App\Http\Controllers\OrderStockController;
use App\Http\Controllers\OrderWorkerController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-customer")->group(function () {
    Route::post("/create", [OrderController::class, "create"]);
    Route::patch("/cancel/{order}", [OrderController::class, "cancel"])->middleware("CustomerOrder");
    Route::get("/my-orders", [OrderController::class, "myOrders"]);
    Route::get("/customer/{order}", [OrderController::class, "customerOrderByid"])->middleware("CustomerOrder");
    Route::patch("/feed-back/{order}", [OrderController::class, "giveFeedback"])->middleware("CustomerOrder");
    Route::post("/pay/{order}", [OrderController::class, "payOrder"]);
});

Route::middleware(["auth:api-admin"])->prefix("branch")->group(function () {
    Route::post("/{branch}/create", [OrderController::class, "createForAdmin"])
        ->middleware("SuperOrBranchAdmin");
    Route::post("/update/{order}", [OrderController::class, "updateForAdmin"])->middleware("BranchOrder");
    Route::post("/refund/{order}", [OrderController::class, "refundOrder"]);
    Route::post("/discount/{order}", [OrderController::class, "dashboardDiscount"]);
    Route::post("/pay/{order}", [OrderController::class, "payOrderForAdmin"])->middleware("BranchOrder");
    Route::get("/orders", [OrderController::class, "branchOrders"])->middleware("BranchAdmin");
    Route::get("/{order}", [OrderController::class, "branchOrderById"])->middleware("BranchOrder");
    Route::post("/assign/worker/{worker}/to/{order}", [OrderWorkerController::class, "assignWorker"])
        ->middleware(["BranchAdminWorker", "BranchOrder"]);
    Route::delete("/remove/worker/{worker}/from/{order}", [OrderWorkerController::class, "removeWorker"])
        ->middleware(["BranchAdminWorker", "BranchOrder"]);
});



/*
    order worker routes 
*/

Route::middleware(["auth:api-admin,api-worker"])->prefix("worker")->group(function () {
    Route::post("/add/stock/{stock}/to/{order}", [OrderStockController::class, "addStockToOrder"])
        ->middleware(["BranchOrder", "BranchStock"]);
    Route::delete("/remove/stock/{stock}/from/{order}", [OrderStockController::class, "removeStockFromOrder"])
        ->middleware(["BranchOrder", "BranchStock"]);

    Route::post("/add/service/{service}/to/{order}", [OrderServiceController::class, "addServiceToOrder"])
        ->middleware(["BranchOrder"]);
    Route::delete(
        "/remove/service/{service}/from/{order}",
        [OrderServiceController::class, "removeServiceFromOrder"]
    )->middleware(["BranchOrder"]);
    Route::post("/add/products/to/{order}", [OrderProductController::class, "add"])
        ->middleware(["BranchOrder"]);
    Route::delete("/remove/product/{product}/from/{order}", [OrderProductController::class, "remove"])
        ->middleware(["BranchOrder"]);
});

Route::middleware(["auth:api-worker"])->prefix("worker")->group(function () {
    Route::get("/orders", [OrderController::class, "getWorkerOrders"]);
    Route::get("/{order}", [OrderController::class, "getWorkerOrderById"]);
});
