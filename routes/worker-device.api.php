<?php

use App\Http\Controllers\WorkerDeviceController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-worker")->group(function () {
    Route::post("/create", [WorkerDeviceController::class, "create"]);
    Route::post("/update/{workerDevice}", [WorkerDeviceController::class, "update"]);
    Route::get("/all", [WorkerDeviceController::class, "getAll"]);
});
