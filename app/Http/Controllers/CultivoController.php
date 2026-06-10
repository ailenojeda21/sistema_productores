<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $this->authorize('create', Cultivo::class);

        $propiedad = Propiedad::where('id', $request->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return back()->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida'])->withInput();
        }

        $hectareasDisponibles = $propiedad->hectareas_disponibles;

        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'variedad' => 'required|string|max:255',
            'estacion' => 'required|string|max:255',
            'tipo' => ['required', 'string', 'max:255', 'regex:/^[\pL\pM0-9\s\-\.\,\/]+$/u'],
            'hectareas' => "required|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'required|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'required|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ], [
            'hectareas.max' => "Las hectáreas del cultivo no pueden exceder las disponibles ($hectareasDisponibles ha).",
            'tipo.regex' => 'El tipo contiene caracteres no permitidos.',
        ]);

        $validated['tipo'] = $this->normalizarTipo($validated['tipo'] ?? null);

        Cultivo::create($validated);

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

    public function update(Request $request, string $id)
    {
        $cultivo = Cultivo::with('propiedad')->findOrFail($id);

        $this->authorize('update', $cultivo);

        $propiedad = Propiedad::where('id', $request->propiedad_id ?: $cultivo->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return back()->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida'])->withInput();
        }

        $hectareasUsadas = $propiedad->cultivos()
            ->where('id', '!=', $id)
            ->sum('hectareas');

        $hectareasDisponibles = max(0, $propiedad->hectareas - $hectareasUsadas);

        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'variedad' => 'sometimes|string|max:255',
            'estacion' => 'sometimes|string|max:255',
            'tipo' => ['sometimes', 'string', 'max:255', 'regex:/^[\pL\pM0-9\s\-\.\,\/]+$/u'],
            'hectareas' => "sometimes|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'sometimes|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'sometimes|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ], [
            'hectareas.max' => "Las hectáreas del cultivo no pueden exceder las disponibles ($hectareasDisponibles ha).",
            'tipo.regex' => 'El tipo contiene caracteres no permitidos.',
        ]);

        if (array_key_exists('tipo', $validated)) {
            $validated['tipo'] = $this->normalizarTipo($validated['tipo']);
        }

        $cultivo->update($validated);

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
