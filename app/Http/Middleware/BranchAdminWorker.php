<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class BranchAdminWorker {
    use HttpErrors;
    //use when the branch admin must have the same branch as the worker in the request
    public function handle(Request $request, Closure $next) {
        // skip for super-admins 

        $user = $request->user();
        if ($user->role === "super") {
            return $next($request);
        }
        $worker = $request->route("worker");
        if (!$worker || $user->branch_id !== $worker->branch_id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
