<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMaquinariaRequest;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaquinariaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Maquinaria::class);

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
        $this->authorize('create', Maquinaria::class);

        $propiedades = Propiedad::where('usuario_id', auth()->id())
            ->whereDoesntHave('maquinaria')
            ->get();

        return view('maquinaria.create', compact('propiedades'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Maquinaria::class);

        $items = $request->input('maquinarias');
        $isBulk = is_array($items);

        $entries = $isBulk ? $items : [$request->only(array_merge(['propiedad_id', 'modelo_tractor', 'tractor'], Maquinaria::implementosKeys()))];

        DB::transaction(function () use ($entries) {
            foreach ($entries as $entry) {
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
                    throw new \Illuminate\Validation\ValidationException($validator);
                }

                $propiedad = Propiedad::where('id', $entry['propiedad_id'])
                    ->where('usuario_id', auth()->id())
                    ->first();

                if (! $propiedad) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Propiedad no encontrada');
                }

                $existente = Maquinaria::lockForUpdate()->where('propiedad_id', $entry['propiedad_id'])->first();
                if ($existente) {
                    throw new \App\Exceptions\BusinessRuleException('Ya existe una maquinaria registrada para la propiedad: '.($propiedad->calle ?? 'ID '.$entry['propiedad_id']));
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
        });

        $msg = $isBulk
            ? 'Maquinarias creadas correctamente.'
            : 'Maquinaria creada correctamente.';

        return redirect()->route('maquinaria.index')->with('success', $msg);
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::with('propiedad')->findOrFail($id);

        $this->authorize('update', $maquinaria);

        $propiedades = Propiedad::where('usuario_id', auth()->id())
            ->where(function ($q) use ($maquinaria) {
                $q->whereDoesntHave('maquinaria')
                    ->orWhere('id', $maquinaria->propiedad_id);
            })
            ->get();

        return view('maquinaria.edit', compact('maquinaria', 'propiedades'));
    }

    public function update(UpdateMaquinariaRequest $request, $id)
    {
        $maquinaria = Maquinaria::with('propiedad')->findOrFail($id);

        $this->authorize('update', $maquinaria);

        $validated = $request->validated();

        $propiedad = Propiedad::where('id', $validated['propiedad_id'])
            ->where('usuario_id', auth()->id())
            ->first();

        if (! $propiedad) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['propiedad_id' => 'La propiedad seleccionada no es válida.']);
        }

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
            \Log::error('Error actualizando maquinaria: '.$e->getMessage(), ['id' => $id]);

            return redirect()->back()->withInput()->withErrors(['general' => 'Ocurrió un error al actualizar la maquinaria.']);
        }

        return redirect()->route('maquinaria.index')
            ->with('success', 'Maquinaria actualizada correctamente');
    }

    public function destroy($id)
    {
        $maquinaria = Maquinaria::with('propiedad')->findOrFail($id);

        $this->authorize('delete', $maquinaria);

        $maquinaria->delete();

        return redirect()->route('maquinaria.index')
            ->with('success', 'Maquinaria eliminada correctamente');
    }
}
