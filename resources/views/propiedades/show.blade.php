@extends('layouts.dashboard')

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
                
                <button type="button" onclick="toggleMapShow(this)" 
                    class="mt-2 px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition flex items-center gap-2">
                    <span class="material-symbols-outlined">map</span>
                    <span class="map-toggle-text">Ver mapa</span>
                </button>
                
                <div class="map-show-container hidden mt-4" 
                     data-lat="{{ $propiedad->lat }}" 
                     data-lng="{{ $propiedad->lng }}">
                    <div class="map-show-element w-full h-64 md:h-80 rounded border border-gray-300 shadow-sm"></div>
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
<script>
// Objeto global para almacenar mapas por elemento
const showMaps = new Map();

function toggleMapShow(button) {
    const container = button.closest('.mb-4, .mb-4\.md\:col-span-2').querySelector('.map-show-container');
    const mapElement = container.querySelector('.map-show-element');
    const toggleText = button.querySelector('.map-toggle-text');
    
    // Toggle visibility
    container.classList.toggle('hidden');
    
    const isVisible = !container.classList.contains('hidden');
    
    // Update button text
    if (toggleText) {
        toggleText.textContent = isVisible ? 'Ocultar mapa' : 'Ver mapa';
    }
    
    if (isVisible) {
        // Si el mapa no existe, crearlo
        if (!showMaps.has(mapElement)) {
            const lat = parseFloat(container.dataset.lat);
            const lng = parseFloat(container.dataset.lng);
            
            try {
                const map = L.map(mapElement, {
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
                
                showMaps.set(mapElement, map);
                console.log('Mapa inicializado en show:', lat, lng);
            } catch (error) {
                console.error('Error al inicializar mapa:', error);
            }
        }
        
        // Invalidar tamaño después de mostrar
        setTimeout(() => {
            const map = showMaps.get(mapElement);
            if (map) {
                map.invalidateSize();
                const lat = parseFloat(container.dataset.lat);
                const lng = parseFloat(container.dataset.lng);
                map.setView([lat, lng], 15);
            }
        }, 200);
    }
}
</script>
@endpush
