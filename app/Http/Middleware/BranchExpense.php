<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use App\Traits\MiddlewareTraits;
use Closure;
use Illuminate\Http\Request;

class BranchExpense {
    use HttpErrors, MiddlewareTraits;

    public function handle(Request $request, Closure $next) {
        $user = $request->user();
        if ($this->isUserSuperAdmin($user)) {
            return $next($request);
        }
        $expense = $request->route("expense");
        if (!$expense || gettype($expense) === "string") {
            return $this->NOT_FOUND(__("errors.expense_not_found"));
        }
        if ($user->branch_id !== $expense->branch_id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
