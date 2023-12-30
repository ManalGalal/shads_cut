<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Throwable;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);

        ini_set('memory_limit', '500M');
        Carbon::macro("checkValid", function ($date) {
            try {
                if (is_null($date)) {
                    return false;
                }
                Carbon::parse($date);
                return true;
            } catch (Throwable $th) {
                return false;
            }
        });
    }
}
