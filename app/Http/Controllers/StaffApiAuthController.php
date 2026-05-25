<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StaffApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string|max:255',
        ]);

        $staff = StaffUser::where('email', strtolower($request->email))->first();

        if (! $staff || ! Hash::check($request->password, $staff->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        if (! $staff->active) {
            throw ValidationException::withMessages([
                'email' => ['Usuario inactivo. Contacte al administrador.'],
            ]);
        }

        $staff->update(['last_login_at' => now()]);

        $device = $request->device_name ?? 'staff-api';
        $token = $staff->createToken($device)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'role' => $staff->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request)
    {
        $staff = $request->user();

        return response()->json([
            'id' => $staff->id,
            'name' => $staff->name,
            'email' => $staff->email,
            'role' => $staff->role,
        ]);
    }
}
