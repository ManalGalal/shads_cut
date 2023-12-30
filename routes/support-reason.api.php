<?php

use App\Http\Controllers\SupportReasonController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [SupportReasonController::class, "create"]);
    Route::post("/update/{reason}", [SupportReasonController::class, "update"]);
    Route::delete("/delete/{reason}", [SupportReasonController::class, "delete"]);
});

Route::get("/all", [SupportReasonController::class, "getAll"]);
Route::get("/{reason}", [SupportReasonController::class, "getById"]);
