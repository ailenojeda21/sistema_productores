<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Maquinaria;
use App\Models\Propiedad;

class MaquinariaController extends Controller
{
    public function index()
    {
        $maquinarias = Maquinaria::with('propiedad')
            ->whereHas('propiedad', function($query) {
                $query->where('usuario_id', auth()->id());
            })
            ->get();

    $hasMaquinaria = $maquinarias->count() > 0;
    return view('maquinaria.index', compact('maquinarias', 'hasMaquinaria'));
    }

    public function create()
    {
        // Si ya existe una maquinaria asociada a cualquier propiedad del usuario, redirigir a editar
        $existing = Maquinaria::whereHas('propiedad', function($q){
            $q->where('usuario_id', auth()->id());
        })->first();

        if ($existing) {
            return redirect()->route('maquinaria.edit', $existing->id)
                             ->with('info', 'Ya existe una maquinaria. Puedes editarla.');
        }

        $propiedades = Propiedad::where('usuario_id', auth()->id())->get();
        return view('maquinaria.create', compact('propiedades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'modelo_tractor' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        // Asegurar que la propiedad pertenece al usuario autenticado
        if (!Propiedad::where('id', $validated['propiedad_id'])->where('usuario_id', auth()->id())->exists()) {
            return redirect()->back()->withInput()->withErrors(['propiedad_id' => 'Propiedad inválida.']);
        }

        foreach ([
            'tractor', 'arado', 'rastra', 'niveleta_comun', 'niveleta_laser', 
            'cincel_cultivadora', 'desmalezadora', 'pulverizadora_tractor', 
            'mochila_pulverizadora', 'cosechadora', 'enfardadora', 'retroexcavadora', 'carro_carreton'
        ] as $campo) {
            $validated[$campo] = $request->has($campo) ? 1 : 0;
        }

        // Si ya existe una maquinaria para el usuario, actualizamos esa en vez de crear otra
        $existing = Maquinaria::whereHas('propiedad', function($q){
            $q->where('usuario_id', auth()->id());
        })->first();

        try {
            if ($existing) {
                $existing->update($validated);
                return redirect()->route('maquinaria.index')->with('success', 'Maquinaria actualizada correctamente');
            }

            Maquinaria::create($validated);
        } catch (\Exception $e) {
            Log::error('Error creando/actualizando maquinaria (single rule): ' . $e->getMessage(), ['input' => $request->all()]);
            return redirect()->back()->withInput()->withErrors(['general' => 'Ocurrió un error al guardar la maquinaria.']);
        }

        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria creada correctamente');
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $propiedades = Propiedad::where('usuario_id', auth()->id())->get();
        return view('maquinaria.edit', compact('maquinaria', 'propiedades'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);

        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'modelo_tractor' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        // Asegurar que la propiedad pertenece al usuario autenticado
        if (!Propiedad::where('id', $validated['propiedad_id'])->where('usuario_id', auth()->id())->exists()) {
            return redirect()->back()->withInput()->withErrors(['propiedad_id' => 'Propiedad inválida.']);
        }

        foreach ([
            'tractor', 'arado', 'rastra', 'niveleta_comun', 'niveleta_laser', 
            'cincel_cultivadora', 'desmalezadora', 'pulverizadora_tractor', 
            'mochila_pulverizadora', 'cosechadora', 'enfardadora', 'retroexcavadora', 'carro_carreton'
        ] as $campo) {
            $validated[$campo] = $request->has($campo) ? 1 : 0;
        }

        try {
            $maquinaria->update($validated);
        } catch (\Exception $e) {
            Log::error('Error actualizando maquinaria: ' . $e->getMessage(), ['input' => $request->all(), 'id' => $id]);
            return redirect()->back()->withInput()->withErrors(['general' => 'Ocurrió un error al actualizar la maquinaria.']);
        }

        return redirect()->route('maquinaria.index')
                         ->with('success', 'Maquinaria actualizada correctamente');
    }

    public function destroy($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $maquinaria->delete();
        return redirect()->route('maquinaria.index')
                         ->with('success', 'Maquinaria eliminada correctamente');
    }
}
