<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use Illuminate\Http\Request;

class ComercioController extends Controller
{
    /**
     * Listar todos los comercios con su usuario relacionado.
     */
    public function index()
    {
        $comercios = Comercio::with('usuario')->get();
        return response()->json($comercios);
    }

    /**
     * Crear un nuevo comercio.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'infraestructura_empaque' => 'required|boolean',
            'comercio_feria' => 'required|boolean',
            'nombre_feria' => 'nullable|string|max:255',
        ]);

        $comercio = Comercio::create($validated);

        return response()->json($comercio, 201);
    }

    /**
     * Mostrar un comercio específico con sus relaciones.
     */
    public function show(string $id)
    {
        $comercio = Comercio::with(['usuario', 'archivos', 'maquinarias', 'cultivos'])->findOrFail($id);
        return response()->json($comercio);
    }

    /**
     * Actualizar un comercio existente.
     */
    public function update(Request $request, string $id)
    {
        $comercio = Comercio::findOrFail($id);

        $validated = $request->validate([
            'usuario_id' => 'sometimes|exists:users,id',
            'infraestructura_empaque' => 'sometimes|boolean',
            'comercio_feria' => 'sometimes|boolean',
            'nombre_feria' => 'nullable|string|max:255',
        ]);

        $comercio->update($validated);

        return response()->json($comercio);
    }

    /**
     * Eliminar un comercio.
     */
    public function destroy(string $id)
    {
        $comercio = Comercio::findOrFail($id);
        $comercio->delete();

        return response()->json(['message' => 'Comercialización eliminada correctamente']);
    }
}
