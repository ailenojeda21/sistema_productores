<?php

namespace App\Http\Controllers;


use App\Models\Comercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComercioController extends Controller
{
    /**
     * Listar todos los comercios con su usuario relacionado.
     */
    public function index()
    {
        $comercios = Comercio::with('usuario')
            ->where('usuario_id', Auth::id())
            ->get();
        return view('comercios.index', compact('comercios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo comercio.
     */
    public function create()
    {
        // Si ya existe un comercio asociado al usuario, redirigir a editar
        $existing = Comercio::where('usuario_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('comercios.edit', $existing->id)
                ->with('info', 'Ya existe un comercio. Puedes editarlo.');
        }
        return view('comercios.create');
    }

    /**
     * Almacenar un nuevo comercio.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'infraestructura_empaque' => 'nullable',
            'vende_en_finca' => 'nullable',
            'tiene_mercados' => 'nullable',
            'mercados' => 'nullable|array',
            'tiene_cooperativas' => 'nullable',
            'cooperativas' => 'nullable|array',
        ]);

        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['mercados'] = $request->has('tiene_mercados') ? $request->input('mercados', []) : [];
        $validated['cooperativas'] = $request->has('tiene_cooperativas') ? $request->input('cooperativas', []) : [];

        $validated['usuario_id'] = Auth::id();

        Comercio::create($validated);
        return redirect()->route('comercios.index')->with('success', 'Comercio creado correctamente');
    }

    /**
     * Mostrar un comercio específico con sus relaciones.
     */
    public function show($id)
    {
        $comercio = Comercio::with('usuario')->findOrFail($id);
        return view('comercios.show', compact('comercio'));
    }

    /**
     * Mostrar el formulario para editar un comercio existente.
     */
    public function edit($id)
    {
        $comercio = Comercio::findOrFail($id);
        return view('comercios.edit', compact('comercio'));
    }

    /**
     * Actualizar un comercio existente.
     */
    public function update(Request $request, $id)
    {
        $comercio = Comercio::findOrFail($id);

        $validated = $request->validate([
            'infraestructura_empaque' => 'nullable',
            'vende_en_finca' => 'nullable',
            'tiene_mercados' => 'nullable',
            'mercados' => 'nullable|array',
            'tiene_cooperativas' => 'nullable',
            'cooperativas' => 'nullable|array',
        ]);

        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['mercados'] = $request->has('tiene_mercados') ? $request->input('mercados', []) : [];
        $validated['cooperativas'] = $request->has('tiene_cooperativas') ? $request->input('cooperativas', []) : [];

        $comercio->update($validated);
        return redirect()->route('comercios.index')->with('success', 'Comercio actualizado correctamente');
    }

    /**
     * Eliminar un comercio.
     */
    public function destroy($id)
    {
        $comercio = Comercio::findOrFail($id);
        $comercio->delete();
        return redirect()->route('comercios.index')->with('success', 'Comercio eliminado correctamente');
    }
}