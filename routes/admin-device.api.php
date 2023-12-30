<?php

use App\Http\Controllers\AdminDeviceController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [AdminDeviceController::class, "create"]);
    Route::post("/update/{adminDevice}", [AdminDeviceController::class, "update"]);
    Route::get("/all", [AdminDeviceController::class, "getAll"]);
});
