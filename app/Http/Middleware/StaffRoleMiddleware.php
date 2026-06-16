<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = Auth::guard('staff')->user() ?? Auth::guard('staff-api')->user();

        $allowed = explode(',', $roles);

        if (! $user || ! in_array($user->role, $allowed)) {
            abort(403, 'No autorizado. Se requiere rol: '.$roles);
        }

        return $next($request);
    }
}
