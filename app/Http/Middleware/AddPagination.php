<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddPagination {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!$request->query("number")) {
            $request->number = 5;
            return $next($request);
        }
        if (!is_numeric($request->query("number"))) {
            $request->number = $request->query("number") == "all" ? "9999999999" : 5;
            return $next($request);
        }
        $request->number = intval($request->query("number"));

        return $next($request);
    }
}
