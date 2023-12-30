<?php

use App\Http\Controllers\IndoorController;
use Illuminate\Support\Facades\Route;

Route::middleware("IndoorBranch")->group(function () {

    Route::get("/branches", [IndoorController::class, "getBranches"])->withoutMiddleware("IndoorBranch");
    Route::get("/{branch}/categories", [IndoorController::class, "getCategories"]);
    Route::get("/{branch}/workers/for/services", [IndoorController::class, "getWorkersForServices"]);
});
