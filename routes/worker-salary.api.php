<?php

use App\Http\Controllers\WorkerSalaryController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [WorkerSalaryController::class, "create"]);
    Route::post("/update/{workerSalary}", [WorkerSalaryController::class, "update"]);
});
