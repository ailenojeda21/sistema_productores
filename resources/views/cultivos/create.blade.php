@extends('layouts.dashboard')



@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Cultivos', 'route' => 'cultivos.index'],
        ['name' => 'Nuevo']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Nuevo Cultivo</h2>
        <form method="POST" action="{{ route('cultivos.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="propiedad_id">Propiedad</label>
                    <select id="propiedad_id" name="propiedad_id" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione propiedad</option>
                        @foreach($propiedades as $propiedad)
                        <option value="{{ $propiedad->id }}" data-hectareas="{{ $propiedad->hectareas }}">{{ $propiedad->direccion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione tipo</option>
                        <option value="Frutícola">Frutícola</option>
                        <option value="Hortícola">Hortícola</option>
                        <option value="Vitícola">Vitícola</option>
                        <option value="Olivícola">Olivícola</option>
                    </select>
                </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="manejo_cultivo">Manejo de Cultivos</label>
                        <select id="manejo_cultivo" name="manejo_cultivo" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="">Seleccione manejo</option>
                            <option value="Convencional">Convencional</option>
                            <option value="Agroecologico">Agroecológico</option>
                            <option value="Organico">Orgánico</option>
                        </select>
                    </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="estacion">Estación</label>
                    <select id="estacion" name="estacion" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione estación</option>
                        <option value="Verano">Verano</option>
                        <option value="Invierno">Invierno</option>
                        <option value="Otoño">Otoño</option>
                        <option value="Primavera">Primavera</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas Totales</label>
                    <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded transition-colors" required>
                    <p id="hectareas-hint" class="text-sm text-gray-500 mt-1">Seleccione una propiedad para ver disponibilidad</p>
                </div>
                <!-- Eliminado malla antigranizo -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="tecnologia_riego">Tecnología de riego</label>
                    <select id="tecnologia_riego" name="tecnologia_riego" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione tecnología</option>
                        <option value="Surco">Por Surco</option>
                        <option value="Inundación">Por Inundación</option>
                        <option value="Cimalco">Cimalco</option>
                        <option value="Manga">Manga</option>
                        <option value="Goteo">Goteo</option>
                        <option value="Aspersión">Aspersión</option>
                    </select>
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
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px #93c5fd;
    }
</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectProp = document.getElementById('propiedad_id');
    const hectareasInput = document.getElementById('hectareas');
    const hectareasHint = document.getElementById('hectareas-hint');

    if (!selectProp || !hectareasInput || !hectareasHint) return;

    async function syncMaxHectareas() {
        const propId = selectProp.value;

        if (!propId) {
            hectareasInput.max = '';
            hectareasInput.placeholder = '';
            hectareasInput.value = '';
            hectareasHint.textContent = 'Seleccione una propiedad para ver disponibilidad';
            return;
        }

        try {
            const url = "{{ route('cultivos.hectareas-disponibles') }}";
            const response = await fetch(`${url}?propiedad_id=${propId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response error:', response.status, errorText);
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || 'Error al obtener hectáreas disponibles');
            }

            const data = await response.json();
            const maxVal = parseFloat(data.hectareas_disponibles);

            hectareasInput.max = maxVal || '';
            hectareasInput.placeholder = maxVal ? `Máximo ${maxVal.toFixed(2)} ha` : '';
            hectareasInput.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');

            const current = parseFloat(hectareasInput.value);
            if (maxVal !== null && current > maxVal) {
                hectareasInput.value = maxVal;
            }

            if (maxVal !== null) {
                if (maxVal <= 0) {
                    hectareasInput.classList.add('border-red-500');
                    hectareasHint.className = 'text-sm text-red-600 mt-1 font-semibold';
                    hectareasHint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">error</span> Total: ${data.hectareas_totales} ha | No hay hectáreas disponibles`;
                } else {
                    hectareasInput.classList.add('border-green-500');
                    hectareasHint.className = 'text-sm text-green-600 mt-1 font-semibold';
                    hectareasHint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">check_circle</span> Total: ${data.hectareas_totales} ha | Disponibles: <span class="text-xl">${maxVal.toFixed(2)} ha</span>`;
                }
            }
        } catch (error) {
            console.error('Error:', error);
            hectareasInput.classList.remove('border-green-500', 'border-red-500');
            hectareasInput.classList.add('border-gray-300');
            hectareasHint.className = 'text-sm text-gray-500 mt-1';
            hectareasHint.textContent = 'Seleccione una propiedad para ver disponibilidad';
        }
    }

    selectProp.addEventListener('change', syncMaxHectareas);

    function validateHectareasValue() {
        const maxVal = parseFloat(hectareasInput.max);
        const current = parseFloat(hectareasInput.value);

        if (!isNaN(maxVal) && maxVal > 0) {
            if (!isNaN(current) && current > maxVal) {
                hectareasInput.classList.remove('border-green-500', 'border-gray-300');
                hectareasInput.classList.add('border-red-500');
                hectareasHint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">error</span> El valor ingresado (${current} ha) excede las hectáreas disponibles (${maxVal.toFixed(2)} ha)`;
                hectareasHint.className = 'text-sm text-red-600 mt-1 font-semibold';
            } else if (!isNaN(current)) {
                hectareasInput.classList.remove('border-red-500', 'border-gray-300');
                hectareasInput.classList.add('border-green-500');
                const remaining = maxVal - current;
                hectareasHint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">check_circle</span> Disponibles: <span class="text-xl">${remaining.toFixed(2)} ha</span> de ${maxVal.toFixed(2)} ha`;
                hectareasHint.className = 'text-sm text-green-600 mt-1 font-semibold';
            }
        }
    }

    hectareasInput.addEventListener('input', validateHectareasValue);
    syncMaxHectareas();
});
</script>
@endpush
<!-- Eliminado script malla antigranizo -->
@endsection