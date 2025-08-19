<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropiedadController extends Controller
{
    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('propiedades.create');
    }

    /**
     * Listar propiedades
     */
    public function index()
    {
        $propiedades = Propiedad::with(['usuario', 'maquinarias', 'cultivos'])->get();
        return view('propiedades.index', compact('propiedades'));
    }

    /**
     * Guardar nueva propiedad
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ubicacion' => 'required|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'hectareas' => 'required|numeric|min:0',
            'es_propietario' => 'nullable',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:subterráneo,superficial,ambos',
            'rut' => 'nullable',
            'rut_valor' => 'nullable|numeric',
            'rut_archivo' => 'nullable|string|max:255',
            'hectareas_malla' => 'nullable|numeric',
            'cierre_perimetral' => 'nullable',
        ]);

        $validated['es_propietario'] = $request->has('es_propietario') ? 1 : 0;
        $validated['malla'] = $request->has('malla') ? 1 : 0;
        $validated['derecho_riego'] = $request->has('derecho_riego') ? 1 : 0;
        $validated['rut'] = $request->has('rut') ? 1 : 0;
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral') ? 1 : 0;
        $validated['usuario_id'] = Auth::id();

        Propiedad::create($validated);

        return redirect()->route('propiedades.index')
                         ->with('success', 'Propiedad creada correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        return view('propiedades.edit', compact('propiedad'));
    }

    /**
     * Actualizar propiedad
     */
    public function update(Request $request, string $id)
    {
        $propiedad = Propiedad::findOrFail($id);

        $validated = $request->validate([
            'usuario_id' => 'sometimes|exists:users,id',
            'ubicacion' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'hectareas' => 'sometimes|numeric|min:0',
            'es_propietario' => 'nullable',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:subterráneo,superficial,ambos',
            'rut' => 'nullable',
            'rut_valor' => 'nullable|numeric',
            'rut_archivo' => 'nullable|string|max:255',
            'hectareas_malla' => 'nullable|numeric',
            'cierre_perimetral' => 'nullable',
        ]);

        $validated['es_propietario'] = $request->has('es_propietario') ? 1 : 0;
        $validated['malla'] = $request->has('malla') ? 1 : 0;
        $validated['derecho_riego'] = $request->has('derecho_riego') ? 1 : 0;
        $validated['rut'] = $request->has('rut') ? 1 : 0;
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral') ? 1 : 0;

        $propiedad->update($validated);

        return redirect()->route('propiedades.index')
                         ->with('success', 'Propiedad actualizada correctamente');
    }

    /**
     * Eliminar propiedad
     */
    public function destroy(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->delete();

        return redirect()->route('propiedades.index')
                         ->with('success', 'Propiedad eliminada correctamente');
    }
}
