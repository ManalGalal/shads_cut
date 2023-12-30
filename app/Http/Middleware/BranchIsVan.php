<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class BranchIsVan {

    use HttpErrors;
    public function handle(Request $request, Closure $next) {
        $branch = $request->route("branch");
        if (!$branch || gettype($branch) === "string") {
            return $this->NOT_FOUND(__("errors.branch_not_found"));
        }
        if (!$branch->is_van) {
            return $this->BAD_REQUEST(__("errors.branch_is_not_van"));
        }
        return $next($request);
    }
}
