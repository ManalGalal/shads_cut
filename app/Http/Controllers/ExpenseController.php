<?php

namespace App\Http\Controllers;

use App\Http\Requests\createExpense;
use App\Http\Requests\updateExpense;
use App\Models\Branch;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller {
    public function create(createExpense $request) {
        $validated = $request->validated();
        if ($request->user()->role === "normal") {
            $validated["branch_id"] = $request->user()->branch_id;
        }
        $expense = Expense::create($validated);
        return response(["message" => __("messages.expense_created"), "expense" => $expense], 201);
    }
    public function update(updateExpense $request, Expense $expense) {
        $validated = $request->validated();
        $expense->update($validated);
        return response(["message" => __("messages.expense_updated"), "expense" => $expense]);
    }
    public function delete(Expense $expense) {
        $expense->delete();
        return response(["expense" => $expense]);
    }
    public function getAll(Request $request) {
        $expenses = Expense::orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["expenses" => $expenses]);
    }
    public function getById(Expense $expense) {
        return response(["expense" => $expense]);
    }
    public function getForBranch(Request $request, Branch $branch) {
        $expenses = Expense::where("branch_id", $branch->id)
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();
        return response(["expenses" => $expenses]);
    }
}
