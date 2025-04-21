<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use Illuminate\Support\Facades\Auth;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listar todos los archivos con relaciones
        $archivos = Archivo::with(['usuario', 'propiedad'])->get();
        return response()->json($archivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'nullable|exists:users,id',
            'propiedad_id' => 'nullable|exists:propiedades,id',
            'tipo_documento' => 'required|string|max:255',
            'ruta_archivo' => 'required|string|max:255',
            'fecha_subida' => 'required|date',
        ]);
        $archivo = Archivo::create($validated);
        return response()->json($archivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $archivo = Archivo::with(['usuario', 'propiedad'])->findOrFail($id);
        return response()->json($archivo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $archivo = Archivo::findOrFail($id);
        $validated = $request->validate([
            'usuario_id' => 'nullable|exists:users,id',
            'propiedad_id' => 'nullable|exists:propiedades,id',
            'tipo_documento' => 'sometimes|string|max:255',
            'ruta_archivo' => 'sometimes|string|max:255',
            'fecha_subida' => 'sometimes|date',
        ]);
        $archivo->update($validated);
        return response()->json($archivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $archivo = Archivo::findOrFail($id);
        $archivo->delete();
        return response()->json(['message' => 'Archivo eliminado correctamente']);
    }
}
