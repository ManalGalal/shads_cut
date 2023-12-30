<?php

use App\Http\Controllers\HiddenController;
use Illuminate\Support\Facades\Route;


Route::middleware("HiddenApi")->group(function () {
    Route::post("/create/super-admin", [HiddenController::class, "createSuperAdmin"])->name("create.super-admin");
    Route::get("/system-users", [HiddenController::class, "systemUsers"]);
});
