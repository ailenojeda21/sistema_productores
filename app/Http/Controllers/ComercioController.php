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
            'comercio_mercado' => 'nullable',
            'nombre_mercado' => 'nullable|string|max:255',
            'mercados' => 'nullable|array',
        ]);

        // Forzar booleanos
        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['comercio_mercado'] = $request->has('comercio_mercado') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['comercializa_cooperativas'] = $request->has('comercializa_cooperativas') ? 1 : 0;

        // Si no vende en mercado, nombre_mercado y mercados deben ser null
        if (!$validated['comercio_mercado']) {
            $validated['nombre_mercado'] = null;
            $validated['mercados'] = null;
        } else {
            $validated['mercados'] = $request->input('mercados', []);
        }

        // Asignar el usuario autenticado
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
            'comercio_mercado' => 'nullable',
            'nombre_mercado' => 'nullable|string|max:255',
            'mercados' => 'nullable|array',
        ]);

        // Forzar booleanos
        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['comercio_mercado'] = $request->has('comercio_mercado') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;
        $validated['comercializa_cooperativas'] = $request->has('comercializa_cooperativas') ? 1 : 0;

        // Si no vende en mercado, nombre_mercado y mercados deben ser null
        if (!$validated['comercio_mercado']) {
            $validated['nombre_mercado'] = null;
            $validated['mercados'] = null;
        } else {
            $validated['mercados'] = $request->input('mercados', []);
        }

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