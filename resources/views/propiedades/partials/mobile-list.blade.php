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
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Propietario:</span>
                <span class="text-gray-800">{{ $propiedad->es_propietario ? 'Sí' : 'No' }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Derecho riego:</span>
                <span class="text-gray-800">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</span>
            </div>
            @if($propiedad->tipo_derecho_riego)
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Tipo:</span>
                <span class="text-gray-800">{{ $propiedad->tipo_derecho_riego }}</span>
            </div>
            @endif
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Malla:</span>
                <span class="text-gray-800">{{ $propiedad->malla ? 'Sí' : 'No' }}</span>
            </div>
            @if($propiedad->lat && $propiedad->lng)
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Ubicación:</span>
                <button onclick="showLocationModal({{ $propiedad->lat }}, {{ $propiedad->lng }})" 
                        class="text-blue-600 underline">Ver Mapa</button>
            </div>
            @endif
        </div>

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

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Propietario:</span>
                        <span class="text-gray-800">{{ $propiedad->es_propietario ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Derecho riego:</span>
                        <span class="text-gray-800">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</span>
                    </div>
                    @if($propiedad->tipo_derecho_riego)
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Tipo:</span>
                        <span class="text-gray-800">{{ $propiedad->tipo_derecho_riego }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Malla:</span>
                        <span class="text-gray-800">{{ $propiedad->malla ? 'Sí' : 'No' }}</span>
                    </div>
                    @if($propiedad->lat && $propiedad->lng)
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Ubicación:</span>
                        <button onclick="showLocationModal({{ $propiedad->lat }}, {{ $propiedad->lng }})" 
                                class="text-blue-600 underline">Ver Mapa</button>
                    </div>
                    @endif
                </div>

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
