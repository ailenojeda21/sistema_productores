<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffProducerController extends Controller
{
    public function index(Request $request)
    {
        $dni  = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));

        $producers = User::query()
            ->when($dni !== '', fn ($q) => $q->where('dni', 'like', "%{$dni}%"))
            ->when($name !== '', fn ($q) => $q->where('name', 'like', "%{$name}%"))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // devolvemos sólo lo mínimo al frontend
        $producers->getCollection()->transform(fn ($u) => [
            'id'    => $u->id,
            'name'  => $u->name,
            'dni'   => $u->dni,
            'email' => $u->email,
        ]);

        $user = Auth::guard('staff')->user();

        return inertia('Staff/Producers/Index', [
            'user'    => $user,
            'filters' => [
                'dni'  => $dni,
                'name' => $name,
            ],
            'producers' => $producers,
        ]);
    }

    public function show($id)
    {
        $user = Auth::guard('staff')->user();

        // Cargar el productor con todas sus relaciones
        // IMPORTANTE: maquinaria es hasOne en Propiedad => propiedades.maquinaria (singular)
        $producer = User::with([
            'propiedades.cultivos',
            'propiedades.maquinaria',
            'comercializacion',
        ])->findOrFail($id);

        // Preparar datos de propiedades
        $propiedades = $producer->propiedades->map(fn ($p) => [
            'id'                   => $p->id,
            'direccion'            => $p->direccion,
            'hectareas'            => $p->hectareas,
            'tipo_tenencia'        => $p->tipo_tenencia,
            'especificar_tenencia' => $p->especificar_tenencia,
            'derecho_riego'        => $p->derecho_riego,
            'tipo_derecho_riego'   => $p->tipo_derecho_riego,
            'malla'                => $p->malla,
            'hectareas_malla'      => $p->hectareas_malla,
            'cierre_perimetral'    => $p->cierre_perimetral,
            'rut'                  => $p->rut,
            'rut_valor'            => $p->rut_valor,
            'rut_archivo_url'      => $p->rut_archivo ? asset('storage/' . $p->rut_archivo) : null,
            'lat'                  => $p->lat,
            'lng'                  => $p->lng,
        ])->values()->all();

        // Preparar datos de cultivos (de todas las propiedades)
        $cultivos = collect();
        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                $cultivos->push([
                    'id'              => $cult->id,
                    'nombre'          => $cult->nombre,
                    'tipo'            => $cult->tipo,
                    'hectareas'       => $cult->hectareas,
                    'manejo_cultivo'  => $cult->manejo_cultivo,
                    'tecnologia_riego'=> $cult->tecnologia_riego,
                    'propiedad'       => [
                        'id'        => $prop->id,
                        'direccion' => $prop->direccion,
                    ],
                ]);
            }
        }

        // Preparar datos de maquinarias (hasOne por propiedad)
        // Tu modelo guarda tractor/modelo_tractor e implementos booleanos, NO nombre/tipo/estado.
        $implements = [
            'arado','rastra','niveleta_comun','niveleta_laser','cincel_cultivadora',
            'desmalezadora','pulverizadora_tractor','mochila_pulverizadora',
            'cosechadora','enfardadora','retroexcavadora','carro_carreton',
        ];

        $maquinarias = collect();
        foreach ($producer->propiedades as $prop) {
            $maq = $prop->maquinaria; // ✅ hasOne

            if ($maq) {
                // Crear objeto de flags para el PDF
                $implementos_flags = [];
                foreach ($implements as $impl) {
                    $implementos_flags[$impl] = (bool) $maq->$impl;
                }
                
                $maquinarias->push([
                    'id' => $maq->id,
                    'propiedad' => [
                        'id'        => $prop->id,
                        'direccion' => $prop->direccion,
                    ],
                    'tractor'       => (bool) $maq->tractor,
                    'modelo_tractor'=> $maq->modelo_tractor,
                    'implementos'   => collect($implements)
                        ->filter(fn ($f) => (int) $maq->$f === 1)
                        ->values()
                        ->all(),
                    'implementos_flags' => $implementos_flags,
                ]);
            }
        }

        // Preparar datos de comercio
        $comercioData = $producer->comercializacion->first();
        $comercio = $comercioData ? [
            'infraestructura_empaque' => $comercioData->infraestructura_empaque,
            'vende_en_finca'          => $comercioData->vende_en_finca,
            'mercados'                => $comercioData->mercados ?? [],
            'cooperativas'            => $comercioData->cooperativas ?? [],
        ] : null;

        // Calcular estadísticas
        $stats = [
            'propiedades' => $producer->propiedades->count(),
            'cultivos'    => $cultivos->count(),
            'maquinarias' => $maquinarias->count(),
            'hectareas'   => $producer->propiedades->sum('hectareas'),
        ];

        return inertia('Staff/Producers/Show', [
            'authUser' => $user,
            'producer' => [
                'id'          => $producer->id,
                'name'        => $producer->name,
                'dni'         => $producer->dni,
                'email'       => $producer->email,
                'telefono'    => $producer->telefono,
                'cooperativas'=> $producer->cooperativas ?? [],
            ],
            'propiedades' => $propiedades,
            'cultivos'    => $cultivos->values()->all(),
            'maquinarias' => $maquinarias->values()->all(),
            'comercio'    => $comercio,
            'stats'       => $stats,
        ]);
    }
}
