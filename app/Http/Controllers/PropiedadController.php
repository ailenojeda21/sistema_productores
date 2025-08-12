<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('propiedades.create');
    }

    /**
     * Display a listing of the resource.
     * @OA\Get(
     *     path="/api/propiedades",
     *     summary="Listar propiedades",
     *     tags={"Propiedades"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de propiedades"
     *     )
     * )
     */
    public function index()
    {
        // Listar todas las propiedades con relaciones
        $propiedades = Propiedad::with(['usuario', 'archivos', 'maquinarias', 'cultivos'])->get();
        return view('propiedades.index', compact('propiedades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'ubicacion' => 'required|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'hectareas' => 'required|numeric|min:0',
            'es_propietario' => 'nullable',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
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
        $propiedad = Propiedad::create($validated);
        return redirect()->route('propiedades.show', $propiedad->id)->with('success', 'Propiedad creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $propiedad = Propiedad::with(['usuario', 'archivos', 'maquinarias', 'cultivos'])->findOrFail($id);
        return view('propiedades.show', compact('propiedad'));
    }

    /**
     * Update the specified resource in storage.
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
        return redirect()->route('propiedades.show', $propiedad->id)->with('success', 'Propiedad actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->delete();
        return redirect()->route('propiedades.index')->with('success', 'Propiedad eliminada correctamente');
    }
}