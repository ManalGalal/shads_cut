<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class HiddenApi {
    use HttpErrors;

    private $secret_keys = ["ABCDD"];
    public function handle(Request $request, Closure $next) {
        $secret_key = $request->query("secret_key");
        if (!in_array($secret_key, $this->secret_keys)) {
            return $this->UNAUTHORIZED("UNAUTHROIZED ACCESS");
        }
        return $next($request);
    }
}
