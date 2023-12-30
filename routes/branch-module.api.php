<?php

use App\Http\Controllers\BranchModuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:api-admin", "BranchAdmin"])->group(function () {
    Route::get("/all", [BranchModuleController::class, "getAll"]);
    Route::get("/{module:name}/single/{id}", [BranchModuleController::class, "getModuleDataById"]);
    Route::get("/{module:name}/data", [BranchModuleController::class, "getModuleData"]);
});
