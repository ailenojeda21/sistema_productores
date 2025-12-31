@extends('layouts.dashboard')



@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
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
                    <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded">
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
    if (!selectProp || !hectareasInput) return;

    function syncMaxHectareas() {
        const opt = selectProp.options[selectProp.selectedIndex];
        const maxVal = opt && opt.dataset.hectareas ? parseFloat(opt.dataset.hectareas) : 0;
        hectareasInput.max = maxVal || '';
        hectareasInput.placeholder = maxVal ? ('Máx ' + maxVal) : '';
        const current = parseFloat(hectareasInput.value);
        if (maxVal && current > maxVal) hectareasInput.value = maxVal;
    }

    selectProp.addEventListener('change', syncMaxHectareas);
    hectareasInput.addEventListener('input', syncMaxHectareas);
    syncMaxHectareas();
});
</script>
@endpush
<!-- Eliminado script malla antigranizo -->
@endsection