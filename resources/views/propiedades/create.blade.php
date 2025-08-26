@extends('layouts.dashboard')





@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Nueva Propiedad</h2>
        <form method="POST" action="{{ route('propiedades.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="direccion">Dirección</label>
                    <input id="direccion" name="direccion" type="text" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="ubicacion">Ubicación</label>
                    <input id="ubicacion" name="ubicacion" type="text" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas</label>
                    <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="es_propietario" id="es_propietario" class="mr-2 rounded-full custom-checkbox">
                    <label for="es_propietario">¿Es propietario?</label>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 rounded-full custom-checkbox" onchange="document.getElementById('tipoDerechoRiegoDiv').style.display = this.checked ? 'block' : 'none';">
                    <label for="derecho_riego">¿Tiene derecho de riego?</label>
                </div>
                <div id="tipoDerechoRiegoDiv" style="display:none;" class="mt-2 md:col-span-2">
                    <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                    <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Subterráneo">Subterráneo</option>
                        <option value="Superficial">Superficial</option>
                        <option value="Ambos">Ambos</option>
                    </select>
                </div>
                <script>
                window.onload = function() {
                    document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
                };
                </script>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="rut" id="rut" class="mr-2 rounded-full custom-checkbox">
                    <label for="rut">¿Posee RUT?</label>
                </div>
                <div id="rut-fields" class="hidden md:col-span-2">
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="rut_valor">Nº RUT</label>
                        <input id="rut_valor" name="rut_valor" type="number" step="1" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo">Adjuntar RUT(Opcional)</label>
                        <input id="rut_archivo" name="rut_archivo" type="text" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="malla" id="malla" class="mr-2 rounded-full custom-checkbox">
                    <label for="malla">¿Tiene malla antigranizo?</label>
                </div>
                <div id="malla-fields" class="hidden md:col-span-2">
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                        <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2 rounded-full custom-checkbox">
                    <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
                </div>
            </div>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar</button>
        </form>
    </div>
</div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rutCheckbox = document.getElementById('rut');
        const rutFields = document.getElementById('rut-fields');
        const mallaCheckbox = document.getElementById('malla');
        const mallaFields = document.getElementById('malla-fields');

        function toggleRutFields() {
            rutFields.classList.toggle('hidden', !rutCheckbox.checked);
        }
        function toggleMallaFields() {
            mallaFields.classList.toggle('hidden', !mallaCheckbox.checked);
        }
        rutCheckbox.addEventListener('change', toggleRutFields);
        mallaCheckbox.addEventListener('change', toggleMallaFields);
        // Inicializar estado
        toggleRutFields();
        toggleMallaFields();
    });
</script>
        </form>
    </div>
</div>
@endsection
