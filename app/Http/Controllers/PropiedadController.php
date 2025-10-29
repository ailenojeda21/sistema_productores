<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $propiedades = Propiedad::with(['usuario', 'maquinarias', 'cultivos'])
            ->where('usuario_id', Auth::id())
            ->get();
        return view('propiedades.index', compact('propiedades'));
    }

    /**
     * Guardar nueva propiedad
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'hectareas' => 'required|numeric|min:0',
            'es_propietario' => 'nullable',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',
            'rut' => 'nullable',
            'rut_valor' => 'nullable|numeric',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240', // máximo 10MB
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'hectareas_malla' => 'nullable|numeric',
            'cierre_perimetral' => 'nullable',
        ]);

        $validated['es_propietario'] = $request->has('es_propietario') ? 1 : 0;
        $validated['malla'] = $request->has('malla') ? 1 : 0;
        $validated['derecho_riego'] = $request->has('derecho_riego') ? 1 : 0;
        $validated['rut'] = $request->has('rut') ? 1 : 0;
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral') ? 1 : 0;
        $validated['usuario_id'] = Auth::id();

        // Manejar el archivo PDF si se subió uno
        if ($request->hasFile('rut_archivo_file')) {
            $path = $request->file('rut_archivo_file')->store('rut_files', 'public');
            $validated['rut_archivo'] = $path;
        }

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
            'direccion' => 'required|string|max:255',
            'hectareas' => 'sometimes|numeric|min:0',
            'es_propietario' => 'nullable',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',
            'rut' => 'nullable',
            'rut_valor' => 'nullable|numeric',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240', // máximo 10MB
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'hectareas_malla' => 'nullable|numeric',
            'cierre_perimetral' => 'nullable',
        ]);

        $validated['es_propietario'] = $request->has('es_propietario') ? 1 : 0;
        $validated['malla'] = $request->has('malla') ? 1 : 0;
        $validated['derecho_riego'] = $request->has('derecho_riego') ? 1 : 0;
        $validated['rut'] = $request->has('rut') ? 1 : 0;
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral') ? 1 : 0;

        // Manejar el archivo PDF si se subió uno nuevo
        if ($request->hasFile('rut_archivo_file')) {
            // Eliminar el archivo anterior si existe
            if ($propiedad->rut_archivo) {
                Storage::disk('public')->delete($propiedad->rut_archivo);
            }
            
            // Guardar el nuevo archivo
            $path = $request->file('rut_archivo_file')->store('rut_files', 'public');
            $validated['rut_archivo'] = $path;
        }

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
