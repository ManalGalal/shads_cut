<?php

use App\Http\Controllers\AssignServiceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchRegionController;
use Illuminate\Support\Facades\Route;


Route::middleware(["auth:api-admin", "SuperOrBranchAdmin"])->group(function () {
    Route::post("/create", [BranchController::class, "create"])->withoutMiddleware("SuperOrBranchAdmin");
    Route::patch("/update/{branch}", [BranchController::class, "update"]);
    Route::delete("/delete/{branch}", [BranchController::class, "update"]);
    Route::post("/assign/{service}/to/{branch}", [AssignServiceController::class, "assignToBranch"]);
    Route::delete("/remove/{service}/from/{branch}", [AssignServiceController::class, "removeFromBranch"]);
    Route::post("/add/{branch}/to/region/{region}", [BranchRegionController::class, "add"]);
    Route::delete("/remove/{branch}/from/region/{region}", [BranchRegionController::class, "remove"]);
});


Route::get("/all", [BranchController::class, "getAll"]);
Route::get("/{branch}", [BranchController::class, "getById"]);
