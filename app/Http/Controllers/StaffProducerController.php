<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffProducerController extends Controller
{
    public function index(Request $request)
    {
        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::query()
            ->select('users.id', 'users.name', 'users.dni', 'users.email')
            ->distinct()

            // Buscar por DNI
            ->when($dni !== '', function ($q) use ($dni) {
                $q->whereRaw(
                    'LOWER(users.dni) LIKE ?',
                    ['%' . strtolower($dni) . '%']
                );
            })

            // Buscar por Nombre
            ->when($name !== '', function ($q) use ($name) {
                $q->whereRaw(
                    'LOWER(users.name) LIKE ?',
                    ['%' . strtolower($name) . '%']
                );
            })

            // Buscar por Distrito
            ->when($distrito !== '', function ($q) use ($distrito) {
                $normalized = strtolower(str_replace(' ', '-', trim($distrito)));
                $search = str_replace('-', '', $normalized);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->whereRaw(
                        "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
                        ["%{$search}%"]
                    );
                });
            })

            // Buscar por Variedad
            ->when($variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', function ($sub) use ($variedad) {
                    $sub->whereRaw(
                        'LOWER(variedad) LIKE ?',
                        ['%' . strtolower($variedad) . '%']
                    );
                });
            })

            // Buscar por Tipo
            ->when($tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', function ($sub) use ($tipo) {
                    $sub->whereRaw(
                        'LOWER(tipo) LIKE ?',
                        ['%' . strtolower($tipo) . '%']
                    );
                });
            })

            // Buscar por RUT
            ->when($rut !== '', function ($q) use ($rut) {
                // dejamos solo números
                $search = preg_replace('/\D/', '', $rut);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->where('rut', 1)
                        ->whereRaw(
                            'CAST(rut_valor AS CHAR) LIKE ?',
                            ["%{$search}%"]
                        );
                });
            })

            ->orderBy('users.id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $producers->getCollection()->transform(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'dni' => $u->dni,
            'email' => $u->email,
        ]);

        $user = Auth::guard('staff')->user();

        return inertia('Staff/Producers/Index', [
            'user' => $user,
            'filters' => [
                'dni' => $dni,
                'name' => $name,
                'distrito' => $distrito,
                'variedad' => $variedad,
                'tipo' => $tipo,
                'rut' => $rut,
            ],
            'producers' => $producers,
        ]);
    }
}