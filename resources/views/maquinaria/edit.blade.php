@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Maquinaria', 'route' => 'maquinaria.index'],
        ['name' => 'Editar']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-naranja-oscuro mb-6">Editar Maquinaria</h2>

        <form method="POST" action="{{ route('maquinaria.update', $maquinaria) }}">
            @csrf
            @method('PUT')

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Propiedad -->
            <div class="mb-4">
                <label for="propiedad_id" class="block text-gray-700 font-semibold mb-1">Propiedad asociada</label>
              <select name="propiedad_id" id="propiedad_id" class="w-full p-2 border border-gray-300 rounded">
    @foreach($propiedades as $propiedad)
        <option 
            value="{{ $propiedad->id }}" 
            {{ (old('propiedad_id', $maquinaria->propiedad_id) == $propiedad->id) ? 'selected' : '' }}>
            
            {{ $propiedad->direccion_completa }}
            
        </option>
    @endforeach
</select>
            </div>
            <!-- Tractor -->
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="tractor" id="tractor" class="mr-2 custom-checkbox"
                        {{ old('tractor', $maquinaria->tractor) ? 'checked' : '' }}>
                    Tractor
                </label>
            </div>

            <!-- Modelo (solo visible si tractor = checked) -->
            <div class="mb-6" id="modelo_tractor_field" style="display: none;">
                <label for="modelo_tractor" class="block text-gray-700 font-semibold mb-1">Modelo (Año)</label>
                <input id="modelo_tractor" name="modelo_tractor" type="number" min="1900" max="{{ date('Y') }}"
                    class="w-full p-2 border border-gray-300 rounded"
                    value="{{ old('modelo_tractor', $maquinaria->modelo_tractor) }}">
            </div>

            <!-- Implementos -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Implementos / Accesorios</h3>
                <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                    @foreach(\App\Models\Maquinaria::getImplementosForForm() as $field => $label)
                        <div class="flex items-center">
                            <input type="checkbox" name="{{ $field }}" id="{{ $field }}" class="custom-checkbox"
                                {{ old($field, $maquinaria->$field) ? 'checked' : '' }}>
                            <label for="{{ $field }}" class="ml-2 text-sm sm:text-base">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Botón -->
            <button type="submit"
                class="w-full py-2 px-4 bg-[#F39200] hover:bg-[#E07F00] text-white font-bold rounded transition">
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
    const tractorCheckbox = document.getElementById('tractor');
    const modeloField = document.getElementById('modelo_tractor_field');
    const modeloInput = document.getElementById('modelo_tractor');

    function toggleModeloField() {
        const checked = tractorCheckbox.checked;
        modeloField.style.display = checked ? 'block' : 'none';
        if (checked) {
            modeloInput.required = true;
        } else {
            modeloInput.required = false;
            modeloInput.value = '';
        }
    }

    toggleModeloField();
    tractorCheckbox.addEventListener('change', toggleModeloField);

    if (modeloInput) {
        modeloInput.addEventListener('invalid', function() {
            if (this.validity.valueMissing) {
                this.setCustomValidity('Debe ingresar el año del tractor.');
            }
        });
        modeloInput.addEventListener('input', function() { this.setCustomValidity(''); });
    }
</script>
@endsection
