<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::guard('staff')->user();

        if (!$user || $user->role !== $role) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
