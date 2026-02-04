<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class StaffUserController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo usuario (solo admin)
     */
    public function create()
    {
        $user = Auth::guard('staff')->user();

        // ✅ Respeta la convención de rutas Inertia/Vue (case-sensitive en deploy)
        return Inertia::render('Staff/Users/Create', [
            'user' => $user,
            'pageTitle' => 'Agregar usuario',
            'pageSubtitle' => 'Alta de usuario staff',
        ]);
    }

    /**
     * Almacena un nuevo usuario staff (solo admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            // ✅ Validar contra staff_users (NO users)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff_users,email'],

            // ✅ Requiere password_confirmation en el form
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'role' => ['required', 'in:admin,auditor'],
        ]);

        StaffUser::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],

            // ✅ Solo si tu tabla tiene "active" (si no existe, borrá esta línea)
            // 'active' => true,
        ]);

        return redirect()
            ->route('staff.dashboard')
            ->with('success', 'Usuario staff creado exitosamente');
    }
}
