<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class SuperAdmin
{  
    use HttpErrors;
    // use after auth:api-admin middleware
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user->role !== "super"){
            return $this->UNAUTHORIZED();
        }
        return $next($request);
    }
}
