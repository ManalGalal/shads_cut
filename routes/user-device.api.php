<?php

use App\Http\Controllers\UserDeviceController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-customer")->group(function () {
    Route::post("/create", [UserDeviceController::class, "create"]);
    Route::post("/update/{userDevice}", [UserDeviceController::class, "update"]);
    Route::get("/all", [UserDeviceController::class, "getAll"]);
});
