<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Http\Request;

class CultivoController extends Controller
{
    /**
     * Listado de cultivos.
     */
    public function index()
    {
        $cultivos = Cultivo::with('propiedad')
            ->whereHas('propiedad', function ($query) {
                $query->where('usuario_id', auth()->id());
            })
            ->get();

        return view('cultivos.index', compact('cultivos'));
    }

    /**
     * Obtener hectáreas disponibles de una propiedad (API)
     */
    public function hectareasDisponibles(Request $request)
    {
        $propiedad = Propiedad::where('id', $request->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return response()->json(['error' => 'Propiedad no encontrada'], 404);
        }

        // Si se proporciona un cultivo_id, excluir sus hectáreas del cálculo (para edición)
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

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $propiedades = \App\Models\Propiedad::where('usuario_id', auth()->id())->get();

        return view('cultivos.create', compact('propiedades'));
    }

    /**
     * Guardar un cultivo nuevo.
     */
    public function store(Request $request)
    {
        $propiedad = Propiedad::where('id', $request->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return back()->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida'])->withInput();
        }

        $hectareasDisponibles = $propiedad->hectareas_disponibles;

        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'nombre' => 'required|string|max:255',
            'estacion' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'hectareas' => "required|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'required|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'required|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ], [
            'hectareas.max' => "Las hectáreas del cultivo no pueden exceder las disponibles ($hectareasDisponibles ha).",
        ]);

        Cultivo::create($validated);

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo creado correctamente');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(string $id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $propiedades = Propiedad::where('usuario_id', auth()->id())->get();

        return view('cultivos.edit', compact('cultivo', 'propiedades'));
    }

    /**
     * Actualizar un cultivo.
     */
    public function update(Request $request, string $id)
    {
        $cultivo = Cultivo::findOrFail($id);

        // Verificar que el usuario tenga acceso al cultivo
        if ($cultivo->propiedad->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este cultivo');
        }

        $propiedad = Propiedad::where('id', $request->propiedad_id ?: $cultivo->propiedad_id)
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return back()->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida'])->withInput();
        }

        // Calcular hectáreas disponibles excluyendo el cultivo actual
        $hectareasUsadas = $propiedad->cultivos()
            ->where('id', '!=', $id)
            ->sum('hectareas');
        $hectareasDisponibles = max(0, $propiedad->hectareas - $hectareasUsadas);

        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'nombre' => 'sometimes|string|max:255',
            'estacion' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|string|max:255',
            'hectareas' => "sometimes|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'sometimes|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'sometimes|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ], [
            'hectareas.max' => "Las hectáreas del cultivo no pueden exceder las disponibles ($hectareasDisponibles ha).",
        ]);

        $cultivo->update($validated);

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo actualizado correctamente');
    }

    /**
     * Eliminar un cultivo.
     */
    public function destroy(string $id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $cultivo->delete();

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo eliminado correctamente');
    }
}
