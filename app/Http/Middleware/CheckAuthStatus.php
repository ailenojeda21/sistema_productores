<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthStatus
{
    public function handle(Request $request, Closure $next)
    {
        $loginRoutes = ['login', 'register', 'logout', 'password.', 'verification.'];

        if (Auth::check()) {
            Auth::user()->touch();
            return $next($request);
        }

        foreach ($loginRoutes as $route) {
            if ($request->is($route) || $request->routeIs($route)) {
                return $next($request);
            }
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Unauthorized', 'redirect' => route('login')], 401);
        }

        return redirect()->route('login');
    }
}
