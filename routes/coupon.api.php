<?php

use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;





Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [CouponController::class, "create"]);
    Route::post("/update/{coupon}", [CouponController::class, "update"]);
    Route::delete("/delete/{coupon}", [CouponController::class, "delete"]);
    Route::get("/all", [CouponController::class, "getAll"]);
    Route::get("/{coupon}", [CouponController::class, "getById"]);
});
Route::middleware(["auth:api-customer"])->group(function () {
    Route::get("/is-valid/{code}", [CouponController::class, "isValidCoupon"]);
});
