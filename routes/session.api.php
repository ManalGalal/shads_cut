<?php

use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;


Route::middleware("auth:api-worker")->group(function () {
    Route::post("/start", [SessionController::class, "start"]);
    Route::delete("/end", [SessionController::class, "end"]);
    Route::get("/my-status", [SessionController::class, "myStatus"]);
});


Route::middleware("auth:api-admin")->group(function () {
    Route::get("/status", [SessionController::class, "status"]);
});
