<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Event::listen(
            Login::class,
            \App\Listeners\LogAuthenticationAttempt::class,
        );
        Event::listen(
            Failed::class,
            \App\Listeners\LogAuthenticationAttempt::class,
        );
        Event::listen(
            Logout::class,
            \App\Listeners\LogAuthenticationAttempt::class,
        );

        Vite::prefetch(concurrency: 3);

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols();
        });

        // Fuerza el esquema HTTPS si estamos en producción (Railway)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login-producer', function (Request $request) {
            $key = ($request->input('email') ?? '') . '|' . $request->ip();
            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('login-staff', function (Request $request) {
            $key = ($request->input('email') ?? '') . '|' . $request->ip();
            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('login-staff-api', function (Request $request) {
            $key = ($request->input('email') ?? '') . '|' . $request->ip();
            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('register', function (Request $request) {
            $key = ($request->input('email') ?? '') . '|' . $request->ip();
            return Limit::perMinutes(60, 3)->by($key);
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            $key = ($request->input('email') ?? '') . '|' . $request->ip();
            return Limit::perMinutes(60, 3)->by($key);
        });
    }
}
