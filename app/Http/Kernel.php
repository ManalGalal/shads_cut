<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\forceJson::class,
        \App\Http\Middleware\SetLang::class,
        \App\Http\Middleware\AddPagination::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        "HiddenApi" => \App\Http\Middleware\HiddenApi::class,
        "ValidateType" => \App\Http\Middleware\ValidateType::class,
        "SuperAdmin" => \App\Http\Middleware\SuperAdmin::class,
        "BranchAdmin" => \App\Http\Middleware\BranchAdmin::class,
        "BranchAdminWorker" => \App\Http\Middleware\BranchAdminWorker::class,
        "BranchService" => \App\Http\Middleware\BranchService::class,
        "BranchPaycut" => \App\Http\Middleware\BranchPaycut::class,
        "BranchAdditive" => \App\Http\Middleware\BranchAdditive::class,
        "BranchIsVan" => \App\Http\Middleware\BranchIsVan::class,
        "BranchIsHome" => \App\Http\Middleware\BranchIsHome::class,
        "SuperOrBranchAdmin" => \App\Http\Middleware\SuperOrBranchAdmin::class,
        "IndoorBranch" => \App\Http\Middleware\IndoorBranch::class,
        "CustomerOrder" => \App\Http\Middleware\CustomerOrder::class,
        "BranchOrder" => \App\Http\Middleware\BranchOrder::class,
        "BranchStock" => \App\Http\Middleware\BranchStock::class,
        "BranchExpense" => \App\Http\Middleware\BranchExpense::class,
        "HMACAuth" => \App\Http\Middleware\HMACAuth::class,
        "BranchAllowedModules" => \App\Http\Middleware\BranchAllowedModules::class,
    ];
}
