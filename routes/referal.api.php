<?php

use App\Http\Controllers\ReferalCodeController;
use Illuminate\Support\Facades\Route;

Route::get("/code", [ReferalCodeController::class, "generateCode"])->middleware("auth:api-customer");

Route::middleware(["auth:api-admin"])->group(function () {
    Route::get("/all", [ReferalCodeController::class, "getAll"]);
    Route::delete("/delete/{code}", [ReferalCodeController::class, "delete"]);
});
