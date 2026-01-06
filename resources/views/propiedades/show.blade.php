@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Propiedades', 'route' => 'propiedades.index'],
        ['name' => 'Detalle']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Propiedad</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4"><strong>Ubicación:</strong> {{ $propiedad->ubicacion }}</div>
            <div class="mb-4"><strong>Dirección:</strong> {{ $propiedad->direccion }}</div>
            <div class="mb-4"><strong>Hectáreas:</strong> {{ number_format($propiedad->hectareas, 2, ',', '.') }}</div>
            
            <div class="mb-4">
                <strong>Coordenadas:</strong> 
                @if($propiedad->lat && $propiedad->lng)
                    {{ number_format($propiedad->lat, 7) }}, {{ number_format($propiedad->lng, 7) }}
                    
                    <div class="mt-2">
                        <button type="button" onclick="toggleMapShow(this)" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">
                            Ver mapa
                        </button>
                        
                        <div class="map-show-container hidden mt-4" 
                             data-lat="{{ $propiedad->lat }}" 
                             data-lng="{{ $propiedad->lng }}">
                            <div class="map-show-element w-full h-64 rounded border"></div>
                            <p class="text-sm text-gray-500 mt-2">
                                Ubicación de la propiedad
                            </p>
                        </div>
                    </div>
                @else
                    No registradas
                @endif
            </div>
            
            <div class="mb-4"><strong>¿Es propietario?:</strong> {{ $propiedad->es_propietario ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>¿Tiene derecho de riego?:</strong> {{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</div>
    @if($propiedad->derecho_riego)
    <div class="mb-4"><strong>Tipo de derecho de riego:</strong> {{ $propiedad->tipo_derecho_riego ?? '-' }}</div>
    @endif
            <div class="mb-4"><strong>¿RUT?:</strong> {{ $propiedad->rut ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>Nº RUT:</strong> {{ number_format($propiedad->rut_valor, 0, '', '') }}</div>
            <div class="mb-4"><strong>Adjunto RUT:</strong> {{ $propiedad->rut_archivo }}</div>
            <div class="mb-4"><strong>Malla antigranizo:</strong> {{ $propiedad->malla ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>Hectáreas con malla:</strong> {{ number_format($propiedad->hectareas_malla, 2, '.', ',') }}</div>
            <div class="mb-4"><strong>Cierre perimetral:</strong> {{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</div>
        </div>
        <a href="{{ route('propiedades.index') }}" class="mt-8 px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">Volver al listado</a>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Objeto global para almacenar mapas por elemento
const showMaps = new Map();

function toggleMapShow(button) {
    const parent = button.parentElement;
    const container = parent.querySelector('.map-show-container');
    const mapElement = container.querySelector('.map-show-element');
    
    // Toggle visibility
    container.classList.toggle('hidden');
    
    // Si el contenedor ahora es visible y el mapa no está inicializado
    if (!container.classList.contains('hidden')) {
        // Usar el elemento como clave para verificar si ya existe un mapa
        if (!showMaps.has(mapElement)) {
            const lat = parseFloat(container.dataset.lat);
            const lng = parseFloat(container.dataset.lng);
            
            console.log('Inicializando mapa show con:', lat, lng);
            
            const map = L.map(mapElement).setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([lat, lng]).addTo(map);
            
            // Guardar el mapa en el objeto global
            showMaps.set(mapElement, map);
        }
        
        // Resize del mapa existente
        setTimeout(() => {
            const map = showMaps.get(mapElement);
            if (map) {
                map.invalidateSize();
            }
        }, 100);
    }
}
</script>
@endpush
