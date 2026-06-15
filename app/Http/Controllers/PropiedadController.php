<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropiedadController extends Controller
{
    public function create()
    {
        $this->authorize('create', Propiedad::class);

        return view('propiedades.create');
    }

    public function index()
    {
        $this->authorize('viewAny', Propiedad::class);

        $propiedades = Propiedad::with(['usuario', 'maquinaria', 'cultivos'])
            ->where('usuario_id', Auth::id())
            ->get();

        return view('propiedades.index', compact('propiedades'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Propiedad::class);

        $validated = $request->validate([
            'calle' => 'required|string|max:255',
            'numeracion' => 'required|string|max:20',
            'distrito' => 'required|string|max:100',
            'hectareas' => 'required|numeric|min:0',

            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',

            'rut' => 'nullable',
            'rut_valor' => 'nullable|required_with:rut_archivo_file',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',

            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',

            'hectareas_malla' => 'nullable|numeric|lte:hectareas',
            'cierre_perimetral' => 'nullable',

            'tipo_tenencia' => 'required|in:propietario,arrendatario,otros',
            'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
        ], [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
            'rut_valor.required_with' => 'El número de RUT es obligatorio cuando se adjunta un archivo.',
        ]);

        $validated['malla'] = $request->has('malla');
        $validated['derecho_riego'] = $request->has('derecho_riego');
        $validated['rut'] = $request->has('rut');
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral');

        if ($request->hasFile('rut_archivo_file')) {
            $validated['rut_archivo'] = $request->file('rut_archivo_file')
                ->store('/', 'rut_files');
        }

        if (! $validated['rut']) {
            $validated['rut_valor'] = null;
            $validated['rut_archivo'] = null;
        }

        $propiedad = new Propiedad();
        $propiedad->fill($validated);
        $propiedad->usuario_id = Auth::id();
        $propiedad->save();

        return redirect()->route('propiedades.index')
            ->with('success', 'Propiedad creada correctamente');
    }

    public function show(string $id)
    {
        $propiedad = Propiedad::with(['usuario', 'maquinaria', 'cultivos'])->findOrFail($id);

        $this->authorize('view', $propiedad);

        return view('propiedades.show', compact('propiedad'));
    }

    public function edit(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);

        $this->authorize('update', $propiedad);

        return view('propiedades.edit', compact('propiedad'));
    }

    public function update(Request $request, string $id)
    {
        $propiedad = Propiedad::findOrFail($id);

        $this->authorize('update', $propiedad);

        $validated = $request->validate([
            'calle' => 'sometimes|required|string|max:255',
            'numeracion' => 'sometimes|required|string|max:20',
            'distrito' => 'sometimes|required|string|max:100',
            'hectareas' => 'sometimes|required|numeric|min:0',

            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',

            'rut' => 'nullable',
            'rut_valor' => 'nullable|required_with:rut_archivo_file',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',

            'lat' => 'sometimes|required|numeric|between:-90,90',
            'lng' => 'sometimes|required|numeric|between:-180,180',

            'hectareas_malla' => 'nullable|numeric|lte:hectareas',
            'cierre_perimetral' => 'nullable',

            'tipo_tenencia' => 'sometimes|required|in:propietario,arrendatario,otros',
            'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
        ], [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
            'rut_valor.required_with' => 'El número de RUT es obligatorio cuando se adjunta un archivo.',
        ]);

        if ($validated['tipo_tenencia'] !== 'otros') {
            $validated['especificar_tenencia'] = null;
        }

        $validated['malla'] = $request->has('malla');
        $validated['derecho_riego'] = $request->has('derecho_riego');
        $validated['rut'] = $request->has('rut');
        $validated['cierre_perimetral'] = $request->has('cierre_perimetral');

        if (! $validated['derecho_riego']) {
            $validated['tipo_derecho_riego'] = null;
        }

        if (! $validated['malla']) {
            $validated['hectareas_malla'] = null;
        }

        if ($validated['rut']) {

            if (! $request->filled('rut_valor')) {
                $validated['rut_valor'] = null;
            }

            if ($request->hasFile('rut_archivo_file')) {

                if ($propiedad->rut_archivo &&
                    Storage::disk('rut_files')->exists($propiedad->rut_archivo)) {
                    Storage::disk('rut_files')->delete($propiedad->rut_archivo);
                }

                $validated['rut_archivo'] =
                    $request->file('rut_archivo_file')
                        ->store('/', 'rut_files');
            }

        } else {
            if ($propiedad->rut_archivo &&
                Storage::disk('rut_files')->exists($propiedad->rut_archivo)) {
                Storage::disk('rut_files')->delete($propiedad->rut_archivo);
            }

            $validated['rut_valor'] = null;
            $validated['rut_archivo'] = null;
        }

        $propiedad->update($validated);

        return redirect()
            ->route('propiedades.index')
            ->with('success', 'Propiedad actualizada correctamente');
    }

    public function destroy(string $id)
    {
        $propiedad = Propiedad::findOrFail($id);

        $this->authorize('delete', $propiedad);

        if ($propiedad->rut_archivo &&
            Storage::disk('rut_files')->exists($propiedad->rut_archivo)) {
            Storage::disk('rut_files')->delete($propiedad->rut_archivo);
        }

        $propiedad->delete();

        return redirect()->route('propiedades.index')
            ->with('success', 'Propiedad eliminada correctamente');
    }

    public function downloadRut(Propiedad $propiedad)
    {
        if (Auth::guard('web')->guest() && Auth::guard('staff')->guest()) {
            abort(401);
        }

        if ($propiedad->usuario_id !== Auth::id() && ! Auth::guard('staff')->check()) {
            abort(403, 'No autorizado');
        }

        if (! $propiedad->rut_archivo ||
            ! Storage::disk('rut_files')->exists($propiedad->rut_archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        $nombre = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/', '', $propiedad->usuario->name ?? 'productor');
        $nombre = str_replace(' ', '_', trim($nombre));
        $fecha = now()->format('Y-m-d');
        $filename = "{$nombre}_rut_{$fecha}.pdf";

        return Storage::disk('rut_files')->download($propiedad->rut_archivo, $filename);
    }
}
