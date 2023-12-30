<?php

use App\Http\Controllers\AnalyticController;
use Illuminate\Support\Facades\Route;


Route::middleware(["auth:api-admin"])->group(function () {
    Route::get("/{module:name}/count", [AnalyticController::class, "count"])->middleware(['throttle:100,1']);
    Route::get("/{module:name}/graph", [AnalyticController::class, "graph"])->middleware(['throttle:100,1']);
    Route::get("/count/users/order/{orderCount}", [AnalyticController::class, "countUsersOnOrderCount"]);
});
