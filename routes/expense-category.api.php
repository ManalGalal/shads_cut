<?php

use App\Http\Controllers\ExpenseCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api-admin")->group(function () {
    Route::post("/create", [ExpenseCategoryController::class, "create"]);
    Route::patch("/update/{expenseCategory}", [ExpenseCategoryController::class, "update"]);
    Route::delete("/delete", [ExpenseCategoryController::class, "delete"]);
    Route::get("/all", [ExpenseCategoryController::class, "getAll"]);
    Route::get("{expenseCategory}", [ExpenseCategoryController::class, "getById"]);
});
