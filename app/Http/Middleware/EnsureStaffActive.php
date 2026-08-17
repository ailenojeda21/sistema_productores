<?php

namespace App\Http\Middleware;

use App\Models\StaffUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStaffActive
{
    /**
     * Bloquea a usuarios staff inactivos o soft-deleted,
     * incluso si aún tienen tokens Sanctum válidos.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('staff')->user() ?? Auth::guard('staff-api')->user();

        if ($user instanceof StaffUser && ! $user->active) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
