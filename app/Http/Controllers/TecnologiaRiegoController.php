<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TecnologiaRiego;

class TecnologiaRiegoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tecnologias = TecnologiaRiego::with(['propiedad'])->get();
        return response()->json($tecnologias);
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
        $tecnologia = TecnologiaRiego::create($validated);
        return response()->json($tecnologia, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tecnologia = TecnologiaRiego::with(['propiedad'])->findOrFail($id);
        return response()->json($tecnologia);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tecnologia = TecnologiaRiego::findOrFail($id);
        $validated = $request->validate([
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'tipo' => 'sometimes|string|max:255',
        ]);
        $tecnologia->update($validated);
        return response()->json($tecnologia);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tecnologia = TecnologiaRiego::findOrFail($id);
        $tecnologia->delete();
        return response()->json(['message' => 'Tecnología de riego eliminada correctamente']);
    }
}
