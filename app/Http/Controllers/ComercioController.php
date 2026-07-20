<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComercioRequest;
use App\Http\Requests\UpdateComercioRequest;
use App\Models\Comercio;
use Illuminate\Support\Facades\Auth;

class ComercioController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Comercio::class);

        $comercios = Comercio::with('usuario')
            ->where('usuario_id', Auth::id())
            ->get();

        return view('comercios.index', compact('comercios'));
    }

    public function create()
    {
        $this->authorize('create', Comercio::class);

        $existing = Comercio::where('usuario_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('comercios.edit', $existing->id)
                ->with('info', 'Ya existe un comercio. Puedes editarlo.');
        }

        return view('comercios.create');
    }

    public function store(StoreComercioRequest $request)
    {
        $this->authorize('create', Comercio::class);

        $validated = $request->validated();

        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['mercados'] = $request->has('tiene_mercados') ? $request->input('mercados', []) : [];
        $validated['cooperativas'] = $request->has('tiene_cooperativas') ? $request->input('cooperativas', []) : [];

        if (!$validated['vende_en_finca'] && empty($validated['mercados']) && empty($validated['cooperativas'])) {
            return back()->withInput()->withErrors([
                'comercializacion' => 'Debe seleccionar al menos una opción de comercialización: vende en finca, vende en mercados o comercializa en cooperativas.'
            ]);
        }

        $validated['usuario_id'] = Auth::id();

        Comercio::create($validated);

        return redirect()->route('comercios.index')->with('success', 'Comercio creado correctamente');
    }

    public function show($id)
    {
        $comercio = Comercio::with('usuario')->findOrFail($id);

        $this->authorize('view', $comercio);

        return view('comercios.show', compact('comercio'));
    }

    public function edit($id)
    {
        $comercio = Comercio::findOrFail($id);

        $this->authorize('update', $comercio);

        return view('comercios.edit', compact('comercio'));
    }

    public function update(UpdateComercioRequest $request, $id)
    {
        $comercio = Comercio::findOrFail($id);

        $this->authorize('update', $comercio);

        $validated = $request->validated();

        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['mercados'] = $request->has('tiene_mercados') ? $request->input('mercados', []) : [];
        $validated['cooperativas'] = $request->has('tiene_cooperativas') ? $request->input('cooperativas', []) : [];

        if (!$validated['vende_en_finca'] && empty($validated['mercados']) && empty($validated['cooperativas'])) {
            return back()->withInput()->withErrors([
                'comercializacion' => 'Debe seleccionar al menos una opción de comercialización: vende en finca, vende en mercados o comercializa en cooperativas.'
            ]);
        }

        $comercio->update($validated);

        return redirect()->route('comercios.index')->with('success', 'Comercio actualizado correctamente');
    }

    public function destroy($id)
    {
        $comercio = Comercio::findOrFail($id);

        $this->authorize('delete', $comercio);

        $comercio->delete();

        return redirect()->route('comercios.index')->with('success', 'Comercio eliminado correctamente');
    }
}
