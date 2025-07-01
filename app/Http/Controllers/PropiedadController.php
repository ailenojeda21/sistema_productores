<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/propiedades",
     *     summary="Listar propiedades",
     *     tags={"Propiedades"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de propiedades"
     *     )
     * )
     */
    public function index()
    {
        // Listar todas las propiedades con relaciones
        $propiedades = Propiedad::with(['usuario', 'archivos', 'maquinarias', 'cultivos'])->get();
        return response()->json($propiedades);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'ubicacion' => 'required|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'hectareas' => 'required|numeric|min:0',
            'es_propietario' => 'boolean',
            'derecho_riego' => 'boolean',
        ]);
        $propiedad = Propiedad::create($validated);
        return response()->json($propiedad, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $propiedad = Propiedad::with(['usuario', 'archivos', 'maquinarias', 'cultivos'])->findOrFail($id);
        return response()->json($propiedad);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $validated = $request->validate([
            'usuario_id' => 'sometimes|exists:users,id',
            'ubicacion' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'hectareas' => 'sometimes|numeric|min:0',
            'es_propietario' => 'sometimes|boolean',
            'derecho_riego' => 'sometimes|boolean',
        ]);
        $propiedad->update($validated);
        return response()->json($propiedad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->delete();
        return response()->json(['message' => 'Propiedad eliminada correctamente']);
    }
}
