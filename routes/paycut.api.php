<?php

use App\Http\Controllers\PaycutController;
use Illuminate\Support\Facades\Route;



Route::middleware(["auth:api-admin", "BranchPaycut"])->group(function () {
    Route::post("/create", [PaycutController::class, "create"])->withoutMiddleware("BranchPaycut");
    Route::patch("/update/{paycut}", [PaycutController::class, "update"]);
    Route::delete("/delete/{paycut}", [PaycutController::class, "delete"]);
    Route::get("/all", [PaycutController::class, "getAll"])->withoutMiddleware("BranchPaycut");
    Route::get("/{paycut}", [PaycutController::class, "getById"]);
});
