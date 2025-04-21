<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Implemento;

class ImplementoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $implementos = Implemento::with(['maquinaria'])->get();
        return response()->json($implementos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'maquinaria_id' => 'required|exists:maquinarias,id',
            'nombre' => 'required|string|max:255',
        ]);
        $implemento = Implemento::create($validated);
        return response()->json($implemento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $implemento = Implemento::with(['maquinaria'])->findOrFail($id);
        return response()->json($implemento);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $implemento = Implemento::findOrFail($id);
        $validated = $request->validate([
            'maquinaria_id' => 'sometimes|exists:maquinarias,id',
            'nombre' => 'sometimes|string|max:255',
        ]);
        $implemento->update($validated);
        return response()->json($implemento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $implemento = Implemento::findOrFail($id);
        $implemento->delete();
        return response()->json(['message' => 'Implemento eliminado correctamente']);
    }
}
