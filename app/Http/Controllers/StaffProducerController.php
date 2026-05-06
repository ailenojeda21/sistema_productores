<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            ->when($dni !== '', function ($q) use ($dni) {
                $q->whereRaw(
                    'LOWER(users.dni) LIKE ?',
                    ['%' . strtolower($dni) . '%']
                );
            })

            ->when($name !== '', function ($q) use ($name) {
                $q->whereRaw(
                    'LOWER(users.name) LIKE ?',
                    ['%' . strtolower($name) . '%']
                );
            })

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

            ->when($variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', function ($sub) use ($variedad) {
                    $sub->whereRaw(
                        'LOWER(variedad) LIKE ?',
                        ['%' . strtolower($variedad) . '%']
                    );
                });
            })

            ->when($tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', function ($sub) use ($tipo) {
                    $sub->whereRaw(
                        'LOWER(tipo) LIKE ?',
                        ['%' . strtolower($tipo) . '%']
                    );
                });
            })

            ->when($rut !== '', function ($q) use ($rut) {
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

    /**
     * 🔥 MÉTODO QUE TE FALTABA
     */
    public function show($id)
    {
        $producer = User::with([
            'propiedades.cultivos',
            'propiedades.maquinaria',
            'comercializacion'
        ])->findOrFail($id);

        $user = Auth::guard('staff')->user();

        // Preparar propiedades con dirección completa
        $propiedades = $producer->propiedades->map(function ($prop) {
            return [
                'id' => $prop->id,
                'direccion' => $prop->direccion,
                'hectareas' => $prop->hectareas,
                'tipo_tenencia' => $prop->tipo_tenencia,
                'especificar_tenencia' => $prop->especificar_tenencia,
                'derecho_riego' => $prop->derecho_riego,
                'tipo_derecho_riego' => $prop->tipo_derecho_riego,
                'malla' => $prop->malla,
                'hectareas_malla' => $prop->hectareas_malla,
                'cierre_perimetral' => $prop->cierre_perimetral,
                'rut' => $prop->rut,
                'rut_valor' => $prop->rut_valor,
                'rut_archivo_url' => $prop->rut_archivo ? Storage::url($prop->rut_archivo) : null,
                'lat' => $prop->lat,
                'lng' => $prop->lng,
            ];
        });

        // Recolectar cultivos de todas las propiedades
        $cultivos = [];
        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                $cultivos[] = [
                    'id' => $cult->id,
                    'nombre' => $cult->nombre,
                    'tipo' => $cult->tipo,
                    'hectareas' => $cult->hectareas,
                    'manejo_cultivo' => $cult->manejo_cultivo,
                    'tecnologia_riego' => $cult->tecnologia_riego,
                    'propiedad' => [
                        'direccion' => $prop->direccion,
                    ],
                ];
            }
        }

        // Recolectar maquinarias de todas las propiedades
   $maquinarias = [];

foreach ($producer->propiedades as $prop) {
    if ($prop->maquinaria) {
        $maq = $prop->maquinaria;

        $maquinarias[] = [
            'id' => $maq->id,
            'tractor' => $maq->tractor,
            'modelo_tractor' => $maq->modelo_tractor,
            'implementos' => $maq->implementos,
            'implementos_flags' => $maq->implementos_flags,
            'propiedad' => [
                'direccion' => $prop->direccion_completa,
            ],
        ];
    }
}

        // Datos de comercialización
        $comercio = $producer->comercializacion ? [
            'infraestructura_empaque' => $producer->comercializacion->infraestructura_empaque,
            'vende_en_finca' => $producer->comercializacion->vende_en_finca,
            'mercados' => $producer->comercializacion->mercados,
            'cooperativas' => $producer->comercializacion->cooperativas,
        ] : null;

        // Calcular stats
        $stats = [
            'propiedades' => $propiedades->count(),
            'cultivos' => count($cultivos),
            'maquinarias' => count($maquinarias),
            'hectareas' => $propiedades->sum('hectareas'),
        ];

        return inertia('Staff/Producers/Show', [
            'authUser' => $user,
            'producer' => [
                'id' => $producer->id,
                'name' => $producer->name,
                'dni' => $producer->dni,
                'email' => $producer->email,
                'telefono' => $producer->telefono,
                'cooperativas' => $producer->cooperativas,
            ],
            'propiedades' => $propiedades,
            'cultivos' => $cultivos,
            'maquinarias' => $maquinarias,
            'comercio' => $comercio,
            'stats' => $stats,
        ]);
    }
}