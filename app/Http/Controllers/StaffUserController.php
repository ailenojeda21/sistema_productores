<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StaffUserController extends Controller
{
    /**
     * Listado de usuarios staff (solo admin)
     */
    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();

        $name = trim((string) $request->get('name', ''));
        $email = trim((string) $request->get('email', ''));

        $users = StaffUser::query()
            ->when($name !== '', fn ($q) => $q->where('name', 'like', "%{$name}%"))
            ->when($email !== '', fn ($q) => $q->where('email', 'like', "%{$email}%"))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $users->getCollection()->transform(fn ($staffUser) => [
            'id' => $staffUser->id,
            'name' => $staffUser->name,
            'email' => $staffUser->email,
            'role' => $staffUser->role,
            'active' => $staffUser->active,
            'last_login_at' => $staffUser->last_login_at ?? null,
            'last_access_at' => $staffUser->last_access_at ?? null,
        ]);

        return Inertia::render('Staff/Users/Index', [
            'user' => $user,
            'filters' => [
                'name' => $name,
                'email' => $email,
            ],
            'users' => $users,
        ]);
    }

    /**
     * Muestra el formulario para editar un usuario (solo admin)
     */
    public function edit($id)
    {
        $user = Auth::guard('staff')->user();
        $staffUser = StaffUser::findOrFail($id);

        return Inertia::render('Staff/Users/Edit', [
            'user' => $user,
            'staffUser' => [
                'id' => $staffUser->id,
                'name' => $staffUser->name,
                'email' => $staffUser->email,
                'role' => $staffUser->role,
                'active' => $staffUser->active,
            ],
            'pageTitle' => 'Editar usuario',
            'pageSubtitle' => 'Actualizacion de datos del usuario',
        ]);
    }

    /**
     * Actualiza un usuario staff (solo admin)
     */
    public function update(Request $request, $id)
    {
        $staffUser = StaffUser::findOrFail($id);

        if (
            $request->has('active')
            && !$request->filled('name')
            && !$request->filled('email')
            && !$request->filled('role')
        ) {
            $validated = $request->validate([
                'active' => ['required', 'boolean'],
            ]);

            $staffUser->update([
                'active' => (bool) $validated['active'],
            ]);

            return back()->with('success', 'Estado actualizado');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('staff_users', 'email')->ignore($staffUser->id),
            ],
            'role' => ['required', 'in:admin,auditor'],
        ]);

        $staffUser->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('staff.users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

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
