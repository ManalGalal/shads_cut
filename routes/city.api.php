<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;



Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [CityController::class, "create"]);
    Route::patch("/update/{city}", [CityController::class, "update"]);
    Route::delete("/delete/{city}", [CityController::class, "delete"]);
});
Route::get("/{city}", [CityController::class, "getById"]);
Route::get("/", [CityController::class, "getAll"]);
