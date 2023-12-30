<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;


Route::middleware("auth:api-admin")->group(function () {
    Route::get("/all", [PermissionController::class, "getAll"]);
    Route::get("/{permission}", [PermissionController::class, "getById"]);
});
