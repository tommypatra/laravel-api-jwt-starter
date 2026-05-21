<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | General API
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('jwt-api', function (Request $request) {
            return Limit::perMinute(100)->by(
                Auth::id() ?: $request->ip()
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Sync API
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('sync-api', function (Request $request) {
            return Limit::perMinute(2)->by(
                Auth::id() ?: $request->ip()
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Login API
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('auth-web', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->ip()
            );
        });
    }
}
