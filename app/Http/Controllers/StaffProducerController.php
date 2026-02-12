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

        $producers = User::query()
            ->when($dni !== '', fn($q) => $q->where('dni', 'like', "%{$dni}%"))
            ->when($name !== '', fn($q) => $q->where('name', 'like', "%{$name}%"))
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // devolvemos sólo lo mínimo al frontend
        $producers->getCollection()->transform(fn($u) => [
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
            ],
            'producers' => $producers,
        ]);
    }

    public function show($id)
    {
        $user = Auth::guard('staff')->user();
        
        // Cargar el productor con todas sus relaciones
        $producer = User::with([
            'propiedades.cultivos',
            'propiedades.maquinarias',
            'comercializacion'
        ])->findOrFail($id);

        // Preparar datos de propiedades
        $propiedades = $producer->propiedades->map(fn($p) => [
            'id' => $p->id,
            'direccion' => $p->direccion,
            'hectareas' => $p->hectareas,
            'tipo_tenencia' => $p->tipo_tenencia,
            'derecho_riego' => $p->derecho_riego,
            'malla' => $p->malla,
            'cierre_perimetral' => $p->cierre_perimetral,
        ]);

        // Preparar datos de cultivos (de todas las propiedades)
        $cultivos = collect();
        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                $cultivos->push([
                    'id' => $cult->id,
                    'nombre' => $cult->nombre,
                    'tipo' => $cult->tipo,
                    'hectareas' => $cult->hectareas,
                    'manejo_cultivo' => $cult->manejo_cultivo,
                    'tecnologia_riego' => $cult->tecnologia_riego,
                ]);
            }
        }

        // Preparar datos de maquinarias
        $maquinarias = collect();
        foreach ($producer->propiedades as $prop) {
            foreach ($prop->maquinarias as $maq) {
                $maquinarias->push([
                    'id' => $maq->id,
                    'nombre' => $maq->nombre,
                    'tipo' => $maq->tipo,
                    'estado' => $maq->estado,
                ]);
            }
        }

        // Preparar datos de comercio
        $comercioData = $producer->comercializacion->first();
        $comercio = $comercioData ? [
            'infraestructura_empaque' => $comercioData->infraestructura_empaque,
            'vende_en_finca' => $comercioData->vende_en_finca,
            'mercados' => $comercioData->mercados ?? [],
            'cooperativas' => $comercioData->cooperativas ?? [],
        ] : null;

        // Calcular estadísticas
        $stats = [
            'propiedades' => $producer->propiedades->count(),
            'cultivos' => $cultivos->count(),
            'maquinarias' => $maquinarias->count(),
            'hectareas' => $producer->propiedades->sum('hectareas'),
        ];

        return inertia('Staff/Producers/Show', [
            'authUser' => $user,
            'producer' => [
                'id' => $producer->id,
                'name' => $producer->name,
                'dni' => $producer->dni,
                'email' => $producer->email,
                'telefono' => $producer->telefono,
                'cooperativas' => $producer->cooperativas ?? [],
            ],
            'propiedades' => $propiedades,
            'cultivos' => $cultivos,
            'maquinarias' => $maquinarias,
            'comercio' => $comercio,
            'stats' => $stats,
        ]);
    }
}
