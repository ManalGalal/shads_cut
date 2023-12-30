<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::middleware("HMACAuth")->group(function () {
    Route::get("/paymob/response-callback", [PaymentController::class, "paymobResonseCallback"]);
    Route::post("/paymob/processed-callback", [PaymentController::class, "paymobProcessedCallback"]);
});
