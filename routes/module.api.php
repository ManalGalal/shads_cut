<?php

use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;


Route::middleware(["auth:api-admin", "BranchAllowedModules"])->group(function () {
    Route::get("/{module:name}/info", [ModuleController::class, "getModuleInfo"]);
    Route::get("/{module:name}/single/{id}", [ModuleController::class, "getModuleDataById"]);
    Route::get("/{module:name}/data", [ModuleController::class, "getModuleData"]);
});

Route::middleware("auth:api-admin", "SuperAdmin")->group(function () {
    Route::get("/all", [ModuleController::class, "getAll"]);
    Route::get("/{module:name}/deleted", [ModuleController::class, "getDeleted"]);
    Route::patch("/{module:name}/restore/{id}", [ModuleController::class, "restoreDeletedById"]);
    Route::delete("/{module:name}/delete/{id}", [ModuleController::class, "delete"]);
    Route::delete("/{module:name}/hard-delete/{id}", [ModuleController::class, "hardDelete"]);
});