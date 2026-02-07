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

            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',

            'rut' => 'nullable',
            'rut_valor' => 'nullable|numeric',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',

            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',

            'hectareas_malla' => 'nullable|numeric',
            'cierre_perimetral' => 'nullable',

            // ✅ NUEVO SISTEMA DE TENENCIA
            'tipo_tenencia' => 'required|in:propietario,arrendatario,otros',
            'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
        ], [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
        ]);

        // Checkboxes
        $validated['malla'] = $request->has('malla');
        $validated['derecho_riego'] = $request->has('derecho_riego');
        $validated['rut'] = $request->has('rut');
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral');

        $validated['usuario_id'] = Auth::id();

        // Archivo RUT
        if ($request->hasFile('rut_archivo_file')) {
            $validated['rut_archivo'] = $request->file('rut_archivo_file')
                ->store('rut_files', 'public');
        }

        Propiedad::create($validated);

        return redirect()->route('propiedades.index')
            ->with('success', 'Propiedad creada correctamente');
    }

    /**
     * Mostrar propiedad individual
     */
    public function show(string $id)
    {
        $propiedad = Propiedad::with(['usuario', 'maquinarias', 'cultivos'])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        return view('propiedades.show', compact('propiedad'));
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

    // =========================
    // VALIDACIÓN
    // =========================
    $validated = $request->validate([
        'direccion' => 'required|string|max:255',
        'hectareas' => 'sometimes|numeric|min:0',

        'malla' => 'nullable',
        'derecho_riego' => 'nullable',
        'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',

        'rut' => 'nullable',
        'rut_valor' => 'nullable|numeric',
        'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',

        'lat' => 'nullable|numeric|between:-90,90',
        'lng' => 'nullable|numeric|between:-180,180',

        'hectareas_malla' => 'nullable|numeric|lte:hectareas',
        'cierre_perimetral' => 'nullable',

        // TENENCIA
        'tipo_tenencia' => 'required|in:propietario,arrendatario,otros',
        'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
    ], [
        'especificar_tenencia.required_if' =>
            'Debe especificar la condición cuando selecciona "Otro".',
    ]);

    // =========================
    // LIMPIEZA DE TENENCIA
    // =========================
    if ($validated['tipo_tenencia'] !== 'otros') {
        $validated['especificar_tenencia'] = null;
    }

    // =========================
    // CHECKBOXES
    // =========================
    $validated['malla'] = $request->has('malla');
    $validated['derecho_riego'] = $request->has('derecho_riego');
    $validated['rut'] = $request->has('rut');
    $validated['cierre_perimetral'] = $request->has('cierre_perimetral');

    // =========================
    // MANEJO DE DERECHO DE RIEGO
    // =========================
    if (!$validated['derecho_riego']) {
        // Si se desmarca derecho de riego → limpiar tipo
        $validated['tipo_derecho_riego'] = null;
    }

    // =========================
    // MANEJO DE MALLA
    // =========================
    if (!$validated['malla']) {
        // Si se desmarca malla → limpiar hectáreas
        $validated['hectareas_malla'] = null;
    }

    // =========================
    // MANEJO DE RUT Y ARCHIVO
    // =========================
    if ($validated['rut']) {

        // Si RUT está marcado, pero no hay valor → limpiar
        if (!$request->filled('rut_valor')) {
            $validated['rut_valor'] = null;
        }

        // Subir nuevo archivo si existe
        if ($request->hasFile('rut_archivo_file')) {

            // Eliminar archivo anterior
            if ($propiedad->rut_archivo &&
                Storage::disk('public')->exists($propiedad->rut_archivo)) {
                Storage::disk('public')->delete($propiedad->rut_archivo);
            }

            $validated['rut_archivo'] =
                $request->file('rut_archivo_file')
                        ->store('rut_files', 'public');
        }

    } else {
        // Si se desmarca RUT → eliminar todo
        if ($propiedad->rut_archivo &&
            Storage::disk('public')->exists($propiedad->rut_archivo)) {
            Storage::disk('public')->delete($propiedad->rut_archivo);
        }

        $validated['rut_valor'] = null;
        $validated['rut_archivo'] = null;
    }

    // =========================
    // UPDATE FINAL
    // =========================
    $propiedad->update($validated);

    return redirect()
        ->route('propiedades.index')
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
