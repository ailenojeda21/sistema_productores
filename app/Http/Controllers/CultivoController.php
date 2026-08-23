<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCultivoRequest;
use App\Http\Requests\UpdateCultivoRequest;
use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CultivoController extends Controller
{
    private function normalizarTipo(?string $tipo): ?string
    {
        if ($tipo === null) {
            return null;
        }

        $tipo = trim($tipo);
        $tipo = preg_replace('/\s+/', ' ', $tipo);
        $tipo = mb_strtolower($tipo, 'UTF-8');
        $tipo = mb_convert_case($tipo, MB_CASE_TITLE, 'UTF-8');

        return $tipo;
    }

    public function index()
    {
        $this->authorize('viewAny', Cultivo::class);

        $cultivos = Cultivo::with('propiedad')
            ->whereHas('propiedad', function ($query) {
                $query->where('usuario_id', auth()->id());
            })
            ->get();

        return view('cultivos.index', compact('cultivos'));
    }

    public function hectareasDisponibles(Request $request)
    {
        $propiedad = Propiedad::where('id', $request->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return response()->json(['error' => 'Propiedad no encontrada'], 404);
        }

        $hectareasUsadas = $propiedad->cultivos()
            ->when($request->cultivo_id, function ($query) use ($request) {
                return $query->where('id', '!=', $request->cultivo_id);
            })
            ->sum('hectareas');

        $hectareasDisponibles = max(0, $propiedad->hectareas - $hectareasUsadas);

        return response()->json([
            'hectareas_disponibles' => (float) $hectareasDisponibles,
            'hectareas_totales' => (float) $propiedad->hectareas,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Cultivo::class);

        $propiedades = Propiedad::where('usuario_id', auth()->id())->get();

        return view('cultivos.create', compact('propiedades'));
    }

    public function store(StoreCultivoRequest $request)
    {
        $this->authorize('create', Cultivo::class);

        $validated = $request->validated();

        $validated['tipo'] = $this->normalizarTipo($validated['tipo'] ?? null);

        // Serializa la verificación de superficie (hallazgo M2): bloquea la fila
        // de la propiedad para que requests paralelos no superen el total.
        DB::transaction(function () use ($validated) {
            $propiedad = Propiedad::where('id', $validated['propiedad_id'])
                ->where('usuario_id', auth()->id())
                ->lockForUpdate()
                ->first();

            if (! $propiedad) {
                throw ValidationException::withMessages([
                    'propiedad_id' => ['La propiedad seleccionada no es válida.'],
                ]);
            }

            $hectareasUsadas = (float) $propiedad->cultivos()->sum('hectareas');
            $disponibles = max(0, (float) $propiedad->hectareas - $hectareasUsadas);

            if (round((float) $validated['hectareas'], 2) > round($disponibles, 2)) {
                throw ValidationException::withMessages([
                    'hectareas' => ['Las hectáreas indicadas exceden la superficie disponible de la propiedad.'],
                ]);
            }

            Cultivo::create($validated);
        });

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo creado correctamente');
    }

    public function edit(string $id)
    {
        $cultivo = Cultivo::with('propiedad')->findOrFail($id);

        $this->authorize('update', $cultivo);

        $propiedades = Propiedad::where('usuario_id', auth()->id())->get();

        return view('cultivos.edit', compact('cultivo', 'propiedades'));
    }

    public function update(UpdateCultivoRequest $request, string $id)
    {
        $cultivo = Cultivo::with('propiedad')->findOrFail($id);

        $this->authorize('update', $cultivo);

        $validated = $request->validated();

        if (array_key_exists('tipo', $validated)) {
            $validated['tipo'] = $this->normalizarTipo($validated['tipo']);
        }

        DB::transaction(function () use ($validated, $cultivo) {
            $propiedadId = $validated['propiedad_id'] ?? $cultivo->propiedad_id;

            $propiedad = Propiedad::where('id', $propiedadId)
                ->where('usuario_id', auth()->id())
                ->lockForUpdate()
                ->first();

            if (! $propiedad) {
                throw ValidationException::withMessages([
                    'propiedad_id' => ['La propiedad seleccionada no es válida.'],
                ]);
            }

            if (array_key_exists('hectareas', $validated)) {
                $usadasPorOtros = (float) $propiedad->cultivos()
                    ->where('id', '!=', $cultivo->id)
                    ->sum('hectareas');
                $disponibles = max(0, (float) $propiedad->hectareas - $usadasPorOtros);

                if (round((float) $validated['hectareas'], 2) > round($disponibles, 2)) {
                    throw ValidationException::withMessages([
                        'hectareas' => ['Las hectáreas indicadas exceden la superficie disponible de la propiedad.'],
                    ]);
                }
            }

            $cultivo->update($validated);
        });

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $cultivo = Cultivo::with('propiedad')->findOrFail($id);

        $this->authorize('delete', $cultivo);

        $cultivo->delete();

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo eliminado correctamente');
    }
}
