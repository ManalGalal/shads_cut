<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware("ValidateType")->group(function () {
    Route::post("/{type}/login", [AuthController::class, "userLogin"]);
    Route::post("/{type}/refresh_token", [AuthController::class, "refreshToken"]);
});

Route::post("/facebook", [SocialLoginController::class, "facebookLogin"]);
Route::post("/google", [SocialLoginController::class, "googleLogin"]);
Route::post("/apple", [SocialLoginController::class, "appleLogin"]);
