<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class ValidateType {
    use HttpErrors;
    public function handle(Request $request, Closure $next) {
        $types = ["user", "admin", "worker"];
        $type = $request->route("type");
        if (!in_array($type, $types)) {
            return $this->BAD_REQUEST();
        }
        return $next($request);
    }
}
