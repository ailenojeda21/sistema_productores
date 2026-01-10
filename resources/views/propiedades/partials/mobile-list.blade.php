<!-- Mobile Card List View -->
<div class="space-y-4">
    @php
        $firstTwo = $propiedades->take(2);
        $remaining = $propiedades->slice(2);
    @endphp

    <!-- Primeras 2 propiedades -->
    @foreach($firstTwo as $propiedad)
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-azul-marino text-lg">{{ $propiedad->direccion }}</h3>
                <p class="text-sm text-gray-500">{{ number_format($propiedad->hectareas, 2, ',', '.') }} hectáreas</p>
            </div>
            <span class="material-symbols-outlined text-gray-400">home</span>
        </div>

        <!-- Detalles -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="space-y-2">
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Tenencia:</span>
                    <span class="text-gray-800">{{ $propiedad->tipo_tenencia ? str_replace('_', ' ', $propiedad->tipo_tenencia) : 'No especificado' }}</span>
                </div>
                @if($propiedad->especificar_tenencia)
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Detalle:</span>
                    <span class="text-gray-800 text-xs">{{ $propiedad->especificar_tenencia }}</span>
                </div>
                @endif
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Derecho riego:</span>
                    <span class="text-gray-800">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</span>
                </div>
                @if($propiedad->tipo_derecho_riego)
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Tipo riego:</span>
                    <span class="text-gray-800">{{ $propiedad->tipo_derecho_riego }}</span>
                </div>
                @endif
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">RUT:</span>
                    <span class="text-gray-800">{{ $propiedad->rut ? 'Sí' : 'No' }}</span>
                </div>
            </div>
            <div class="space-y-2">
                @if($propiedad->rut_valor)
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Nº RUT:</span>
                    <span class="text-gray-800">{{ number_format($propiedad->rut_valor, 0, '', '') }}</span>
                </div>
                @endif
                @if($propiedad->rut_archivo)
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Adjunto RUT:</span>
                    <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-xs">Ver archivo</a>
                </div>
                @endif
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Malla:</span>
                    <span class="text-gray-800">{{ $propiedad->malla ? 'Sí' : 'No' }}</span>
                </div>
                @if($propiedad->hectareas_malla)
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Ha. con malla:</span>
                    <span class="text-gray-800">{{ number_format($propiedad->hectareas_malla, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex flex-col text-sm">
                    <span class="font-medium text-gray-600 text-xs">Cierre perimetral:</span>
                    <span class="text-gray-800">{{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</span>
                </div>
            </div>
        </div>

        <!-- Mapa inline -->
        @if($propiedad->lat && $propiedad->lng)
        <a href="#"
           class="ml-toggle-map text-blue-600 underline hover:text-blue-800"
           data-target="ml-map-{{ $propiedad->id }}">
            Ver mapa
        </a>

        <div id="ml-map-{{ $propiedad->id }}"
             class="hidden mt-2 rounded border"
             style="height: 220px;"
             data-lat="{{ $propiedad->lat }}"
             data-lng="{{ $propiedad->lng }}">
        </div>
        @else
        <span class="text-gray-500 text-sm">Sin ubicación</span>
        @endif

        <!-- Acciones -->
        <div class="flex gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('propiedades.edit', $propiedad) }}" 
               class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">edit</span>
                Editar
            </a>
            <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta propiedad?');" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full py-2 bg-red-500 text-white rounded font-medium flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    Eliminar
                </button>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Expandible para el resto -->
    @if($remaining->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <button onclick="toggleExpand()" 
                class="w-full px-4 py-3 bg-gray-50 text-azul-marino font-medium flex items-center justify-between">
            <span>Ver {{ $remaining->count() }} más</span>
            <span id="expand-icon" class="material-symbols-outlined transition-transform">expand_more</span>
        </button>
        
        <div id="expandable-content" class="hidden space-y-4 p-4 bg-gray-50">
            @foreach($remaining as $propiedad)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-azul-marino text-lg">{{ $propiedad->direccion }}</h3>
                        <p class="text-sm text-gray-500">{{ number_format($propiedad->hectareas, 2, ',', '.') }} hectáreas</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400">home</span>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="space-y-2">
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Tenencia:</span>
                            <span class="text-gray-800">{{ $propiedad->tipo_tenencia ? str_replace('_', ' ', $propiedad->tipo_tenencia) : 'No especificado' }}</span>
                        </div>
                        @if($propiedad->especificar_tenencia)
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Detalle:</span>
                            <span class="text-gray-800 text-xs">{{ $propiedad->especificar_tenencia }}</span>
                        </div>
                        @endif
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Derecho riego:</span>
                            <span class="text-gray-800">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</span>
                        </div>
                        @if($propiedad->tipo_derecho_riego)
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Tipo riego:</span>
                            <span class="text-gray-800">{{ $propiedad->tipo_derecho_riego }}</span>
                        </div>
                        @endif
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">RUT:</span>
                            <span class="text-gray-800">{{ $propiedad->rut ? 'Sí' : 'No' }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @if($propiedad->rut_valor)
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Nº RUT:</span>
                            <span class="text-gray-800">{{ number_format($propiedad->rut_valor, 0, '', '') }}</span>
                        </div>
                        @endif
                        @if($propiedad->rut_archivo)
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Adjunto RUT:</span>
                            <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-xs">Ver archivo</a>
                        </div>
                        @endif
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Malla:</span>
                            <span class="text-gray-800">{{ $propiedad->malla ? 'Sí' : 'No' }}</span>
                        </div>
                        @if($propiedad->hectareas_malla)
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Ha. con malla:</span>
                            <span class="text-gray-800">{{ number_format($propiedad->hectareas_malla, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex flex-col text-sm">
                            <span class="font-medium text-gray-600 text-xs">Cierre perimetral:</span>
                            <span class="text-gray-800">{{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mapa inline -->
                @if($propiedad->lat && $propiedad->lng)
                <a href="#"
                   class="ml-toggle-map text-blue-600 underline hover:text-blue-800"
                   data-target="ml-map-{{ $propiedad->id }}-exp">
                    Ver mapa
                </a>

                <div id="ml-map-{{ $propiedad->id }}-exp"
                     class="hidden mt-2 rounded border"
                     style="height: 220px;"
                     data-lat="{{ $propiedad->lat }}"
                     data-lng="{{ $propiedad->lng }}">
                </div>
                @else
                <span class="text-gray-500 text-sm">Sin ubicación</span>
                @endif

                <div class="flex gap-2 pt-3 border-t border-gray-200">
                    <a href="{{ route('propiedades.edit', $propiedad) }}" 
                       class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Editar
                    </a>
                    <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta propiedad?');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full py-2 bg-red-500 text-white rounded font-medium flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">delete</span>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleExpand() {
            const content = document.getElementById('expandable-content');
            const icon = document.getElementById('expand-icon');
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    </script>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.warn('Leaflet no está cargado, mapas no disponibles');
        return;
    }

    console.log('Inicializando toggle de mapas móviles');

    document.querySelectorAll('.ml-toggle-map').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const mapDiv = document.getElementById(targetId);

            if (!mapDiv) {
                console.error('Map container not found:', targetId);
                return;
            }

            const wasHidden = mapDiv.classList.contains('hidden');
            mapDiv.classList.toggle('hidden');
            const isNowVisible = !mapDiv.classList.contains('hidden');

            console.log('Toggle map:', targetId, 'was hidden:', wasHidden, 'now visible:', isNowVisible);

            // Verificar si el mapa ya fue inicializado por el script global
            let mapInstance = null;
            if (mapDiv._leaflet_id) {
                // El mapa ya fue inicializado por el script global, buscar la instancia
                mapInstance = Object.values(window).find(obj =>
                    obj && obj._leaflet_id === mapDiv._leaflet_id
                );
            }

            // Si no hay instancia guardada, intentar inicializar
            if (!mapInstance && !mapDiv.dataset.initialized) {
                console.log('Initializing map for:', targetId);
                const lat = parseFloat(mapDiv.dataset.lat);
                const lng = parseFloat(mapDiv.dataset.lng);

                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Invalid coordinates for map:', targetId, lat, lng);
                    return;
                }

                try {
                    mapInstance = L.map(targetId, {
                        zoomControl: false,
                        dragging: false,
                        scrollWheelZoom: false,
                        doubleClickZoom: false,
                        boxZoom: false,
                        keyboard: false,
                        tap: false,
                        touchZoom: false
                    }).setView([lat, lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(mapInstance);

                    L.marker([lat, lng]).addTo(mapInstance);

                    // Guardar instancia
                    mapDiv._mlMap = mapInstance;
                    mapDiv.dataset.initialized = '1';

                    console.log('Map initialized for:', targetId);
                } catch (error) {
                    console.error('Error initializing map:', error);
                    return;
                }
            }

            // Refrescar mapa cuando se hace visible
            if (isNowVisible && (mapInstance || mapDiv._mlMap)) {
                const map = mapInstance || mapDiv._mlMap;
                console.log('Refreshing map for:', targetId);

                // Usar múltiples timeouts para asegurar el refresh
                setTimeout(() => {
                    if (map && !mapDiv.classList.contains('hidden')) {
                        map.invalidateSize();
                        console.log('Map refreshed for:', targetId);
                    }
                }, 100);

                setTimeout(() => {
                    if (map && !mapDiv.classList.contains('hidden')) {
                        map.invalidateSize();
                    }
                }, 300);

                setTimeout(() => {
                    if (map && !mapDiv.classList.contains('hidden')) {
                        map.invalidateSize();
                    }
                }, 500);
            }
        });
    });

    console.log('Map toggle initialization complete');
});
</script>
@endpush
