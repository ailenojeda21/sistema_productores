@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Comercialización', 'route' => 'comercios.index'],
        ['name' => 'Editar']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-naranja-oscuro mb-6">Editar Comercio</h2>

        <form method="POST" action="{{ route('comercios.update', $comercio) }}" id="comercio-form">
            @csrf
            @method('PUT')

            <div id="comercializacion-error" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                Debe seleccionar al menos una opción de comercialización: vende en finca, vende en mercados o comercializa en cooperativas.
            </div>
            @error('comercializacion')
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">{{ $message }}</div>
            @enderror
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
                                $mercadosExistentes = isset($comercio->mercados) && is_array($comercio->mercados) && count($comercio->mercados) > 0;
                            @endphp
                           {{ old('tiene_mercados', $mercadosExistentes) ? 'checked' : '' }}>
                    <label for="tiene_mercados">¿Vende en mercados?¿Cuál?</label>
                </div>
            </div>

            <div id="mercado-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione el mercado</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @php
                        $selectedMercados = old('mercados', $comercio->mercados ?? []);
                    @endphp
                    @foreach(\App\Models\Comercio::getMercadosForForm() as $field => $label)
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
                            $cooperativasExistentes = isset($comercio->cooperativas) && is_array($comercio->cooperativas) && count($comercio->cooperativas) > 0;
                        @endphp
                       {{ old('tiene_cooperativas', $cooperativasExistentes) ? 'checked' : '' }}>
                <label for="tiene_cooperativas">¿Comercializa en cooperativas?¿Cuál?</label>
            </div>

            <div id="cooperativa-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @php
                        $selectedCooperativas = old('cooperativas', $comercio->cooperativas ?? []);
                    @endphp
                    @foreach(\App\Models\Comercio::getCooperativasForForm() as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array($selectedCooperativas) && in_array($label, $selectedCooperativas) ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="mt-8 w-full py-2 px-4 bg-[#F39200] hover:bg-[#E07F00] text-white font-bold rounded transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>

<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        outline: none !important;
        outline-style: none !important;
        box-shadow: none !important;
        -webkit-tap-highlight-color: transparent;
        transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
        margin: 0;
    }
    .custom-checkbox:hover {
        border-color: #F39200 !important;
    }
    .custom-checkbox:focus {
        outline: none !important;
        outline-style: none !important;
        box-shadow: 0 0 0 3px rgba(243, 146, 0, 0.3) !important;
        border-color: #F39200 !important;
    }
    .custom-checkbox:focus-visible {
        outline: none !important;
        outline-style: none !important;
        box-shadow: 0 0 0 3px rgba(243, 146, 0, 0.4) !important;
        border-color: #F39200 !important;
    }
    .custom-checkbox:checked {
        background-color: #F39200 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:checked:hover {
        background-color: #D97706 !important;
        border-color: #D97706 !important;
    }
    .custom-checkbox:checked:focus {
        background-color: #F39200 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:active {
        background-color: #FCE7A3 !important;
        border-color: #F39200 !important;
        box-shadow: none !important;
    }
    .custom-checkbox:checked:active {
        background-color: #FCE7A3 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        border-color: #e5e7eb !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mercadoCheckbox = document.getElementById('tiene_mercados');
        const mercadoFields = document.getElementById('mercado-fields');
        const cooperativaCheckbox = document.getElementById('tiene_cooperativas');
        const cooperativaFields = document.getElementById('cooperativa-fields');
        const form = document.getElementById('comercio-form');
        const errorDiv = document.getElementById('comercializacion-error');

        function toggleMercadoFields() {
            mercadoFields.classList.toggle('hidden', !mercadoCheckbox.checked);
        }

        function toggleCooperativaFields() {
            cooperativaFields.classList.toggle('hidden', !cooperativaCheckbox.checked);
        }

        function validarComercializacion() {
            const vendeEnFinca = document.getElementById('vende_en_finca').checked;
            const tieneMercados = mercadoCheckbox.checked && mercadoFields.querySelectorAll('input[type="checkbox"]:checked').length > 0;
            const tieneCooperativas = cooperativaCheckbox.checked && cooperativaFields.querySelectorAll('input[type="checkbox"]:checked').length > 0;

            if (!vendeEnFinca && !tieneMercados && !tieneCooperativas) {
                errorDiv.classList.remove('hidden');
                return false;
            }
            errorDiv.classList.add('hidden');
            return true;
        }

        mercadoCheckbox.addEventListener('change', toggleMercadoFields);
        cooperativaCheckbox.addEventListener('change', toggleCooperativaFields);

        form.addEventListener('submit', function(e) {
            if (!validarComercializacion()) {
                e.preventDefault();
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        toggleMercadoFields();
        toggleCooperativaFields();
    });
</script>
@endsection
