<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Support\Facades\Validator;

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
        // obtener propiedades del usuario para el select
        $propiedades = Propiedad::where('usuario_id', auth()->id())
            ->whereDoesntHave('maquinaria')
            ->get();
        return view('maquinaria.create', compact('propiedades'));
    }

    public function store(Request $request)
    {
        // Si viene el bulk (maquinarias[]), procesar como múltiples registros
        if ($request->has('maquinarias') && is_array($request->input('maquinarias'))) {
            $implements = [
                'arado','rastra','niveleta_comun','niveleta_laser','cincel_cultivadora',
                'desmalezadora','pulverizadora_tractor','mochila_pulverizadora',
                'cosechadora','enfardadora','retroexcavadora','carro_carreton'
            ];

            foreach ($request->input('maquinarias') as $i => $item) {
                $validator = Validator::make($item, [
                    'propiedad_id' => 'required|exists:propiedades,id',
                    'modelo_tractor' => 'nullable|integer|min:1900|max:'.date('Y'),
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                // Verificar si ya existe una maquinaria para esta propiedad
                $existente = Maquinaria::where('propiedad_id', $item['propiedad_id'])->first();
                if ($existente) {
                    $propiedad = Propiedad::find($item['propiedad_id']);
                    return redirect()->back()
                        ->with('error', 'Ya existe una maquinaria registrada para la propiedad: ' . ($propiedad->direccion ?? 'ID ' . $item['propiedad_id']))
                        ->withInput();
                }

                $ma = new Maquinaria();
                $ma->propiedad_id = $item['propiedad_id'];
                $ma->tractor = isset($item['tractor']) && $item['tractor'] ? 1 : 0;
                $ma->modelo_tractor = $item['modelo_tractor'] ?? null;

                // Asignar implementos booleanos
                foreach ($implements as $field) {
                    $ma->$field = isset($item[$field]) && $item[$field] ? 1 : 0;
                }

                $ma->save();
            }

            return redirect()->route('maquinaria.index')->with('success', 'Maquinarias creadas correctamente.');
        }

        // Caso single (forma antigua / compatibilidad)
        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'modelo_tractor' => 'nullable|integer|min:1900|max:'.date('Y'),
        ]);
        
        // Verificar si ya existe una maquinaria para esta propiedad
        $existente = Maquinaria::where('propiedad_id', $validated['propiedad_id'])->first();
        if ($existente) {
            $propiedad = Propiedad::find($validated['propiedad_id']);
            return redirect()->back()
                ->with('error', 'Ya existe una maquinaria registrada para la propiedad: ' . ($propiedad->direccion ?? 'ID ' . $validated['propiedad_id']))
                ->withInput();
        }

        $ma = new Maquinaria();
        $ma->propiedad_id = $validated['propiedad_id'];
        $ma->tractor = $request->has('tractor') ? 1 : 0;
        $ma->modelo_tractor = $validated['modelo_tractor'] ?? null;

        // asignar implementos (compatibilidad simple)
        foreach (['arado','rastra','niveleta_comun','niveleta_laser','cincel_cultivadora','desmalezadora','pulverizadora_tractor','mochila_pulverizadora','cosechadora','enfardadora','retroexcavadora','carro_carreton'] as $f) {
            $ma->$f = $request->has($f) ? 1 : 0;
        }

        $ma->save();

        return redirect()->route('maquinaria.index')->with('info', 'Maquinaria creada correctamente.');
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        $propiedades = Propiedad::where('usuario_id', auth()->id())
            ->where(function($q) use ($maquinaria) {
                $q->whereDoesntHave('maquinaria')
                  ->orWhere('id', $maquinaria->propiedad_id);
            })
            ->get();
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
        
        // Verificar si ya existe otra maquinaria para esta propiedad (excluyendo la actual)
        $existente = Maquinaria::where('propiedad_id', $validated['propiedad_id'])
            ->where('id', '!=', $id)
            ->first();
            
        if ($existente) {
            $propiedad = Propiedad::find($validated['propiedad_id']);
            return redirect()->back()
                ->with('error', 'Ya existe otra maquinaria registrada para la propiedad: ' . ($propiedad->direccion ?? 'ID ' . $validated['propiedad_id']))
                ->withInput();
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
            \Log::error('Error actualizando maquinaria: ' . $e->getMessage(), ['input' => $request->all(), 'id' => $id]);
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
