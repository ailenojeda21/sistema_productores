<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dni' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'dni')
                    ->ignore($user->id)
                    ->whereNull('deleted_at')
                    ->where('dni', '!=', ''),
            ],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'cooperativas' => ['nullable', 'array'],
            'cooperativas.*' => [
                'string',
                Rule::in(array_merge(array_keys(User::COOPERATIVAS), array_values(User::COOPERATIVAS))),
            ],
        ], [
            'dni.unique' => 'El D.N.I. ya corresponde a otro productor registrado.',
        ]);

        if (! $request->has('tiene_cooperativas')) {
            $validated['cooperativas'] = null;
        }

        $this->authorize('update', $user);

        $validated['email'] = $user->email;

        try {
            $user->update($validated);
        } catch (UniqueConstraintViolationException) {
            // Carrera: otro productor guardo el mismo DNI entre la validacion
            // y el update; el indice unico de la BD lo impide.
            throw ValidationException::withMessages([
                'dni' => ['El D.N.I. ya corresponde a otro productor registrado.'],
            ]);
        }

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

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('staff_password_reset_tokens')->where('email', $user->email)->delete();

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

        $user->load([
            'propiedades.cultivos.propiedad',
            'propiedades.maquinaria.propiedad',
            'comercializacion',
        ]);

        $propiedades = $user->propiedades;
        $cultivos = $propiedades->flatMap->cultivos;
        $maquinarias = $propiedades->map->maquinaria->filter()->values();
        $comercio = $user->comercializacion;

        $stats = [
            'propiedades' => $propiedades->count(),
            'cultivos' => $cultivos->count(),
            'maquinarias' => $maquinarias->count(),
            'comercializacion' => $comercio ? 1 : 0,
        ];

        $verificacionUrl = CertificateService::verificationUrl(CertificateService::TIPO_COMPROBANTE, $user->id);

        return view('profile.show', compact(
            'user',
            'propiedades',
            'cultivos',
            'maquinarias',
            'comercio',
            'stats',
            'verificacionUrl',
        ) + ['verificacionQr' => CertificateService::qrDataUri($verificacionUrl)]);
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
