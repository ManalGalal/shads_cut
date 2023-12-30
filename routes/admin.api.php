<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin", "SuperAdmin"])->group(function () {
    Route::post("/create", [AdminController::class, "create"]);
    Route::patch("/update/{admin}", [AdminController::class, "update"]);
    Route::delete("/delete/{admin}", [AdminController::class, "delete"]);
    Route::get("/all", [AdminController::class, "getAll"]);
    Route::get("/dashboard-info", [AdminController::class, "dashboardInfo"])
        ->withoutMiddleware("SuperAdmin");
    Route::get("/{admin}", [AdminController::class, "getById"]);
});
