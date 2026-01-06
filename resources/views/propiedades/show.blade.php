@extends('layouts.dashboard')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Propiedades', 'route' => 'propiedades.index'],
        ['name' => 'Detalle']
    ]" />
    <div class="bg-white rounded-lg shadow p-4 md:p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Propiedad</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4"><strong>Dirección:</strong> {{ $propiedad->direccion }}</div>
            <div class="mb-4"><strong>Hectáreas:</strong> {{ number_format($propiedad->hectareas, 2, ',', '.') }}</div>
            
            @if($propiedad->lat && $propiedad->lng)
            <div class="mb-4 md:col-span-2">
                <strong>Coordenadas:</strong> 
                <span class="text-gray-600">{{ number_format($propiedad->lat, 7) }}, {{ number_format($propiedad->lng, 7) }}</span>
                
                <button type="button" id="toggleMapBtn" 
                    class="mt-2 px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow transition flex items-center gap-2">
                    <span class="material-symbols-outlined">map</span>
                    <span id="mapToggleText">Ver mapa</span>
                </button>
                
                <div id="mapContainer" class="hidden mt-4" 
                     data-lat="{{ $propiedad->lat }}" 
                     data-lng="{{ $propiedad->lng }}">
                    <div id="propiedadMap" class="w-full h-64 md:h-80 rounded border border-gray-300 shadow-sm"></div>
                </div>
            </div>
            @else
            <div class="mb-4 md:col-span-2">
                <strong>Coordenadas:</strong> 
                <span class="text-gray-500">No registradas</span>
            </div>
            @endif
            
            <div class="mb-4"><strong>Tipo tenencia:</strong> {{ ucfirst($propiedad->tipo_tenencia ?? '-') }}</div>
            @if($propiedad->tipo_tenencia === 'otros')
            <div class="mb-4"><strong>Especificar:</strong> {{ $propiedad->especificar_tenencia ?? '-' }}</div>
            @endif
            <div class="mb-4"><strong>Derecho riego:</strong> {{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</div>
            @if($propiedad->derecho_riego)
            <div class="mb-4"><strong>Tipo:</strong> {{ $propiedad->tipo_derecho_riego ?? '-' }}</div>
            @endif
            <div class="mb-4"><strong>RUT:</strong> {{ $propiedad->rut ? 'Sí' : 'No' }}</div>
            @if($propiedad->rut && $propiedad->rut_valor)
            <div class="mb-4"><strong>Nº RUT:</strong> {{ number_format($propiedad->rut_valor, 0, '', '') }}</div>
            @endif
            <div class="mb-4"><strong>Malla antigranizo:</strong> {{ $propiedad->malla ? 'Sí' : 'No' }}</div>
            @if($propiedad->malla && $propiedad->hectareas_malla)
            <div class="mb-4"><strong>Hectáreas malla:</strong> {{ number_format($propiedad->hectareas_malla, 2, '.', ',') }}</div>
            @endif
            <div class="mb-4"><strong>Cierre perimetral:</strong> {{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</div>
        </div>
        
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('propiedades.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 font-semibold shadow transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span>
                Volver
            </a>
            <a href="{{ route('propiedades.edit', $propiedad) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">edit</span>
                Editar
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM Content Loaded ===');
    
    const toggleBtn = document.getElementById('toggleMapBtn');
    const mapContainer = document.getElementById('mapContainer');
    const mapDiv = document.getElementById('propiedadMap');
    const toggleText = document.getElementById('mapToggleText');
    
    console.log('Elementos encontrados:', {
        toggleBtn: !!toggleBtn,
        mapContainer: !!mapContainer,
        mapDiv: !!mapDiv,
        toggleText: !!toggleText
    });
    
    let map = null;
    
    if (toggleBtn && mapContainer && mapDiv) {
        toggleBtn.addEventListener('click', function() {
            console.log('=== Botón clickeado ===');
            
            mapContainer.classList.toggle('hidden');
            const isVisible = !mapContainer.classList.contains('hidden');
            console.log('Mapa visible:', isVisible);
            
            if (toggleText) {
                toggleText.textContent = isVisible ? 'Ocultar mapa' : 'Ver mapa';
            }
            
            if (isVisible && map === null) {
                // Mostrar contenedor temporalmente para inicializar mapa
                const containerStyle = mapContainer.getAttribute('style');
                console.log('Style actual:', containerStyle);
                
                // Forzar dimensiones para inicialización
                mapDiv.style.position = 'absolute';
                mapDiv.style.visibility = 'hidden';
                mapDiv.style.display = 'block';
                
                const lat = parseFloat(mapContainer.dataset.lat);
                const lng = parseFloat(mapContainer.dataset.lng);
                
                console.log('Coordenadas:', lat, lng);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    try {
                        console.log('Inicializando Leaflet...');
                        map = L.map('propiedadMap', {
                            zoomControl: true,
                            scrollWheelZoom: true,
                            dragging: true
                        }).setView([lat, lng], 15);
                        
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        
                        L.marker([lat, lng]).addTo(map)
                            .bindPopup('Ubicación de la propiedad')
                            .openPopup();
                        
                        console.log('Mapa inicializado correctamente');
                        
                        // Restaurar estilos
                        mapDiv.style.position = '';
                        mapDiv.style.visibility = '';
                        mapDiv.style.display = '';
                        
                        // Invalidar tamaño
                        setTimeout(function() {
                            if (map) {
                                map.invalidateSize();
                                console.log('Mapa invalidado');
                            }
                        }, 100);
                    } catch (error) {
                        console.error('Error al inicializar mapa:', error);
                    }
                } else {
                    console.error('Coordenadas inválidas:', lat, lng);
                }
            }
            
            if (map !== null) {
                setTimeout(function() {
                    map.invalidateSize();
                    console.log('Mapa invalidado después de toggle');
                }, 300);
            }
        });
    } else {
        console.error('Elementos del mapa no encontrados');
    }
    
    // Log para verificar que L de Leaflet está disponible
    console.log('Leaflet disponible:', typeof L !== 'undefined');
});
</script>
@endpush
