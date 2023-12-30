<?php

use App\Http\Controllers\DayOffController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-worker")->group(function(){
    Route::post("/request",[DayOffController::class, "request"]);
    Route::get("/my-daysoff",[DayOffController::class, "myDaysoff"]);
});

Route::middleware("auth:api-admin")->group(function(){
    Route::post("/create", [DayOffController::class, "create"]);
    Route::patch("/update/{dayOff}",[DayOffController::class, "update"]);
});