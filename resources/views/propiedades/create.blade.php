@extends('layouts.dashboard')


@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Propiedades', 'route' => 'propiedades.index'],
        ['name' => 'Nueva']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Nueva Propiedad</h2>
        <form method="POST" action="{{ route('propiedades.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="direccion">Dirección</label>
                    <input id="direccion" name="direccion" type="text" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Ubicación</label>
                    <input type="hidden" name="lat" class="lat-input" value="{{ old('lat', '') }}">
                    <input type="hidden" name="lng" class="lng-input" value="{{ old('lng', '') }}">
                    
                    <!-- Botón para mostrar/ocultar mapa -->
                    <button type="button" class="toggle-map-btn px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">
                        Ver Mapa
                    </button>
                    
                    <!-- Contenedor del mapa (inicialmente oculto) -->
                    <div class="map-container hidden mt-4">
                        <div class="map-element w-full h-64 rounded border"></div>
                        <p class="text-sm text-gray-500 mt-2">
                            Coordenadas seleccionadas:
                            <span class="coordenadas-display font-semibold">No seleccionadas</span>
                        </p>
                        <p class="text-sm text-gray-500">Haz click en el mapa para ubicar la propiedad. También puedes arrastrar el pin.</p>
                    </div>
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
                    <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 rounded-full custom-checkbox">
                    <label for="derecho_riego">¿Tiene derecho de riego?</label>
                </div>
                <div id="tipoDerechoRiegoDiv" class="hidden mt-2 md:col-span-2">
                    <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                    <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Subterráneo">Subterráneo</option>
                        <option value="Superficial">Superficial</option>
                        <option value="Ambos">Ambos</option>
                    </select>
                </div>
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
                        <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo_file">Adjuntar RUT (PDF, opcional)</label>
                        <input id="rut_archivo_file" name="rut_archivo_file" type="file" accept="application/pdf" class="w-full p-2 border border-gray-300 rounded">
                        @error('rut_archivo_file')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="malla" id="malla" class="mr-2 rounded-full custom-checkbox">
                    <label for="malla">¿Tiene malla antigranizo?</label>
                </div>
                 <div id="malla-fields" class="hidden md:col-span-2">
                     <div class="mt-2">
                         <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                         <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded transition-colors" required>
                         <p id="hectareas-malla-hint" class="text-sm text-gray-500 mt-1">Ingrese las hectáreas totales primero</p>
                     </div>
                 </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2 rounded-full custom-checkbox">
                    <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
                </div>
               <!-- Tipo de Tenencia -->
<div class="md:col-span-2 mt-6">
    <label class="block text-gray-700 font-semibold mb-3">
        Tipo de tenencia de la propiedad
    </label>

    <div class="space-y-3">
        <!-- Propietario -->
        <label class="flex items-center cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="propietario"
                   class="mr-3 tenencia-radio"
                   {{ old('tipo_tenencia') === 'propietario' ? 'checked' : '' }}>
            <span class="text-gray-700">Propietario</span>
        </label>

        <!-- Arrendatario -->
        <label class="flex items-center cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="arrendatario"
                   class="mr-3 tenencia-radio"
                   {{ old('tipo_tenencia') === 'arrendatario' ? 'checked' : '' }}>
            <span class="text-gray-700">Arrendatario</span>
        </label>

        <!-- Otro (input en la misma fila) -->
        <label class="flex items-center gap-4 cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="otros"
                   class="tenencia-radio"
                   {{ old('tipo_tenencia') === 'otros' ? 'checked' : '' }}>

            <span class="text-gray-700 whitespace-nowrap">Otro</span>

            <input type="text"
                   name="especificar_tenencia"
                   class="w-72 p-2 border border-gray-300 rounded text-sm
                          {{ old('tipo_tenencia') === 'otros' ? '' : 'hidden' }}"
                   placeholder="Especifique la condición"
                   value="{{ old('especificar_tenencia') }}">
        </label>
    </div>

    @error('tipo_tenencia')
        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
    @enderror

    @error('especificar_tenencia')
        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
    @enderror
</div>


            </div>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar</button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('.tenencia-radio');
    const inputOtro = document.querySelector('input[name="especificar_tenencia"]');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'otros') {
                inputOtro.classList.remove('hidden');
                inputOtro.focus();
            } else {
                inputOtro.classList.add('hidden');
                inputOtro.value = '';
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elementos para checkboxes y toggles
    const tenenciaRadios = document.querySelectorAll('.tenencia-radio');
    const otrosContainer = document.getElementById('otros-tenencia-container');
    const riego = document.getElementById('derecho_riego');
    const riegoDiv = document.getElementById('tipoDerechoRiegoDiv');
    const rut = document.getElementById('rut');
    const rutFields = document.getElementById('rut-fields');
    const malla = document.getElementById('malla');
    const mallaFields = document.getElementById('malla-fields');
    const hectareasInput = document.getElementById('hectareas');
    const hectareasMallaInput = document.getElementById('hectareas_malla');

    // Función toggle reutilizable
    const toggle = (check, div) => div.classList.toggle('hidden', !check.checked);

    // Función para toggle del campo "otros" en tenencia
    const toggleOtros = () => {
        const sel = document.querySelector('.tenencia-radio:checked');
        if (otrosContainer) {
            otrosContainer.classList.toggle('hidden', !sel || sel.value !== 'otros');
        }
    };

    // Función para validación de hectáreas con malla
    function syncHectareasMallaMax() {
        if (!hectareasMallaInput) return;

        const total = parseFloat(hectareasInput?.value) || 0;
        const current = parseFloat(hectareasMallaInput.value) || 0;
        const hint = document.getElementById('hectareas-malla-hint');

        if (!hint) return;

        hectareasMallaInput.max = total > 0 ? total : '';
        hectareasMallaInput.placeholder = total > 0 ? `Máximo ${total.toFixed(2)} ha` : '';
        hectareasMallaInput.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');

        if (total === 0) {
            hint.className = 'text-sm text-gray-500 mt-1';
            hint.textContent = 'Ingrese las hectáreas totales primero';
            hectareasMallaInput.classList.add('border-gray-300');
        } else if (current > total) {
            hectareasMallaInput.classList.add('border-red-500');
            hint.className = 'text-sm text-red-600 mt-1 font-semibold';
            hint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">error</span> El valor (${current} ha) excede las hectáreas totales (${total.toFixed(2)} ha)`;
            hectareasMallaInput.value = total;
        } else if (current > 0) {
            hectareasMallaInput.classList.add('border-green-500');
            hint.className = 'text-sm text-green-600 mt-1 font-semibold';
            hint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">check_circle</span> Válido: ${current} ha de ${total.toFixed(2)} ha disponibles`;
        } else {
            hint.className = 'text-sm text-blue-600 mt-1 font-semibold';
            hint.innerHTML = `<span class="material-symbols-outlined align-middle text-lg mr-1">info</span> Máximo disponible: ${total.toFixed(2)} ha`;
            hectareasMallaInput.classList.add('border-gray-300');
        }
    }

    // Configurar listeners
    tenenciaRadios.forEach(radio => radio.addEventListener('change', toggleOtros));

    if (riego && riegoDiv) {
        riego.addEventListener('change', () => toggle(riego, riegoDiv));
    }

    if (rut && rutFields) {
        rut.addEventListener('change', () => toggle(rut, rutFields));
    }

    if (malla && mallaFields) {
        malla.addEventListener('change', function() {
            toggle(malla, mallaFields);
            if (malla.checked) {
                syncHectareasMallaMax();
            } else {
                const hint = document.getElementById('hectareas-malla-hint');
                if (hint) {
                    hint.className = 'text-sm text-gray-500 mt-1';
                    hint.textContent = 'Seleccione "tiene malla antigranizo" primero';
                }
            }
        });
    }

    if (hectareasInput && hectareasMallaInput) {
        hectareasInput.addEventListener('input', syncHectareasMallaMax);
        hectareasMallaInput.addEventListener('input', syncHectareasMallaMax);
    }

    // Inicializar estados
    toggleOtros();
    if (riego && riegoDiv) toggle(riego, riegoDiv);
    if (rut && rutFields) toggle(rut, rutFields);
    if (malla && mallaFields) toggle(malla, mallaFields);

    // Inicializar validación de hectáreas con malla si el checkbox está marcado
    if (malla && malla.checked) {
        syncHectareasMallaMax();
    }

    // Inicializar validación general
    syncHectareasMallaMax();
});
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar cada botón de mapa por separado
    const toggleButtons = document.querySelectorAll('.toggle-map-btn');
    
    toggleButtons.forEach(function(toggleBtn) {
        let map, marker;
        let mapInitialized = false;
        
        const container = toggleBtn.closest('div').querySelector('.map-container');
        const mapElement = container.querySelector('.map-element');
        const latInput = toggleBtn.closest('div').querySelector('.lat-input');
        const lngInput = toggleBtn.closest('div').querySelector('.lng-input');
        const coordDisplay = container.querySelector('.coordenadas-display');
        
        // Toggle map visibility
        toggleBtn.addEventListener('click', function() {
            container.classList.toggle('hidden');
            
            if (!mapInitialized) {
                // Initialize map on first show
                const initialLat = parseFloat(latInput.value) || -31.5;
                const initialLng = parseFloat(lngInput.value) || -68.5;

                map = L.map(mapElement).setView([initialLat, initialLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // If we have coordinates, show marker
                if (latInput.value && lngInput.value) {
                    updateMarker(L.latLng(parseFloat(latInput.value), parseFloat(lngInput.value)));
                }

                // Try to get user location if no coordinates set
                if (!latInput.value && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        map.setView([pos.coords.latitude, pos.coords.longitude], 13);
                    });
                }

                // Click on map to set marker
                map.on('click', function(e) {
                    updateMarker(e.latlng);
                });

                mapInitialized = true;
            }
            
            // Force map resize when showing
            if (!container.classList.contains('hidden')) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            }
        });

        // Function to update marker and coordinates
        function updateMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, {draggable: true}).addTo(map);
                // Event for marker drag
                marker.on('dragend', function(e) {
                    updateMarker(e.target.getLatLng());
                });
            }
            
            // Update hidden fields and show coordinates
            latInput.value = latlng.lat.toFixed(7);
            lngInput.value = latlng.lng.toFixed(7);
            coordDisplay.textContent = latlng.lat.toFixed(7) + ', ' + latlng.lng.toFixed(7);
        }
    });
});
</script>
@endpush
