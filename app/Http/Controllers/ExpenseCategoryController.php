<?php

namespace App\Http\Controllers;

use App\Http\Requests\createExpenseCategory;
use App\Http\Requests\updateExpenseCategory;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller {
    public function create(createExpenseCategory $request) {
        $validated = $request->validated();
        $expenseCategory = ExpenseCategory::create($validated);
        return response(["message" => __("messages.expense_category_created"), "expense_category" => $expenseCategory], 201);
    }
    public function update(updateExpenseCategory $request, ExpenseCategory $expenseCategory) {
        $validated = $request->validated();
        $expenseCategory->update($validated);
        return response(["message" => __("messages.expense_category_updated"), "expense_category" => $expenseCategory]);
    }
    public function delete(ExpenseCategory $expenseCategory) {
        $expenseCategory->delete();
        return response(["expense_category" => $expenseCategory]);
    }
    public function getAll(Request $request) {
        $expense_categories = ExpenseCategory::orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["expense_categories" => $expense_categories]);
    }
    public function getById(ExpenseCategory $expenseCategory) {
        return response(["expense_category" => $expenseCategory]);
    }
}
