<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use App\Traits\MiddlewareTraits;
use Closure;
use Illuminate\Http\Request;


/**
 * @api this middleware can work both on worker and admin api because both have branch_id 
 */
class BranchOrder {
    use HttpErrors, MiddlewareTraits;
    public function handle(Request $request, Closure $next) {
        $user = $request->user();
        if ($this->isUserSuperAdmin($user)) {
            return $next($request);
        }
        $order = $request->route("order");
        if (!$order || gettype($order) === "string") {
            return $this->NOT_FOUND(__("errors.order_not_found"));
        }
        if ($user->branch_id !== $order->branch_id) {
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
