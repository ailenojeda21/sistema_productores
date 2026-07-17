@extends('layouts.dashboard')



@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Cultivos', 'route' => 'cultivos.index'],
        ['name' => 'Nuevo']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-naranja-oscuro mb-6">Nuevo Cultivo</h2>
        <form method="POST" action="{{ route('cultivos.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
                    <label class="block text-gray-700 font-semibold mb-1" for="propiedad_id">Propiedad</label>
                   <select id="propiedad_id" name="propiedad_id" class="w-full p-2 border border-gray-300 rounded" required>
                   <option value="">Seleccione propiedad</option>
                   @foreach($propiedades as $propiedad)
                       <option value="{{ $propiedad->id }}"  data-hectareas="{{ $propiedad->hectareas }}">
                           {{ $propiedad->direccion_completa }}
                       </option>
                   @endforeach
               </select>
               </div>
               <div>
                   <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                   <select id="tipo" name="tipo" class="w-full p-2 border border-gray-300 rounded" required>
                       <option value="">Seleccione tipo</option>
                       @foreach(\App\Models\Cultivo::getTiposForForm() as $value => $label)
                           <option value="{{ $value }}" {{ old('tipo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                       @endforeach
                   </select>
               </div>
               <div>
                   <label class="block text-gray-700 font-semibold mb-1" for="variedad">Variedad</label>
                   <select id="variedad" name="variedad" class="w-full p-2 border border-gray-300 rounded" required>
                       <option value="">Seleccione variedad</option>
                   </select>
               </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="manejo_cultivo">Manejo de Cultivos</label>
                        <select id="manejo_cultivo" name="manejo_cultivo" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="">Seleccione manejo</option>
                            @foreach(\App\Models\Cultivo::getManejoOptionsForForm() as $value => $label)
                                <option value="{{ $value }}" {{ old('manejo_cultivo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="estacion">Estación</label>
                    <select id="estacion" name="estacion" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione estación</option>
                        @foreach(\App\Models\Cultivo::getEstacionesForForm() as $value => $label)
                            <option value="{{ $value }}" {{ old('estacion') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas Totales</label>
                    <input id="hectareas" name="hectareas" type="text" inputmode="decimal" class="w-full p-2 border border-gray-300 rounded transition-colors" required>
                    <p id="hectareas-hint" class="text-sm text-gray-500 mt-1">Seleccione una propiedad para ver disponibilidad</p>
                </div>
                <!-- Eliminado malla antigranizo -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="tecnologia_riego">Tecnología de riego</label>
                    <select id="tecnologia_riego" name="tecnologia_riego" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione tecnología</option>
                        @foreach(\App\Models\Cultivo::getTecnologiaRiegoForForm() as $value => $label)
                            <option value="{{ $value }}" {{ old('tecnologia_riego') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            
            </div>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-[#F39200] hover:bg-[#E07F00] text-white font-bold rounded transition">Guardar</button>
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const propSelect = document.getElementById('propiedad_id');
    const haInput = document.getElementById('hectareas');
    const hint = document.getElementById('hectareas-hint');

    if (!propSelect || !haInput || !hint) return;

    const toNum = (v) => { const n = Number(String(v).replace(',', '.')); return isNaN(n) ? 0 : n; };

    let disponible = null;
    let total = 0;

    const setHint = (type, msg) => {
        hint.className = `text-sm text-${type}-600 mt-1 font-semibold`;
        hint.textContent = msg;
    };

    const setBorder = (color) => {
        haInput.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');
        if (color) haInput.classList.add(`border-${color}-500`);
    };

    const actualizarUI = () => {
        if (!propSelect.value || disponible === null) {
            setBorder('gray');
            setHint('gray', 'Seleccione una propiedad para ver disponibilidad');
            return;
        }
        const current = toNum(haInput.value);
        if (disponible <= 0) {
            setBorder('red');
            setHint('red', `Total: ${total} ha | No hay hectáreas disponibles`);
            return;
        }
        if (current > disponible) {
            setBorder('red');
            setHint('red', `El valor (${current} ha) excede lo disponible (${disponible} ha)`);
        } else if (current > 0) {
            setBorder('green');
            const rest = disponible - current;
            setHint('green', `Disponibles: ${rest.toFixed(2)} ha de ${disponible.toFixed(2)} ha`);
        } else {
            setBorder('gray');
            setHint('blue', `Máximo disponible: ${disponible.toFixed(2)} ha (Total propiedad: ${total.toFixed(2)} ha)`);
        }
    };

    async function fetchDisponibilidad() {
        const propId = propSelect.value;
        if (!propId) {
            disponible = null; total = 0;
            actualizarUI();
            return;
        }
        try {
            const url = "{{ route('cultivos.hectareas-disponibles') }}";
            const cultivoId = form?.dataset.cultivoId || '';
            const params = new URLSearchParams({ propiedad_id: propId });
            if (cultivoId) params.append('cultivo_id', cultivoId);

            const res = await fetch(`${url}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            if (!res.ok) throw new Error('Error al obtener disponibilidad');
            const data = await res.json();
            disponible = toNum(data.hectareas_disponibles);
            total = toNum(data.hectareas_totales);
        } catch (e) {
            console.error(e);
            disponible = 0; total = 0;
        }
        actualizarUI();
    }

    propSelect.addEventListener('change', fetchDisponibilidad);

    haInput.addEventListener('input', () => {
        if (!propSelect.value || disponible === null || disponible <= 0) return;
        const current = toNum(haInput.value);
        if (current > disponible) {
            setBorder('red');
            setHint('red', `El valor (${current} ha) excede lo disponible (${disponible} ha)`);
        } else if (current > 0) {
            setBorder('green');
            const rest = disponible - current;
            setHint('green', `Disponibles: ${rest.toFixed(2)} ha de ${disponible.toFixed(2)} ha`);
        } else {
            setBorder('gray');
            setHint('blue', `Máximo disponible: ${disponible.toFixed(2)} ha (Total propiedad: ${total.toFixed(2)} ha)`);
        }
    });

    haInput.addEventListener('blur', () => {
        if (!propSelect.value || disponible === null || disponible <= 0) return;
        const current = toNum(haInput.value);
        if (current > disponible) {
            haInput.value = disponible.toFixed(2);
            setBorder('green');
            setHint('green', `Disponibles: 0 ha de ${disponible.toFixed(2)} ha`);
        } else {
            actualizarUI();
        }
    });

    if (form) {
        form.addEventListener('submit', (e) => {
            actualizarUI();
            const current = toNum(haInput.value);
            if (disponible > 0 && current > disponible) {
                e.preventDefault();
                haInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                haInput.focus();
            }
        });
    }

    fetchDisponibilidad();

    // Dependent dropdown: Variedad changes based on Tipo
    const tipoSelect = document.getElementById('tipo');
    const variedadSelect = document.getElementById('variedad');

    const variedadesData = {
        'Hortícola': @json(\App\Models\Cultivo::VARIEDADES_HORTICOLA),
        'Vitícola': @json(\App\Models\Cultivo::VARIEDADES_VITICOLA),
        'Olivícola': @json(\App\Models\Cultivo::VARIEDADES_OLIVICOLA),
        'Frutícola': @json(\App\Models\Cultivo::VARIEDADES_FRUTICOLA),
    };

    function updateVariedadOptions() {
        const tipo = tipoSelect.value;
        const variedades = variedadesData[tipo] || {};
        const currentValue = variedadSelect.value;
        variedadSelect.innerHTML = '<option value="">Seleccione variedad</option>';
        for (const [value, label] of Object.entries(variedades)) {
            const opt = document.createElement('option');
            opt.value = value;
            opt.textContent = label;
            variedadSelect.appendChild(opt);
        }
        if (variedades[currentValue]) variedadSelect.value = currentValue;
    }

    if (tipoSelect && variedadSelect) {
        tipoSelect.addEventListener('change', updateVariedadOptions);
        updateVariedadOptions();
    }
});
</script>
@endpush
@endsection