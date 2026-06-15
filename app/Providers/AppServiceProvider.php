<?php

namespace App\Providers;

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\User;
use App\Policies\ComercioPolicy;
use App\Policies\CultivoPolicy;
use App\Policies\MaquinariaPolicy;
use App\Policies\PropiedadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Propiedad::class, PropiedadPolicy::class);
        Gate::policy(Cultivo::class, CultivoPolicy::class);
        Gate::policy(Maquinaria::class, MaquinariaPolicy::class);
        Gate::policy(Comercio::class, ComercioPolicy::class);

        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }
        });

        Vite::prefetch(concurrency: 3);

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
