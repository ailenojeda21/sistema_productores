@extends('layouts.dashboard')


@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
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
                    <input type="hidden" name="lat" id="lat" value="{{ old('lat', '') }}">
                    <input type="hidden" name="lng" id="lng" value="{{ old('lng', '') }}">
                    
                    <!-- Botón para mostrar/ocultar mapa -->
                    <button type="button" id="toggleMap" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">
                        Ver Mapa
                    </button>
                    
                    <!-- Contenedor del mapa (inicialmente oculto) -->
                    <div id="mapContainer" class="hidden mt-4">
                        <div id="map" class="w-full h-64 rounded border"></div>
                        <p class="text-sm text-gray-500 mt-2">
                            Coordenadas seleccionadas:
                            <span id="coordenadas" class="font-semibold">No seleccionadas</span>
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
                    <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 rounded-full custom-checkbox" onchange="document.getElementById('tipoDerechoRiegoDiv').style.display = this.checked ? 'block' : 'none';">
                    <label for="derecho_riego">¿Tiene derecho de riego?</label>
                </div>
                <div id="tipoDerechoRiegoDiv" style="display:none;" class="mt-2 md:col-span-2">
                    <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                    <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Subterráneo">Subterráneo</option>
                        <option value="Superficial">Superficial</option>
                        <option value="Ambos">Ambos</option>
                    </select>
                </div>
                <script>
                window.onload = function() {
                    document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
                };
                </script>
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
                        <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2 rounded-full custom-checkbox">
                    <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
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
        const rutCheckbox = document.getElementById('rut');
        const rutFields = document.getElementById('rut-fields');
        const mallaCheckbox = document.getElementById('malla');
        const mallaFields = document.getElementById('malla-fields');

        function toggleRutFields() {
            rutFields.classList.toggle('hidden', !rutCheckbox.checked);
        }
        function toggleMallaFields() {
            mallaFields.classList.toggle('hidden', !mallaCheckbox.checked);
        }
        rutCheckbox.addEventListener('change', toggleRutFields);
        mallaCheckbox.addEventListener('change', toggleMallaFields);
        // Inicializar estado
        toggleRutFields();
        toggleMallaFields();
    });
</script>
    <!-- Leaflet CSS/JS y script para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let map, marker;
        let mapInitialized = false;

        // Toggle map visibility
        document.getElementById('toggleMap').addEventListener('click', function() {
            const mapContainer = document.getElementById('mapContainer');
            mapContainer.classList.toggle('hidden');
            
            if (!mapInitialized) {
                // Initialize map on first show
                const latInput = document.getElementById('lat');
                const lngInput = document.getElementById('lng');
                const initialLat = parseFloat(latInput.value) || -31.5;
                const initialLng = parseFloat(lngInput.value) || -68.5;

                map = L.map('map').setView([initialLat, initialLng], 13);
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
            if (!mapContainer.classList.contains('hidden')) {
                map.invalidateSize();
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
            document.getElementById('lat').value = latlng.lat.toFixed(7);
            document.getElementById('lng').value = latlng.lng.toFixed(7);
            document.getElementById('coordenadas').textContent = 
                latlng.lat.toFixed(7) + ', ' + latlng.lng.toFixed(7);
        }
    });
    </script>
        </form>
    </div>
</div>
@endsection
