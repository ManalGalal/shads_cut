<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get("/branches/for/region/{region}", [HomeController::class, "getBranchesForRegion"]);


Route::middleware("BranchIsHome")->group(function () {
    Route::get("/{branch}/categories", [HomeController::class, "getCategories"]);
    Route::get("/{branch}/workers/for/services", [HomeController::class, "getWorkersForServices"]);
});
