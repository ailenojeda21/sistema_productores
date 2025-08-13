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
        $comercios = Comercio::with('usuario')->get();
        return view('comercios.index', compact('comercios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo comercio.
     */
    public function create()
    {
        return view('comercios.create');
    }

    /**
     * Almacenar un nuevo comercio.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'infraestructura_empaque' => 'nullable',
            'comercio_feria' => 'nullable',
            'nombre_feria' => 'nullable|string|max:255',
        ]);

        // Forzar booleanos
        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['comercio_feria'] = $request->has('comercio_feria') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;

        // Si no vende en feria, nombre_feria debe ser null
        if (!$validated['comercio_feria']) {
            $validated['nombre_feria'] = null;
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
            'comercio_feria' => 'nullable',
            'nombre_feria' => 'nullable|string|max:255',
        ]);

        // Forzar booleanos
        $validated['infraestructura_empaque'] = $request->has('infraestructura_empaque') ? 1 : 0;
        $validated['comercio_feria'] = $request->has('comercio_feria') ? 1 : 0;
        $validated['vende_en_finca'] = $request->has('vende_en_finca') ? 1 : 0;

        // Si no vende en feria, nombre_feria debe ser null
        if (!$validated['comercio_feria']) {
            $validated['nombre_feria'] = null;
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