<?php

namespace App\Http\Middleware;

use App\Models\BranchService as ModelsBranchService;
use App\Traits\HttpErrors;
use App\Traits\MiddlewareTraits;
use Closure;
use Illuminate\Http\Request;

class BranchService {
    use HttpErrors, MiddlewareTraits;
    //use when the branch admin must have the same branch as the service in the request
    public function handle(Request $request, Closure $next) {
        $user = $request->user();
        if ($this->isUserSuperAdmin($request->user())) {
            return $next($request);
        }
        $service = $request->route("service");
        if (!$service) {
            return $this->UNAUTHORIZED();
        }
        $branch_service_found = ModelsBranchService::where("service_id", $service->id)
            ->where("branch_id", $user->branch_id)
            ->exists();
        if (!$branch_service_found) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
