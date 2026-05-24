<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Middleware para verificar roles Spatie en el modelo User (productores).
     * NO debe usarse en rutas con auth:staff porque StaffUser
     * no implementa Spatie\Permission\Traits\HasRoles.
     *
     * @param  string|string[]  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'No autenticado.');
        }

        if (! method_exists($user, 'hasAnyRole')) {
            abort(403, 'El tipo de usuario no soporta verificación de roles Spatie.');
        }

        if (! $user->hasAnyRole($roles)) {
            abort(403, 'No tienes permiso para acceder a esta ruta.');
        }

        return $next($request);
    }
}
