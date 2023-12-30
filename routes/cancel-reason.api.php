<?php

use App\Http\Controllers\CancellationReasonController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [CancellationReasonController::class, "create"]);
    Route::patch("/update/{cancellationReason}", [CancellationReasonController::class, "update"]);
    Route::delete("/delete/{cancellationReasion", [CancellationReasonController::class, "delete"]);
});

Route::get("/all", [CancellationReasonController::class, "getAll"]);
Route::get("/{cancellationReason}", [CancellationReasonController::class, "getById"]);