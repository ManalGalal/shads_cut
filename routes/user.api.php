<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/create", [UserController::class, "create"]);
Route::middleware("auth:api-customer")->group(function () {
    Route::patch("/update", [UserController::class, "update"]);
    Route::post("/upload/profile-picture", [UserController::class, "uploadProfilePicture"]);
    Route::patch("/change-password", [UserController::class, "changePassword"]);
    Route::patch("/update-phone", [UserController::class, "updatePhone"]);
    Route::get("/profile", [UserController::class, "getProfile"]);
    Route::post("/logout", [UserController::class, "logout"]);
});
Route::patch("/forget-password", [UserController::class, "forgetPassword"]);
Route::middleware(["auth:api-admin", "SuperAdmin"])->prefix("admin")->group(function () {
    Route::post("/create", [UserController::class, "createForAdmin"]);
    Route::post("/update/{user}", [UserController::class, "updateForAdmin"]);
    Route::get("/all", [UserController::class, "getAll"]);
    Route::get("/{user}", [UserController::class, "getById"]);
});
