
PRUEBA COPILOT CREATE
@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Nuevo Comercio</h2>
        <form method="POST" action="{{ route('comercios.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="vende_en_finca" id="vende_en_finca" class="mr-2 rounded-full custom-checkbox">
                    <label for="vende_en_finca">¿Vende en finca?</label>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="infraestructura_empaque" id="infraestructura_empaque" class="mr-2 rounded-full custom-checkbox">
                    <label for="infraestructura_empaque">¿Tiene infraestructura de empaque?</label>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="comercio_feria" id="comercio_feria" class="mr-2 rounded-full custom-checkbox">
                    <label for="comercio_feria">¿Vende en feria?</label>
                </div>

                <!-- Ferias -->
                <div id="feria-fields" class="hidden md:col-span-2 mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Seleccione la feria</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @php
                            $ferias = [
                                'mercado_guaymallen' => 'Mercado Cooperativo Guaymallen',
                                'mercado_acceso_este' => 'Mercado Cooperativo Acceso Este',
                                'mercado_las_heras' => 'Mercado Cooperativo Las Heras',
                                'mercado_godoy_cruz' => 'Mercado Concentrador de Godoy Cruz',
                                'mercado_colonia_bombal' => 'Mercado Cooperativo Colonia Bombal',
                                'mercados_nacionales_internacionales' => 'Mercados Nacionales o Internacionales',
                            ];
                        @endphp

                        @foreach($ferias as $field => $label)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="ferias[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                    {{ is_array(old('ferias')) && in_array($label, old('ferias')) ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">
                Guardar
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
        const feriaCheckbox = document.getElementById('comercio_feria');
        const feriaFields = document.getElementById('feria-fields');
        function toggleFeriaFields() {
            feriaFields.classList.toggle('hidden', !feriaCheckbox.checked);
        }
        feriaCheckbox.addEventListener('change', toggleFeriaFields);
        toggleFeriaFields();
    });
</script>
@endsection
