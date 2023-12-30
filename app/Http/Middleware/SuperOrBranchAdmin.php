<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class SuperOrBranchAdmin {

    use HttpErrors;
    /**  
     * this middleware is meant if the admin is a super admin or branch admin meaning he have the same branch_id as
     *  the branch in the route;
     */
    public function handle(Request $request, Closure $next) {
        $branch = $request->route("branch");

        if (!$branch || gettype($branch) === "string") {
            return $this->NOT_FOUND(__("errors.branch_not_found"));
        }
        if ($request->user()->role === "super") {
            return $next($request);
        }
        if ($request->user()->branch_id !== $branch->id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
