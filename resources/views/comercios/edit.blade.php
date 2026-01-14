@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Comercialización', 'route' => 'comercios.index'],
        ['name' => 'Editar']
    ]" />
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
                
                <div class="flex items-center mt-6">
                    <input type="checkbox"
                           name="infraestructura_empaque"
                           id="infraestructura_empaque"
                           class="mr-2 custom-checkbox"
                           {{ old('infraestructura_empaque', $comercio->infraestructura_empaque) ? 'checked' : '' }}>
                    <label for="infraestructura_empaque">¿Tiene infraestructura de empaque?</label>
                </div>

                <div class="flex items-center mt-6 md:col-span-2">
                    <input type="checkbox"
                           name="tiene_mercados"
                           id="tiene_mercados"
                           class="mr-2 custom-checkbox"
                           @php
                               $mercadosExistentes = isset($comercio->mercados) && (is_array($comercio->mercados) ? count($comercio->mercados) > 0 : count(json_decode($comercio->mercados, true)) > 0);
                           @endphp
                           {{ old('tiene_mercados', $mercadosExistentes) ? 'checked' : '' }}>
                    <label for="tiene_mercados">¿Vende en mercados?¿Cuál?</label>
                </div>
            </div>

            <div id="mercado-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione el mercado</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
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
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="mercados[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array($selectedMercados) && in_array($label, $selectedMercados) ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center mt-8">
                <input type="checkbox" name="tiene_cooperativas" id="tiene_cooperativas" class="mr-2 custom-checkbox"
                       @php
                           $cooperativasExistentes = isset($comercio->cooperativas) && (is_array($comercio->cooperativas) ? count($comercio->cooperativas) > 0 : count(json_decode($comercio->cooperativas, true)) > 0);
                       @endphp
                       {{ old('tiene_cooperativas', $cooperativasExistentes) ? 'checked' : '' }}>
                <label for="tiene_cooperativas">¿Comercializa en cooperativas?¿Cuál?</label>
            </div>

            <div id="cooperativa-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @php
                        $cooperativas = [
                            'cooperativa_nueva_california' => 'Coop. Nueva California',
                            'cooperativa_tulumaya' => 'Coop. Tulumaya',
                            'cooperativa_norte_mendocino' => 'Coop. Norte Mendocino',
                            'cooperativa_tres_de_mayo' => 'Coop. Tres de Mayo',
                            'cooperativa_altas_cumbres' => 'Coop. Altas Cumbres',
                            'cooperativa_tres_portenas' => 'Coop. Tres Porteñas',
                            'cooperativa_el_poniente' => 'Coop. El Poniente',
                            'cooperativa_pampanos_mendocinos' => 'Coop. Pámpanos Mendocinos',
                            'cooperativa_ingeniero_giagnoni' => 'Coop. Ingeniero Giagnoni',
                            'cooperativa_las_trincheras' => 'Coop. Las Trincheras',
                            'cooperativa_agricola_beltran' => 'Coop. Agrícola Beltrán',
                            'cooperativa_la_dormida' => 'Coop. La Dormida',
                            'cooperativa_del_algarrobal' => 'Coop. Del Algarrobal',
                            'cooperativa_el_libertador' => 'Coop. El Libertador',
                            'cooperativa_brindis' => 'Coop. Brindis',
                            'cooperativa_productores_junin' => 'Coop. Productores de Junín',
                            'cooperativa_colonia_california' => 'Coop. Colonia California',
                            'cooperativa_mendoza' => 'Coop. Mendoza',
                            'cooperativa_norte_lavallino' => 'Coop. Norte Lavallino',
                            'cooperativa_maipu' => 'Coop. Maipú',
                            'cooperativa_lacofut' => 'Coop. LACOFUT',
                        ];
                        $selectedCooperativas = old('cooperativas', isset($comercio->cooperativas) ? (is_array($comercio->cooperativas) ? $comercio->cooperativas : json_decode($comercio->cooperativas, true)) : []);
                    @endphp
                    @foreach($cooperativas as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array($selectedCooperativas) && in_array($label, $selectedCooperativas) ? 'checked' : '' }}>
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
        const mercadoCheckbox = document.getElementById('tiene_mercados');
        const mercadoFields = document.getElementById('mercado-fields');
        const cooperativaCheckbox = document.getElementById('tiene_cooperativas');
        const cooperativaFields = document.getElementById('cooperativa-fields');

        function toggleMercadoFields() {
            mercadoFields.classList.toggle('hidden', !mercadoCheckbox.checked);
        }

        function toggleCooperativaFields() {
            cooperativaFields.classList.toggle('hidden', !cooperativaCheckbox.checked);
        }

        mercadoCheckbox.addEventListener('change', toggleMercadoFields);
        cooperativaCheckbox.addEventListener('change', toggleCooperativaFields);

        toggleMercadoFields();
        toggleCooperativaFields();
    });
</script>
@endsection
