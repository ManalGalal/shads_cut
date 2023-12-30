<?php

use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post("/send", [VerificationController::class, "sendMessage"]);
Route::post("/", [VerificationController::class, "verify"]);
