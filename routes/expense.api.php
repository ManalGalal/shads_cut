<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [ExpenseController::class, "create"]);
    Route::patch("/update/{expense}", [ExpenseController::class, "update"])->middleware("BranchExpense");
    Route::delete("/delete/{expense}", [ExpenseController::class, "delete"])->middleware("BranchExpense");
    Route::get("/all", [ExpenseController::class, "getAll"]);
    Route::get("/branch/{branch}", [ExpenseController::class, "getForBranch"])->middleware("SuperOrBranchAdmin");
    Route::get("{expense}", [ExpenseController::class, "getById"])->middleware("BranchExpense");
});
