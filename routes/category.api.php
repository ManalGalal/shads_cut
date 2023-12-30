<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [CategoryController::class, "create"]);
    Route::patch("/update/{category}", [CategoryController::class, "update"]);
    Route::delete("/delete/{category}", [CategoryController::class, "delete"]);
});

Route::get("/all", [CategoryController::class, "getAll"]);
Route::get("/{category}", [CategoryController::class, "getById"]);
