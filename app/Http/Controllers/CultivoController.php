<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cultivo;

class CultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cultivos = Cultivo::with(['propiedad'])->get();
        return response()->json($cultivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'estacion' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'hectareas' => 'required|numeric|min:0',
        ]);
        $cultivo = Cultivo::create($validated);
        return response()->json($cultivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cultivo = Cultivo::with(['propiedad'])->findOrFail($id);
        return response()->json($cultivo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'estacion' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|string|max:255',
            'hectareas' => 'sometimes|numeric|min:0',
        ]);
        $cultivo->update($validated);
        return response()->json($cultivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $cultivo->delete();
        return response()->json(['message' => 'Cultivo eliminado correctamente']);
    }
}
