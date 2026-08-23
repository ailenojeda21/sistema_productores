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
     * incluso si aún tienen tokens Sanctum válidos o sesión web activa.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('staff')->user() ?? Auth::guard('staff-api')->user();

        if ($user instanceof StaffUser && ! $user->active) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(403, 'No autorizado.');
            }

            Auth::guard('staff')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('staff.login')
                ->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        return $next($request);
    }
}
