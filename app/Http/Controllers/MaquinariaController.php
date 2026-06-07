<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaquinariaController extends Controller
{
    public function index()
    {
        $maquinarias = Maquinaria::with('propiedad')
            ->whereHas('propiedad', function ($query) {
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
        $items = $request->input('maquinarias');
        $isBulk = is_array($items);

        $entries = $isBulk ? $items : [$request->all()];

        foreach ($entries as $index => $entry) {
            $validator = Validator::make($entry, [
                'propiedad_id' => 'required|exists:propiedades,id',
                'modelo_tractor' => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:'.date('Y'),
                    function ($attr, $value, $fail) use ($entry) {
                        if (! empty($entry['tractor']) && empty($value)) {
                            $fail('El año del tractor es obligatorio cuando se marca la opción tractor.');
                        }
                    },
                ],
            ], [
                'modelo_tractor.integer' => 'El año del tractor debe ser un número entero.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Verificar que la propiedad pertenece al usuario autenticado
            $propiedad = Propiedad::where('id', $entry['propiedad_id'])
                ->where('usuario_id', auth()->id())
                ->first();

            if (! $propiedad) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida.']);
            }

            $existente = Maquinaria::where('propiedad_id', $entry['propiedad_id'])->first();
            if ($existente) {
                return redirect()->back()
                    ->with('error', 'Ya existe una maquinaria registrada para la propiedad: '.($propiedad->calle ?? 'ID '.$entry['propiedad_id']))
                    ->withInput();
            }

            $ma = new Maquinaria;
            $ma->propiedad_id = $entry['propiedad_id'];
            $ma->tractor = ! empty($entry['tractor']) ? 1 : 0;
            $ma->modelo_tractor = ! empty($entry['tractor']) ? ($entry['modelo_tractor'] ?? null) : null;

            foreach (Maquinaria::implementosKeys() as $field) {
                $ma->$field = ! empty($entry[$field]) ? 1 : 0;
            }

            $ma->save();
        }

        $msg = $isBulk
            ? 'Maquinarias creadas correctamente.'
            : 'Maquinaria creada correctamente.';

        return redirect()->route('maquinaria.index')->with('success', $msg);
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::whereHas('propiedad', fn($q) => $q->where('usuario_id', auth()->id()))->findOrFail($id);
        $propiedades = Propiedad::where('usuario_id', auth()->id())
            ->where(function ($q) use ($maquinaria) {
                $q->whereDoesntHave('maquinaria')
                    ->orWhere('id', $maquinaria->propiedad_id);
            })
            ->get();

        return view('maquinaria.edit', compact('maquinaria', 'propiedades'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::whereHas('propiedad', fn($q) => $q->where('usuario_id', auth()->id()))->findOrFail($id);

        $validated = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'modelo_tractor' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.date('Y'),
                function ($attr, $value, $fail) use ($request) {
                    if ($request->has('tractor') && empty($value)) {
                        $fail('El año del tractor es obligatorio cuando se marca la opción tractor.');
                    }
                },
            ],
        ], [
            'modelo_tractor.integer' => 'El año del tractor debe ser un número entero.',
        ]);

        // Verificar que la propiedad pertenece al usuario autenticado
        $propiedad = Propiedad::where('id', $validated['propiedad_id'])
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida.']);
        }

        // Verificar si ya existe otra maquinaria para esta propiedad (excluyendo la actual)
        $existente = Maquinaria::where('propiedad_id', $validated['propiedad_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existente) {
            return redirect()->back()
                ->with('error', 'Ya existe otra maquinaria registrada para la propiedad: '.($propiedad->calle ?? 'ID '.$validated['propiedad_id']))
                ->withInput();
        }

        foreach (Maquinaria::implementosKeys() as $campo) {
            $validated[$campo] = $request->has($campo) ? 1 : 0;
        }

        $validated['tractor'] = $request->boolean('tractor') ? 1 : 0;

        if (! $request->boolean('tractor')) {
            $validated['modelo_tractor'] = null;
        }

        try {
            $maquinaria->update($validated);
        } catch (\Exception $e) {
            \Log::error('Error actualizando maquinaria: '.$e->getMessage(), ['input' => $request->all(), 'id' => $id]);

            return redirect()->back()->withInput()->withErrors(['general' => 'Ocurrió un error al actualizar la maquinaria.']);
        }

        return redirect()->route('maquinaria.index')
            ->with('success', 'Maquinaria actualizada correctamente');
    }

    public function destroy($id)
    {
        $maquinaria = Maquinaria::whereHas('propiedad', function ($q) {
            $q->where('usuario_id', auth()->id());
        })->findOrFail($id);
        $maquinaria->delete();

        return redirect()->route('maquinaria.index')
            ->with('success', 'Maquinaria eliminada correctamente');
    }
}
