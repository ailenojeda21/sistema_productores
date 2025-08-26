@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Propiedad</h2>
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
                <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas', isset($propiedad) ? $propiedad->hectareas : '') }}">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="es_propietario" id="es_propietario" class="mr-2  custom-checkbox" {{ old('es_propietario', $propiedad->es_propietario) ? 'checked' : '' }}>
                <label for="es_propietario">¿Es propietario?</label>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 custom-checkbox" {{ old('derecho_riego', $propiedad->derecho_riego) ? 'checked' : '' }} onchange="document.getElementById('tipoDerechoRiegoDiv').style.display = this.checked ? 'block' : 'none';">
                <label for="derecho_riego">¿Tiene derecho de riego?</label>
            </div>
            <div id="tipoDerechoRiegoDiv" style="display:none;" class="mb-4">
                <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                    
                    <option value="">Seleccione...</option>
                    <option value="Subterráneo" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'subterráneo' ? 'selected' : '' }}>Subterráneo</option>
                    <option value="Superficial" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'superficial' ? 'selected' : '' }}>Superficial</option>
                    <option value="Ambos" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'ambos' ? 'selected' : '' }}>Ambos</option>
                </select>
            </div>
            <script>
            window.onload = function() {
                document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
            };
            </script>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="rut" id="rut" class="mr-2 custom-checkbox" {{ old('rut', $propiedad->rut) ? 'checked' : '' }} onchange="document.getElementById('rutFields').style.display = this.checked ? 'block' : 'none';">
                <label for="rut">¿Posee RUT?</label>
            </div>
            <div id="rutFields" style="display:none;">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="rut_valor">Nº RUT</label>
                    <input id="rut_valor" name="rut_valor" type="number" step="1" class="w-full p-2 border border-gray-300 rounded" value="{{ old('rut_valor', isset($propiedad) ? $propiedad->rut_valor : '') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo">Adjuntar RUT(Opcional)</label>
                    <input id="rut_archivo" name="rut_archivo" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('rut_archivo', $propiedad->rut_archivo) }}">
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="malla" id="malla" class="mr-2 custom-checkbox" {{ old('malla', $propiedad->malla) ? 'checked' : '' }} onchange="document.getElementById('mallaFields').style.display = this.checked ? 'block' : 'none';">
                <label for="malla">¿Tiene malla antigranizo?</label>
            </div>
            <div id="mallaFields" style="display:none;">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                    <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas_malla', isset($propiedad) ? $propiedad->hectareas_malla : '') }}">
                </div>
            </div>
            <script>
            window.onload = function() {
                document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
                document.getElementById('rutFields').style.display = document.getElementById('rut').checked ? 'block' : 'none';
                document.getElementById('mallaFields').style.display = document.getElementById('malla').checked ? 'block' : 'none';
            };
            </script>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2  custom-checkbox" {{ old('cierre_perimetral', $propiedad->cierre_perimetral) ? 'checked' : '' }}>
                <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
            </div>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar Cambios</button>
        <style>
            .custom-checkbox {
                width: 1.25rem;
                height: 1.25rem;
                border-radius: 9999px;
                border: 2px solid #cbd5e1;
                background: #fff;
                appearance: none;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
                box-shadow: 0 0 0 0 #2563eb;
                cursor: pointer;
            }
            .custom-checkbox:checked {
                background-color: #ea580c;
                border-color: #ea580c;
                box-shadow: 0 0 0 2px #fdba74;
            }
        </style>
        </form>
    </div>
</div>
@endsection
