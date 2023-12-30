<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/send/{type}", [NotificationController::class, "send"]);
});

Route::middleware("auth:api-customer")->group(function () {
    Route::get("/all", [NotificationController::class, "getAll"]);
    Route::get("/{notification}", [NotificationController::class,  "getById"]);
    Route::delete("/delete/{notification}", [NotificationController::class, "delete"]);
});
