<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivo;

class CultivoController extends Controller
{
    /**
     * Listado de cultivos.
     */
    public function index()
    {
        $cultivos = Cultivo::with('propiedad')->get();
        return view('cultivos.index', compact('cultivos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('cultivos.create');
    }

    /**
     * Guardar un cultivo nuevo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'estacion' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'hectareas' => 'required|numeric|min:0',
            'manejo_cultivo' => 'required|in:Convencional,Agroecologico,Organico',
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
        return view('cultivos.edit', compact('cultivo'));
    }

    /**
     * Actualizar un cultivo.
     */
    public function update(Request $request, string $id)
    {
        $cultivo = Cultivo::findOrFail($id);

        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'estacion' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|string|max:255',
            'hectareas' => 'sometimes|numeric|min:0',
            'manejo_cultivo' => 'sometimes|in:Convencional,Agroecologico,Organico',
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
