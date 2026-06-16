<?php

namespace App\Providers;

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use App\Policies\ComercioPolicy;
use App\Policies\CultivoPolicy;
use App\Policies\MaquinariaPolicy;
use App\Policies\PropiedadPolicy;
use App\Policies\StaffUserPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Propiedad::class => PropiedadPolicy::class,
        Cultivo::class => CultivoPolicy::class,
        Maquinaria::class => MaquinariaPolicy::class,
        Comercio::class => ComercioPolicy::class,
        StaffUser::class => StaffUserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-staff', function ($user) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }

            return $user instanceof \App\Models\StaffUser && $user->role === 'admin';
        });

        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }
        });
    }
}
