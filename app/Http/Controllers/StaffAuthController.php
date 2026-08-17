<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
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

        $credentials['email'] = strtolower($credentials['email']);

        $staffUser = StaffUser::where('email', $credentials['email'])->first();

        if (! $staffUser) {
            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        if (! $staffUser->active) {
            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('staff.dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('staff')->user();

        if ($user) {
            $user->tokens()->delete();
        }

        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
