<?php

use App\Http\Controllers\AppSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin", "SuperAdmin"])->group(function () {
    Route::post("/create", [AppSettingController::class, "create"]);
    Route::post("/update/{appSetting}", [AppSettingController::class, "update"]);
    Route::delete("/delete/{appSetting}", [AppSettingController::class, "delete"]);
});

Route::get("/all", [AppSettingController::class, "getAll"]);
Route::get("/{appSetting:name}", [AppSettingController::class, "getByName"]);
