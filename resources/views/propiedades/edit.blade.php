@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Editar Propiedad</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('propiedades.update', $propiedad) }}">
            @csrf
            @method('PUT')
           
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="direccion">Dirección</label>
                <input id="direccion" name="direccion" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('direccion', $propiedad->direccion) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="ubicacion">Ubicación</label>
                <input id="ubicacion" name="ubicacion" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('ubicacion', $propiedad->ubicacion) }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas</label>
                <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas', $propiedad->hectareas) }}">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="es_propietario" id="es_propietario" class="mr-2" {{ old('es_propietario', $propiedad->es_propietario) ? 'checked' : '' }}>
                <label for="es_propietario">¿Es propietario?</label>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2" {{ old('derecho_riego', $propiedad->derecho_riego) ? 'checked' : '' }}>
                <label for="derecho_riego">¿Tiene derecho de riego?</label>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="rut" id="rut" class="mr-2" {{ old('rut', $propiedad->rut) ? 'checked' : '' }}>
                <label for="rut">¿Posee RUT?</label>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="malla" id="malla" class="mr-2" {{ old('malla', $propiedad->malla) ? 'checked' : '' }}>
                <label for="malla">¿Tiene malla antigranizo?</label>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="rut_valor">Valor RUT</label>
                <input id="rut_valor" name="rut_valor" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('rut_valor', $propiedad->rut_valor) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo">Archivo RUT (nombre o URL)</label>
                <input id="rut_archivo" name="rut_archivo" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('rut_archivo', $propiedad->rut_archivo) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas_malla', $propiedad->hectareas_malla) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="tecnologia_riego">Tecnología de riego</label>
                <input id="tecnologia_riego" name="tecnologia_riego" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('tecnologia_riego', $propiedad->tecnologia_riego) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="condicion_acceso">Condición de acceso</label>
                <input id="condicion_acceso" name="condicion_acceso" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('condicion_acceso', $propiedad->condicion_acceso) }}">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2" {{ old('cierre_perimetral', $propiedad->cierre_perimetral) ? 'checked' : '' }}>
                <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded">Guardar Cambios</button>
        </form>
    </div>
</div>
@endsection
