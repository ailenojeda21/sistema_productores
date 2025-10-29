@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Propiedad</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('propiedades.update', $propiedad) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="direccion">Dirección</label>
                    <input id="direccion" name="direccion" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('direccion', $propiedad->direccion) }}">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Ubicación</label>
                    <input type="hidden" name="lat" id="lat" value="{{ old('lat', $propiedad->lat) }}">
                    <input type="hidden" name="lng" id="lng" value="{{ old('lng', $propiedad->lng) }}">
                    
                    <!-- Botón para mostrar/ocultar mapa -->
                    <button type="button" id="toggleMap" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">
                        Ver mapa
                    </button>
                    
                    <!-- Contenedor del mapa (inicialmente oculto) -->
                    <div id="mapContainer" class="hidden mt-4">
                        <div id="map" class="w-full h-64 rounded border"></div>
                        <p class="text-sm text-gray-500 mt-2">
                            Coordenadas seleccionadas:
                            <span id="coordenadas" class="font-semibold">{{ $propiedad->lat ? $propiedad->lat . ', ' . $propiedad->lng : 'No seleccionadas' }}</span>
                        </p>
                        <p class="text-sm text-gray-500">Haz click en el mapa para ubicar la propiedad. También puedes arrastrar el pin.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas</label>
                    <input id="hectareas" name="hectareas" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas', isset($propiedad) ? $propiedad->hectareas : '') }}">
                </div>

                <!-- Checkboxes básicos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="es_propietario" id="es_propietario" class="mr-2 custom-checkbox" {{ old('es_propietario', $propiedad->es_propietario) ? 'checked' : '' }}>
                        <label for="es_propietario">¿Es propietario?</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2 custom-checkbox" {{ old('cierre_perimetral', $propiedad->cierre_perimetral) ? 'checked' : '' }}>
                        <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
                    </div>
                </div>

                <!-- Derecho de Riego -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 custom-checkbox" {{ old('derecho_riego', $propiedad->derecho_riego) ? 'checked' : '' }} onchange="document.getElementById('tipoDerechoRiegoDiv').style.display = this.checked ? 'block' : 'none';">
                        <label for="derecho_riego">¿Tiene derecho de riego?</label>
                    </div>
                    <div id="tipoDerechoRiegoDiv" style="display:none;" class="ml-7">
                        <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                        <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                            <option value="">Seleccione...</option>
                            <option value="Subterráneo" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'subterráneo' ? 'selected' : '' }}>Subterráneo</option>
                            <option value="Superficial" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'superficial' ? 'selected' : '' }}>Superficial</option>
                            <option value="Ambos" {{ old('tipo_derecho_riego', $propiedad->tipo_derecho_riego) == 'ambos' ? 'selected' : '' }}>Ambos</option>
                        </select>
                    </div>
                </div>

                <!-- RUT -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="rut" id="rut" class="mr-2 custom-checkbox" {{ old('rut', $propiedad->rut) ? 'checked' : '' }} onchange="document.getElementById('rutFields').style.display = this.checked ? 'block' : 'none';">
                        <label for="rut">¿Posee RUT?</label>
                    </div>
                    <div id="rutFields" style="display:none;" class="ml-7 space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="rut_valor">Nº RUT</label>
                            <input id="rut_valor" name="rut_valor" type="number" step="1" class="w-full p-2 border border-gray-300 rounded" value="{{ old('rut_valor', isset($propiedad) ? $propiedad->rut_valor : '') }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo_file">Adjuntar RUT (PDF, opcional)</label>
                            <input id="rut_archivo_file" name="rut_archivo_file" type="file" accept="application/pdf" class="w-full p-2 border border-gray-300 rounded">
                            @error('rut_archivo_file')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                            @if($propiedad->rut_archivo)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Ver RUT actual</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Malla Antigranizo -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="malla" id="malla" class="mr-2 custom-checkbox" {{ old('malla', $propiedad->malla) ? 'checked' : '' }} onchange="document.getElementById('mallaFields').style.display = this.checked ? 'block' : 'none';">
                        <label for="malla">¿Tiene malla antigranizo?</label>
                    </div>
                    <div id="mallaFields" style="display:none;" class="ml-7">
                        <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                        <input id="hectareas_malla" name="hectareas_malla" type="number" step="0.01" class="w-full p-2 border border-gray-300 rounded" value="{{ old('hectareas_malla', isset($propiedad) ? $propiedad->hectareas_malla : '') }}">
                    </div>
                </div>
            </div>

            <script>
            window.onload = function() {
                document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
                document.getElementById('rutFields').style.display = document.getElementById('rut').checked ? 'block' : 'none';
                document.getElementById('mallaFields').style.display = document.getElementById('malla').checked ? 'block' : 'none';
            };
            </script>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar Cambios</button>
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
    <!-- Leaflet CSS/JS y script para el mapa (edit) -->
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
