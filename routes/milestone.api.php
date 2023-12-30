<?php

use App\Http\Controllers\MilestoneController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-customer")->group(function () {
    Route::get("/my-milestones", [MilestoneController::class, "myMileStones"]);
});

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [MilestoneController::class, "create"]);
    Route::get("/all", [MilestoneController::class, "getAll"]);
    Route::get("/user/{user}", [MilestoneController::class, "userMileStones"]);
    Route::get("/{milestone}", [MilestoneController::class, "getById"]);
    Route::delete("/delete/{milestone}", [MilestoneController::class, "delete"]);
});
