<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use App\Traits\MiddlewareTraits;
use Closure;
use Illuminate\Http\Request;

class BranchStock {
    use HttpErrors, MiddlewareTraits;
    public function handle(Request $request, Closure $next) {
        $user = $request->user();
        if ($this->isUserSuperAdmin($user)) {
            return $next($request);
        }
        $stock = $request->route("stock");
        if (!$stock || gettype($stock) === "string") {
            return $this->NOT_FOUND(__("errors.stock_not_found"));
        }
        if ($user->branch_id !== $stock->branch_id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
