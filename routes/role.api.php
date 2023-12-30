<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


Route::middleware(["auth:api-admin", "SuperAdmin"])->group(function () {
    Route::post("/create", [RoleController::class, "create"]);
    Route::post("/update/{role}", [RoleController::class, "update"]);
    Route::post("/add/permissions/to/{role}", [RoleController::class, "addPermissionsToRole"]);
    Route::delete("/remove/{permission}/from/{role}", [RoleController::class, "removePermissionFromRole"]);

    Route::post("/user/assign/{role}/to/{admin}", [RoleController::class, "assignRoleToUser"]);
    Route::delete("/user/remove/{role}/from/{admin}", [RoleController::class, "removeRoleFromUser"]);

    Route::get("all", [RoleController::class, "getAll"]);
});
