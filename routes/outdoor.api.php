<?php

use App\Http\Controllers\OutdoorController;
use Illuminate\Support\Facades\Route;



Route::get("/branches/for/region/{region}", [OutdoorController::class, "getBranchesForRegion"]);


Route::middleware("BranchIsVan")->group(function(){
    Route::get("/{branch}/categories", [OutdoorController::class, "getCategories"]);
    Route::get("/{branch}/workers/for/services", [OutdoorController::class, "getWorkersForServices"]);
});