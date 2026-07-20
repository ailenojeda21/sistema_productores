<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();

        $this->authorize('update', $user);

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'cooperativas' => ['nullable', 'array'],
            'cooperativas.*' => ['string'],
        ]);

        if (! $request->has('tiene_cooperativas')) {
            $validated['cooperativas'] = null;
        }

        $user = auth()->user();

        $this->authorize('update', $user);

        $validated['email'] = $user->email;
        $user->update($validated);

        return Redirect::route('profile')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    public function editAvatar()
    {
        $user = Auth::user();

        $this->authorize('update', $user);

        return view('profile.avatar', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $validAvatars = [
            'uno.png',
            'dos.png',
            'tres.png',
            'cuatro.png',
            'cinco.png',
        ];

        $request->validate([
            'avatar' => 'required|in:'.implode(',', $validAvatars),
        ]);

        $user = Auth::user();

        $this->authorize('update', $user);

        $user->avatar = $request->avatar;
        $user->save();

        return Redirect::route('profile')
            ->with('success', 'Avatar actualizado correctamente.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->authorize('delete', $user);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show()
    {
        $user = Auth::user();

        $this->authorize('view', $user);

        return view('profile.show', compact('user'));
    }

    public function export(): JsonResponse
    {
        $user = Auth::user();

        $this->authorize('view', $user);

        $user->load([
            'propiedades',
            'propiedades.cultivos',
            'propiedades.maquinaria',
            'comercializacion',
        ]);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'cooperativas' => $user->cooperativas,
                'tiene_cooperativas' => $user->tiene_cooperativas,
                'tipo_productor' => $user->tipo_productor,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'propiedades' => $user->propiedades->toArray(),
            'cultivos' => $user->propiedades->flatMap->cultivos->toArray(),
            'maquinarias' => $user->propiedades->flatMap->maquinaria->toArray(),
            'comercios' => $user->comercializacion ? [$user->comercializacion->toArray()] : [],
        ]);
    }
}
