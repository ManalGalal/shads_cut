<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-customer"])->group(function () {
    Route::post("/create", [AddressController::class, "create"]);
    Route::post("/update/{address}", [AddressController::class, "update"]);
    Route::delete("/delete/{address}", [AddressController::class, "delete"]);
    Route::get("/my-addresses", [AddressController::class, "getMyAddresses"]);
    Route::get("/{address}", [AddressController::class, "getById"]);
});

Route::middleware(["auth:api-admin"])->prefix("admin")->group(function(){
    Route::post("/create",[AddressController::class, "createForAdmin"]);
    Route::post("/update/{address}",[AddressController::class, "updateForAdmin"]);
});