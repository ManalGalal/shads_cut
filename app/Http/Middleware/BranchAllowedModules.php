<?php

namespace App\Http\Middleware;

use App\Traits\HttpErrors;
use Closure;
use Illuminate\Http\Request;

class BranchAllowedModules {

    use HttpErrors;
    protected $allowed_modules = [
        "users",
        "categories",
        "roles",
        "brands",
        "permissions",
        "expense_categories"
    ];
    public function handle(Request $request, Closure $next) {

        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }
        $module = $request->route("module");
        if (!$module || gettype($module) === "string") {
            return $next($request);
        }
        if (!in_array($module->name, $this->allowed_modules)) {
            return $this->FORBIDDEN();
        }
        return $next($request);
    }
}
