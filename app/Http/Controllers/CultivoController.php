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
        return view('cultivos.index', compact('cultivos'));
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
            'riego_tecnificado' => 'nullable',
        ]);
        $validated['riego_tecnificado'] = $request->has('riego_tecnificado') ? 1 : 0;
        $cultivo = Cultivo::create($validated);
        return redirect()->route('cultivos.show', $cultivo->id)->with('success', 'Cultivo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cultivo = Cultivo::with(['propiedad'])->findOrFail($id);
        return view('cultivos.show', compact('cultivo'));
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
            'riego_tecnificado' => 'nullable',
        ]);
        $validated['riego_tecnificado'] = $request->has('riego_tecnificado') ? 1 : 0;
        $cultivo->update($validated);
        return redirect()->route('cultivos.show', $cultivo->id)->with('success', 'Cultivo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $cultivo->delete();
        return redirect()->route('cultivos.index')->with('success', 'Cultivo eliminado correctamente');
    }
}
