@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Comercio</h2>

        <form method="POST" action="{{ route('comercios.update', $comercio) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center mt-6">
                    <input type="checkbox"
                           name="vende_en_finca"
                           id="vende_en_finca"
                           class="mr-2 custom-checkbox"
                           {{ old('vende_en_finca', $comercio->vende_en_finca) ? 'checked' : '' }}>
                    <label for="vende_en_finca">¿Vende en finca?</label>
                </div>
                <!-- Checkbox infraestructura -->
                <div class="flex items-center mt-6">
                    <input type="checkbox"
                           name="infraestructura_empaque"
                           id="infraestructura_empaque"
                           class="mr-2 custom-checkbox"
                           {{ old('infraestructura_empaque', $comercio->infraestructura_empaque) ? 'checked' : '' }}>
                    <label for="infraestructura_empaque">¿Tiene infraestructura de empaque?</label>
                </div>

                <!-- Checkbox comercio mercados -->
                <div class="flex items-center mt-6">
                    <input type="checkbox"
                           name="comercio_mercado"
                           id="comercio_mercado"
                           class="mr-2 custom-checkbox"
                           {{ old('comercio_mercado', $comercio->comercio_mercado) ? 'checked' : '' }}>
                    <label for="comercio_mercado">¿Vende en mercados?¿Cuál?</label>
                </div>

                <div id="mercado-fields" class="hidden md:col-span-2 mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Seleccione el mercado</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @php
                            $mercados = [
                                'mercado_guaymallen' => 'Mercado Cooperativo Guaymallen',
                                'mercado_acceso_este' => 'Mercado Cooperativo Acceso Este',
                                'mercado_las_heras' => 'Mercado Cooperativo Las Heras',
                                'mercado_godoy_cruz' => 'Mercado Concentrador de Godoy Cruz',
                                'mercado_colonia_bombal' => 'Mercado Cooperativo Colonia Bombal',
                                'mercados_nacionales_internacionales' => 'Mercados Nacionales o Internacionales',
                            ];
                            $selectedMercados = old('mercados', isset($comercio->mercados) ? (is_array($comercio->mercados) ? $comercio->mercados : json_decode($comercio->mercados, true)) : []);
                        @endphp
                        @foreach($mercados as $field => $label)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="mercados[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                    {{ is_array($selectedMercados) && in_array($label, $selectedMercados) ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            <button type="submit"
                    class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>

<style>
   .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem; /* cuadrado */
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px #93c5fd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mercadoCheckbox = document.getElementById('comercio_mercado');
        const mercadoFields = document.getElementById('mercado-fields');
        function toggleMercadoFields() {
            mercadoFields.classList.toggle('hidden', !mercadoCheckbox.checked);
        }
        mercadoCheckbox.addEventListener('change', toggleMercadoFields);
        toggleMercadoFields();
    });
</script>
@endsection
