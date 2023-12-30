<?php

use App\Http\Controllers\BranchProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin"])->group(function () {
    Route::post("/create", [ProductController::class, "create"]);
    Route::patch("/update/{product}", [ProductController::class, "update"]);
    Route::post("/upload/image/{product}", [ProductController::class, "uploadImage"]);
    Route::delete("/delete/{product}", [ProductController::class, "delete"]);
    Route::post("/assign/products", [BranchProductController::class, "assign"]);
    Route::delete("/remove/{product}", [BranchProductController::class, "remove"]);
    Route::post("/refill", [BranchProductController::class, "refill"]);
});

Route::middleware(["auth:api-admin,api-worker"])->group(function () {
    Route::get("/for/branch", [ProductController::class, "getProductsForBranch"]);
});


Route::get("/featured", [ProductController::class, "getFeaturedProducts"]);
Route::get("/all", [ProductController::class, "getAll"]);
Route::get("/for/{category}", [ProductController::class, "getProductFor"]);
Route::get("/{product}", [ProductController::class, "getById"]);