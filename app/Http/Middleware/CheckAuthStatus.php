<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() && !$request->is('login*', 'register*')) {
            // Si no está autenticado y no está en las rutas de login/register
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized', 'redirect' => route('login')], 401);
            }
            return redirect()->route('login');
        }

        // Si está autenticado, actualizar timestamp de última actividad
        if (Auth::check()) {
            Auth::user()->touch();
        }

        return $next($request);
    }
}
