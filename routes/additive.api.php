<?php

use App\Http\Controllers\AdditiveController;
use Illuminate\Support\Facades\Route;



Route::middleware(["auth:api-admin", "BranchAdditive"])->group(function () {
    Route::post("/create", [AdditiveController::class, "create"])->withoutMiddleware("BranchAdditive");
    Route::patch("/update/{additive}", [AdditiveController::class, "update"]);
    Route::delete("/delete/{additive}", [AdditiveController::class, "delete"]);
    Route::get("/all", [AdditiveController::class, "getAll"])->withoutMiddleware("BranchAdditive");
    Route::get("/{additive}", [AdditiveController::class, "getById"]);
});
