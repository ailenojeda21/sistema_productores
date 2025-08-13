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

                <!-- Checkbox comercio feria -->
                <div class="flex items-center mt-6">
                    <input type="checkbox"
                           name="comercio_feria"
                           id="comercio_feria"
                           class="mr-2 custom-checkbox"
                           {{ old('comercio_feria', $comercio->comercio_feria) ? 'checked' : '' }}>
                    <label for="comercio_feria">¿Vende en feria?</label>
                </div>

                <div id="feria-fields" class="hidden md:col-span-2">
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="nombre_feria">Seleccione la feria</label>
                        <select id="nombre_feria" name="nombre_feria" class="w-full p-2 border border-gray-300 rounded">
                            <option value="">Seleccione una feria...</option>
                            <option value="Mercado Cooperativo Guaymallen" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercado Cooperativo Guaymallen' ? 'selected' : '' }}>Mercado Cooperativo Guaymallen</option>
                            <option value="Mercado Cooperativo Acceso Este" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercado Cooperativo Acceso Este' ? 'selected' : '' }}>Mercado Cooperativo Acceso Este</option>
                            <option value="Mercado Cooperativo Las Heras" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercado Cooperativo Las Heras' ? 'selected' : '' }}>Mercado Cooperativo Las Heras</option>
                            <option value="Mercado Concentrador de Godoy Cruz" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercado Concentrador de Godoy Cruz' ? 'selected' : '' }}>Mercado Concentrador de Godoy Cruz</option>
                            <option value="Mercado Cooperativo Colonia Bombal" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercado Cooperativo Colonia Bombal' ? 'selected' : '' }}>Mercado Cooperativo Colonia Bombal</option>
                            <option value="Mercados Nacionales o Internacionales" {{ old('nombre_feria', $comercio->nombre_feria ?? '') == 'Mercados Nacionales o Internacionales' ? 'selected' : '' }}>Mercados Nacionales o Internacionales</option>
                        </select>
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
