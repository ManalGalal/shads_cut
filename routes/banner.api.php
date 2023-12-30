<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin", "SuperAdmin"])->group(function () {
    Route::post("/create", [BannerController::class, "create"]);
    Route::post("/update/{banner}", [BannerController::class, "update"]);
    Route::delete("/delete/{banner}", [BannerController::class, "delete"]);
});

Route::get("/all", [BannerController::class, "getAll"]);
Route::get("/featured", [BannerController::class, "getFeatured"]);
Route::get("/{banner}", [BannerController::class, "getById"]);
