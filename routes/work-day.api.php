<?php

use App\Http\Controllers\WorkDayController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [WorkDayController::class, "create"]);
    Route::patch("/update/{workDay}", [WorkDayController::class, "update"]);
    Route::delete("/delete/{workDay}", [WorkDayController::class, "delete"]);
});

Route::get("/for/worker/{worker}", [WorkDayController::class, "getForWorker"]);
