<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class StaffPasswordResetLinkController extends Controller
{
    public function create()
    {
        return inertia('Staff/ForgotPassword');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker('staff_users')->sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Hemos enviado por correo electrónico el enlace para restablecer tu contraseña.');
        }

        throw ValidationException::withMessages([
            'email' => ['No podemos encontrar un usuario con esa dirección de correo electrónico.'],
        ]);
    }
}
