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

        <!-- Mapa preview -->
        @if($propiedad->lat && $propiedad->lng)
        <div class="mt-3">
            <div class="text-sm font-medium text-gray-600 mb-2">Ubicación:</div>
            <div id="map-preview-{{ $propiedad->id }}"
                 class="rounded border overflow-hidden"
                 style="height: 120px; width: 100%; position: relative;"
                 data-lat="{{ $propiedad->lat }}"
                 data-lng="{{ $propiedad->lng }}">
                <div class="loading-state flex items-center justify-center h-full text-gray-500 text-sm bg-gray-50">
                    <div class="text-center">
                        <div class="material-symbols-outlined text-2xl mb-1">location_on</div>
                        <div>Cargando mapa...</div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="mt-3">
            <div class="text-sm font-medium text-gray-600 mb-2">Ubicación:</div>
            <div class="rounded border bg-gray-200 flex items-center justify-center" style="height: 120px;">
                <div class="text-center text-gray-500 text-sm">
                    <div class="material-symbols-outlined text-2xl mb-1">location_off</div>
                    <div>Sin ubicación</div>
                </div>
            </div>
        </div>
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

                <!-- Mapa preview -->
                @if($propiedad->lat && $propiedad->lng)
                <div class="mt-3">
                    <div class="text-sm font-medium text-gray-600 mb-2">Ubicación:</div>
                    <div id="map-preview-{{ $propiedad->id }}-exp"
                         class="rounded border overflow-hidden"
                         style="height: 120px; width: 100%; position: relative;"
                         data-lat="{{ $propiedad->lat }}"
                         data-lng="{{ $propiedad->lng }}">
                        <div class="loading-state flex items-center justify-center h-full text-gray-500 text-sm bg-gray-50">
                            <div class="text-center">
                                <div class="material-symbols-outlined text-2xl mb-1">location_on</div>
                                <div>Cargando mapa...</div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-3">
                    <div class="text-sm font-medium text-gray-600 mb-2">Ubicación:</div>
                    <div class="rounded border bg-gray-200 flex items-center justify-center" style="height: 120px;">
                        <div class="text-center text-gray-500 text-sm">
                            <div class="material-symbols-outlined text-2xl mb-1">location_off</div>
                            <div>Sin ubicación</div>
                        </div>
                    </div>
                </div>
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
    console.log('Script de mapas preview móviles cargado');

    // Verificar que Leaflet esté disponible
    if (typeof L === 'undefined') {
        console.error('Leaflet no está disponible');
        return;
    }

    console.log('Leaflet disponible, inicializando mapas preview');

    // Función para inicializar un mapa preview
    function initializeMapPreview(mapDiv) {
        if (!mapDiv || mapDiv.dataset.initialized) {
            console.log('Mapa ya inicializado o contenedor inválido:', mapDiv?.id);
            return;
        }

        const lat = parseFloat(mapDiv.dataset.lat);
        const lng = parseFloat(mapDiv.dataset.lng);
        const mapId = mapDiv.id;

        console.log('Verificando coordenadas para mapa:', mapId);
        console.log('Lat:', mapDiv.dataset.lat, '-> parsed:', lat);
        console.log('Lng:', mapDiv.dataset.lng, '-> parsed:', lng);

        if (isNaN(lat) || isNaN(lng)) {
            console.error('Coordenadas inválidas para mapa:', mapId, 'lat:', lat, 'lng:', lng);
            showMapError(mapDiv, 'Coordenadas inválidas');
            return;
        }

        // Verificar rango válido de coordenadas
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            console.error('Coordenadas fuera de rango para mapa:', mapId, 'lat:', lat, 'lng:', lng);
            showMapError(mapDiv, 'Coordenadas fuera de rango');
            return;
        }

        console.log('Inicializando mapa preview:', mapId, 'con coordenadas válidas:', lat, lng);

        try {
            // Verificar que el contenedor tenga dimensiones
            const rect = mapDiv.getBoundingClientRect();
            console.log('Dimensiones del contenedor:', mapId, 'width:', rect.width, 'height:', rect.height);

            if (rect.width === 0 || rect.height === 0) {
                console.warn('Contenedor sin dimensiones, reintentando en 500ms:', mapId);
                setTimeout(() => initializeMapPreview(mapDiv), 500);
                return;
            }

            // Limpiar contenido anterior (remover loading state)
            mapDiv.innerHTML = '';

            // Crear el mapa con opciones optimizadas para preview
            console.log('Creando instancia de mapa para:', mapId);
            const map = L.map(mapId, {
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                tap: false,
                touchZoom: false,
                attributionControl: false,
                fadeAnimation: false,
                zoomAnimation: false
            });

            console.log('Mapa creado, configurando vista para:', mapId);
            map.setView([lat, lng], 16);

            // Agregar tile layer con manejo de errores
            console.log('Agregando tile layer para:', mapId);
            const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: false,
                maxZoom: 18,
                errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
            });

            tileLayer.on('tileerror', function(e) {
                console.warn('Error cargando tile para mapa:', mapId, e);
            });

            tileLayer.on('tileload', function() {
                console.log('Tile cargado exitosamente para mapa:', mapId);
            });

            tileLayer.addTo(map);

            // Agregar marker
            console.log('Agregando marker para:', mapId);
            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div class="w-3 h-3 bg-red-500 rounded-full border-2 border-white shadow-lg"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                })
            }).addTo(map);

            // Guardar referencia
            mapDiv._previewMap = map;
            mapDiv.dataset.initialized = '1';

            console.log('Mapa preview inicializado exitosamente:', mapId);

            // Forzar refresh inmediato y después de un delay
            map.invalidateSize();

            // Ocultar loading state después de inicialización
            const loadingState = mapDiv.querySelector('.loading-state');
            if (loadingState) {
                loadingState.style.display = 'none';
                console.log('Estado de carga ocultado para mapa:', mapId);
            }

            setTimeout(() => {
                if (mapDiv._previewMap) {
                    console.log('Refrescando mapa preview:', mapId);
                    mapDiv._previewMap.invalidateSize();

                    // Verificar si el mapa se renderizó correctamente
                    setTimeout(() => {
                        const mapContainer = mapDiv.querySelector('.leaflet-container');
                        if (mapContainer) {
                            console.log('Mapa renderizado correctamente:', mapId);
                        } else {
                            console.warn('Mapa no se renderizó correctamente:', mapId);
                        }
                    }, 500);
                }
            }, 100);

        } catch (error) {
            console.error('Error inicializando mapa preview:', mapId, error);
            showMapError(mapDiv, 'Error al cargar el mapa');
        }
    }

    // Función auxiliar para mostrar errores
    function showMapError(mapDiv, message) {
        mapDiv.innerHTML = `
            <div class="flex items-center justify-center h-full text-red-500 text-xs">
                <div class="text-center">
                    <div class="material-symbols-outlined text-lg mb-1">error</div>
                    <div>${message}</div>
                </div>
            </div>
        `;
    }

    // Usar Intersection Observer para inicializar mapas cuando sean visibles
    if ('IntersectionObserver' in window) {
        const mapObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const mapDiv = entry.target;
                    initializeMapPreview(mapDiv);
                    // Dejar de observar una vez inicializado
                    mapObserver.unobserve(mapDiv);
                }
            });
        }, {
            rootMargin: '50px' // Inicializar 50px antes de que sea visible
        });

        // Observar todos los contenedores de mapa preview
        document.querySelectorAll('[id^="map-preview-"]').forEach(mapDiv => {
            mapObserver.observe(mapDiv);
        });

        console.log('Intersection Observer configurado para mapas preview');
    } else {
        // Fallback para navegadores sin Intersection Observer
        console.log('Intersection Observer no disponible, inicializando todos los mapas');

        // Inicializar todos los mapas inmediatamente con delay escalonado
        document.querySelectorAll('[id^="map-preview-"]').forEach((mapDiv, index) => {
            setTimeout(() => initializeMapPreview(mapDiv), index * 200);
        });
    }

    console.log('Inicialización de mapas preview completada');
});
</script>

<style>
.custom-marker {
    pointer-events: none;
}

/* Estilos específicos para mapas preview */
[id^="map-preview-"] {
    background: #f8f9fa;
}

[id^="map-preview-"] .leaflet-container {
    background: #f8f9fa !important;
}

[id^="map-preview-"] .loading-state {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
}

[id^="map-preview-"] .leaflet-container {
    z-index: 1;
}
</style>
@endpush
