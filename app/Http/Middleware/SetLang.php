<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLang {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $lang = $request->header("Accept-language");
        if ($lang && in_array($lang, ["ar", "en"])) {
            App::setLocale($lang);
        }
        return $next($request);
    }
}
