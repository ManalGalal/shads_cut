<?php

use App\Http\Controllers\SupportFormController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-customer")->group(function () {
    Route::post("/create", [SupportFormController::class, "create"]);
});

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/update/{form}", [SupportFormController::class, "update"]);
    Route::delete("/delete/{form}", [SupportFormController::class, "delete"]);
    Route::get("/all", [SupportFormController::class, "getAll"]);
    Route::get("/{form}", [SupportFormController::class, "getById"]);
});
