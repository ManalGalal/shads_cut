<?php

use App\Http\Controllers\RedeemController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-customer")->group(function () {
    Route::post("/my-points", [RedeemController::class, "myPoints"]);
});


Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/user-points/{user}", [RedeemController::class, "userPoints"]);
});
