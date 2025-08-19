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
        $maquinarias = Maquinaria::with('propiedad')
            ->whereHas('propiedad', function($query) {
                $query->where('usuario_id', auth()->id());
            })
            ->get();
        return view('maquinaria.index', compact('maquinarias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        // Puedes pasar propiedades si es necesario para un select
        return view('maquinaria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'funciona' => 'nullable',
        ]);
        $validated['funciona'] = $request->has('funciona') ? 1 : 0;
        $maquinaria = Maquinaria::create($validated);
        return redirect()->route('maquinaria.show', $maquinaria->id)->with('success', 'Maquinaria creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        return view('maquinaria.show', compact('maquinaria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        return view('maquinaria.edit', compact('maquinaria'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'funciona' => 'nullable',
        ]);
        $validated['funciona'] = $request->has('funciona') ? 1 : 0;
        $maquinaria->update($validated);
        return redirect()->route('maquinaria.show', $maquinaria->id)->with('success', 'Maquinaria actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $maquinaria->delete();
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria eliminada correctamente');
    }
}
