<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [BrandController::class, "create"]);
    Route::post("/update/{brand}", [BrandController::class, "update"]);
    Route::delete("/delete/{brand}", [BrandController::class, "delete"]);
});


Route::get("/all", [BrandController::class, "getAll"]);
Route::get("/{brand}", [BrandController::class, "getById"]);
