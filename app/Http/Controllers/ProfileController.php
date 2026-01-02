<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Mostrar formulario de edición del perfil general.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar información general del perfil.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'dni' => ['nullable', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'tipo_tenencia' => ['nullable', 'string', 'in:propietario,arrendatario,otros'],
            'especificar_tenencia' => ['nullable', 'required_if:tipo_tenencia,otros', 'string', 'max:255'],
        ], [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
        ]);

        $user = auth()->user();
        
        // Si no selecciona "otros", limpiar el campo especificar_tenencia
        if ($validated['tipo_tenencia'] !== 'otros') {
            $validated['especificar_tenencia'] = null;
        }
        
        $user->update($validated);

        return Redirect::route('profile')->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Mostrar formulario para cambiar avatar.
     */
    public function editAvatar()
    {
        $user = Auth::user();
        return view('profile.avatar', compact('user'));
    }

    /**
     * Actualizar avatar (solo seleccionar entre 5 predefinidos).
     */
    public function updateAvatar(Request $request)
    {
        // Lista válida de avatares
        $validAvatars = [
            'uno.png',
            'dos.png',
            'tres.png',
            'cuatro.png',
            'cinco.png',
        ];

        // Validación
        $request->validate([
            'avatar' => 'required|in:' . implode(',', $validAvatars),
        ]);

        // Guardar avatar
        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return Redirect::route('profile.update')
            ->with('status', 'Avatar actualizado correctamente.');
    }

    /**
     * Eliminar cuenta.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Ver perfil público.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    
}
