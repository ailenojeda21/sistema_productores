@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Propiedad</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('propiedades.update', $propiedad) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                {{-- Dirección --}}
                <div>
                    <label class="block font-semibold mb-1">Dirección</label>
                    <input name="direccion" type="text"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('direccion', $propiedad->direccion) }}">
                </div>

                {{-- Ubicación --}}
                <div>
                    <label class="block font-semibold mb-1">Ubicación</label>
                    <input type="hidden" name="lat" class="lat-input" value="{{ old('lat', $propiedad->lat) }}">
                    <input type="hidden" name="lng" class="lng-input" value="{{ old('lng', $propiedad->lng) }}">

                    <button type="button"
                        class="toggle-map-btn px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino transition">
                        Ver mapa
                    </button>

                    <div class="map-container hidden mt-4">
                        <div class="map-element w-full h-64 rounded border"></div>
                        <p class="text-sm text-gray-500 mt-2">
                            Coordenadas:
                            <span class="coordenadas-display font-semibold">
                                {{ $propiedad->lat ? $propiedad->lat.', '.$propiedad->lng : 'No seleccionadas' }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Hectáreas --}}
                <div>
                    <label class="block font-semibold mb-1">Hectáreas</label>
                    <input id="hectareas" name="hectareas" type="number" step="0.01"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('hectareas', $propiedad->hectareas) }}">
                </div>

                {{-- TENENCIA --}}
                <div>
                    <label class="block font-semibold mb-1">Tipo de tenencia</label>
                    <select name="tipo_tenencia" id="tipo_tenencia"
                        class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione...</option>
                        <option value="propietario" {{ old('tipo_tenencia', $propiedad->tipo_tenencia) == 'propietario' ? 'selected' : '' }}>Propietario</option>
                        <option value="arrendatario" {{ old('tipo_tenencia', $propiedad->tipo_tenencia) == 'arrendatario' ? 'selected' : '' }}>Arrendatario</option>
                        <option value="otros" {{ old('tipo_tenencia', $propiedad->tipo_tenencia) == 'otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>

                {{-- ESPECIFICAR TENENCIA --}}
                <div id="especificarTenenciaDiv" class="hidden md:col-span-2">
                    <label class="block font-semibold mb-1">Especificar tenencia</label>
                    <input type="text" name="especificar_tenencia"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('especificar_tenencia', $propiedad->especificar_tenencia) }}">
                </div>
            </div>

            {{-- CHECKBOXES --}}
            <div class="space-y-6">

                {{-- Derecho de riego --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="derecho_riego" name="derecho_riego"
                            class="mr-2 custom-checkbox"
                            {{ old('derecho_riego', $propiedad->derecho_riego) ? 'checked' : '' }}>
                        <label>¿Tiene derecho de riego?</label>
                    </div>

                    <div id="tipoDerechoRiegoDiv" class="hidden ml-7 mt-2">
                        <select name="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                            <option value="">Seleccione...</option>
                            <option value="Subterráneo" {{ $propiedad->tipo_derecho_riego == 'Subterráneo' ? 'selected' : '' }}>Subterráneo</option>
                            <option value="Superficial" {{ $propiedad->tipo_derecho_riego == 'Superficial' ? 'selected' : '' }}>Superficial</option>
                            <option value="Ambos" {{ $propiedad->tipo_derecho_riego == 'Ambos' ? 'selected' : '' }}>Ambos</option>
                        </select>
                    </div>
                </div>

                {{-- RUT --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="rut" name="rut"
                            class="mr-2 custom-checkbox"
                            {{ old('rut', $propiedad->rut) ? 'checked' : '' }}>
                        <label>¿Posee RUT?</label>
                    </div>

                    <div id="rutFields" class="hidden ml-7 mt-2">
                        <input name="rut_valor" type="number"
                            class="w-full p-2 border border-gray-300 rounded"
                            value="{{ old('rut_valor', $propiedad->rut_valor) }}">
                    </div>
                </div>

                {{-- Malla --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="malla" name="malla"
                            class="mr-2 custom-checkbox"
                            {{ old('malla', $propiedad->malla) ? 'checked' : '' }}>
                        <label>¿Tiene malla antigranizo?</label>
                    </div>

                    <div id="mallaFields" class="hidden ml-7 mt-2">
                        <input name="hectareas_malla" type="number" step="0.01"
                            class="w-full p-2 border rounded"
                            value="{{ old('hectareas_malla', $propiedad->hectareas_malla) }}">
                    </div>
                </div>

                {{-- Cierre --}}
                <div class="flex items-center">
                    <input type="checkbox" name="cierre_perimetral"
                        class="mr-2 custom-checkbox"
                        {{ old('cierre_perimetral', $propiedad->cierre_perimetral) ? 'checked' : '' }}>
                    <label>¿Tiene cierre perimetral?</label>
                </div>
            </div>

            <button type="submit"
                class="mt-8 w-full py-2 bg-azul-marino text-white font-bold rounded hover:bg-amarillo-claro hover:text-azul-marino transition">
                Guardar cambios
            </button>
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
window.onload = function() {
    document.getElementById('tipoDerechoRiegoDiv').style.display = document.getElementById('derecho_riego').checked ? 'block' : 'none';
    document.getElementById('rutFields').style.display = document.getElementById('rut').checked ? 'block' : 'none';
    document.getElementById('mallaFields').style.display = document.getElementById('malla').checked ? 'block' : 'none';
};
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoTenencia = document.getElementById('tipo_tenencia');
    const especificar = document.getElementById('especificarTenenciaDiv');
    const riego = document.getElementById('derecho_riego');
    const riegoDiv = document.getElementById('tipoDerechoRiegoDiv');
    const rut = document.getElementById('rut');
    const rutDiv = document.getElementById('rutFields');
    const malla = document.getElementById('malla');
    const mallaDiv = document.getElementById('mallaFields');

    const toggle = (check, div) => div.classList.toggle('hidden', !check.checked);

    tipoTenencia.addEventListener('change', () =>
        especificar.classList.toggle('hidden', tipoTenencia.value !== 'otros')
    );

    riego.addEventListener('change', () => toggle(riego, riegoDiv));
    rut.addEventListener('change', () => toggle(rut, rutDiv));
    malla.addEventListener('change', () => toggle(malla, mallaDiv));

    // init
    toggle(riego, riegoDiv);
    toggle(rut, rutDiv);
    toggle(malla, mallaDiv);
    especificar.classList.toggle('hidden', tipoTenencia.value !== 'otros');
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
