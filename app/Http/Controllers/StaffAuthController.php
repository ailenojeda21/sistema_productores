<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        return inertia('Staff/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $staffUser = \App\Models\StaffUser::where('email', strtolower($credentials['email']))->first();

        if (!$staffUser) {
            return back()->withErrors([
                'email' => 'Credenciales incorrectas',
            ]);
        }

        if (!$staffUser->active) {
            return back()->withErrors([
                'email' => 'Usuario inactivo. Contacte al administrador.',
            ]);
        }

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('staff.dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
