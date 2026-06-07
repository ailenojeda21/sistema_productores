<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
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
        $user = $request->user();

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

        $filters = [
            'name' => $name,
            'email' => $email,
        ];

        if ($this->isApiRequest($request)) {
            return response()->json([
                'filters' => $filters,
                'users' => $users,
            ]);
        }

        return Inertia::render('Staff/Users/Index', [
            'user' => $user,
            'filters' => $filters,
            'users' => $users,
        ]);
    }

    /**
     * Muestra el formulario para editar un usuario (solo admin)
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $staffUser = StaffUser::findOrFail($id);

        $userData = [
            'id' => $staffUser->id,
            'name' => $staffUser->name,
            'email' => $staffUser->email,
            'role' => $staffUser->role,
            'active' => $staffUser->active,
        ];

        if ($this->isApiRequest($request)) {
            return response()->json($userData);
        }

        return Inertia::render('Staff/Users/Edit', [
            'user' => $user,
            'staffUser' => $userData,
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
            && ! $request->filled('name')
            && ! $request->filled('email')
            && ! $request->filled('role')
        ) {
            $validated = $request->validate([
                'active' => ['required', 'boolean'],
            ]);

            $staffUser->update([
                'active' => (bool) $validated['active'],
            ]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'message' => 'Estado actualizado',
                    'user' => $staffUser->fresh(),
                ]);
            }

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
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staffUser->update($updateData);

        if ($this->isApiRequest($request)) {
            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'user' => $staffUser->fresh(),
            ]);
        }

        return redirect()
            ->route('staff.users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Elimina (soft delete) un usuario staff (solo admin)
     */
    public function destroy(Request $request, $id)
    {
        if ((int) $id === (int) $request->user()->id) {
            if ($this->isApiRequest($request)) {
                return response()->json(['message' => 'No puedes eliminarte a ti mismo.'], 403);
            }

            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $staffUser = StaffUser::findOrFail($id);
        $staffUser->delete();

        if ($this->isApiRequest($request)) {
            return response()->json(['message' => 'Usuario eliminado']);
        }

        return back()->with('success', 'Usuario eliminado');
    }

    /**
     * Muestra el formulario para crear un nuevo usuario (solo admin)
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $availableRoles = ['admin', 'auditor'];

        if ($this->isApiRequest($request)) {
            return response()->json([
                'available_roles' => $availableRoles,
                'fields' => ['name', 'email', 'password', 'password_confirmation', 'role'],
            ]);
        }

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,auditor'],
        ]);

        $staffUser = StaffUser::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($this->isApiRequest($request)) {
            return response()->json([
                'message' => 'Usuario staff creado exitosamente',
                'user' => [
                    'id' => $staffUser->id,
                    'name' => $staffUser->name,
                    'email' => $staffUser->email,
                    'role' => $staffUser->role,
                ],
            ], 201);
        }

        return redirect()
            ->route('staff.dashboard')
            ->with('success', 'Usuario staff creado exitosamente');
    }
}
