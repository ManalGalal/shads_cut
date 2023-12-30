<?php

use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin", "BranchAdminWorker"])->group(function () {
    Route::post("/create", [WorkerController::class, "create"])->withoutMiddleware("BranchAdminWorker");
    Route::post("/update/{worker}", [WorkerController::class, "update"]);
    Route::delete("/delete/{worker}", [WorkerController::class, "delete"]);
    Route::post("/upload/profile-picture/{worker}", [WorkerController::class, "uploadProfilePicture"]);

    Route::middleware("BranchService")->group(function () {
        Route::post("/assign/{worker}/service/{service}", [WorkerServiceController::class, "assignToWorker"]);
        Route::delete("/remove/{worker}/service/{service}", [WorkerServiceController::class, "removeFromWorker"]);
    });
});
Route::middleware("auth:api-worker")->group(function () {
    Route::get("/profile", [WorkerController::class, "getProfile"]);
    Route::get("/salary", [WorkerController::class, "getSalary"]);
});
Route::get("/all", [WorkerController::class, "getAll"]);
Route::get("/{worker}", [WorkerController::class, "getById"]);
