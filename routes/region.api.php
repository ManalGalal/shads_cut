<?php

use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;


Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [RegionController::class, "create"]);
    Route::patch("/update/{region}", [RegionController::class, "update"]);
    Route::delete("/delete/{region}", [RegionController::class, "delete"]);
});
Route::get("/{region}", [RegionController::class, "getById"]);
Route::get("/", [RegionController::class, "getAll"]);
