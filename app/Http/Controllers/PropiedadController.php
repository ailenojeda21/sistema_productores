<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropiedadRequest;
use App\Http\Requests\UpdatePropiedadRequest;
use App\Models\Propiedad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function store(StorePropiedadRequest $request)
    {
        $this->authorize('create', Propiedad::class);

        $validated = $request->validated();

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

        $propiedad = new Propiedad;
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

    public function update(UpdatePropiedadRequest $request, string $id)
    {
        $propiedad = Propiedad::findOrFail($id);

        $this->authorize('update', $propiedad);

        $validated = $request->validated();

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

        // Defensa frente a path traversal en la ruta almacenada.
        $storedPath = (string) $propiedad->rut_archivo;
        if ($storedPath === '' || str_contains($storedPath, '..') || str_contains($storedPath, '/') || str_contains($storedPath, '\\')) {
            abort(404, 'Archivo no encontrado');
        }

        $ascii = static function (string $value): string {
            $value = Str::ascii(trim($value));
            $value = preg_replace('/[^A-Za-z0-9]+/', '_', $value);
            $value = trim((string) $value, '_');

            return $value === '' ? 'x' : $value;
        };

        $nombre = $ascii($propiedad->usuario->name ?? 'productor');
        $distrito = $ascii($propiedad->distrito ? ucwords(str_replace('-', ' ', $propiedad->distrito)) : '');
        $calle = $ascii($propiedad->calle ? ucwords(str_replace('-', ' ', $propiedad->calle)) : '');
        $numeracion = $ascii($propiedad->numeracion ?? '');
        $calleNumero = $numeracion !== 'x' ? "{$calle}_{$numeracion}" : $calle;
        $propStr = $distrito !== 'x' ? "{$distrito}_{$calleNumero}" : $calleNumero;
        $fecha = now()->format('Y-m-d');
        $filename = "{$nombre}_{$propStr}_rut_{$fecha}.pdf";
        $filename = preg_replace('/[^A-Za-z0-9_.-]/', '', $filename);

        return Storage::disk('rut_files')->download($propiedad->rut_archivo, $filename);
    }
}
