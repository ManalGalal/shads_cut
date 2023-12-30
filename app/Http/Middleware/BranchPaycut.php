<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class BranchPaycut {
    use HttpErrors;
    //use when the branch admin must have the same branch as the paycut in the request
    public function handle(Request $request, Closure $next) {
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }
        $paycut = $request->route("paycut");
        if (!$paycut || $paycut->branch_id !== $request->user()->branch_id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
