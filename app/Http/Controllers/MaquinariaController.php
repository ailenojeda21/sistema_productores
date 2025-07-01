<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;

class MaquinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maquinarias = Maquinaria::with(['propiedad'])->get();
        return response()->json($maquinarias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'tipo' => 'required|string|max:255',
        ]);
        $maquinaria = Maquinaria::create($validated);
        return response()->json($maquinaria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $maquinaria = Maquinaria::with(['propiedad', 'implementos'])->findOrFail($id);
        return response()->json($maquinaria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'tipo' => 'sometimes|string|max:255',
        ]);
        $maquinaria->update($validated);
        return response()->json($maquinaria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $maquinaria->delete();
        return response()->json(['message' => 'Maquinaria eliminada correctamente']);
    }
}
