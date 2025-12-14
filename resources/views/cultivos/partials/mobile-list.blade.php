<!-- Mobile Card List View -->
<div class="space-y-4">
    @php
        $firstTwo = $cultivos->take(2);
        $remaining = $cultivos->slice(2);
    @endphp

    <!-- Primeros 2 cultivos -->
    @foreach($firstTwo as $cultivo)
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-azul-marino text-lg">{{ $cultivo->nombre }}</h3>
                <p class="text-sm text-gray-500">{{ $cultivo->tipo }} - {{ $cultivo->estacion }}</p>
            </div>
            <span class="material-symbols-outlined text-gray-400">agriculture</span>
        </div>

        <!-- Detalles -->
        <div class="space-y-2 mb-4">
            @if(isset($cultivo->propiedad) && $cultivo->propiedad)
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Propiedad:</span>
                <span class="text-gray-800">{{ $cultivo->propiedad->ubicacion ?? '' }} {{ $cultivo->propiedad->direccion ?? '' }}</span>
            </div>
            @endif
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Hectáreas:</span>
                <span class="text-gray-800">{{ number_format($cultivo->hectareas, 2, '.', ',') }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Manejo:</span>
                <span class="text-gray-800">{{ $cultivo->manejo_cultivo }}</span>
            </div>
            @if($cultivo->tecnologia_riego)
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-32">Riego:</span>
                <span class="text-gray-800">{{ $cultivo->tecnologia_riego }}</span>
            </div>
            @endif
        </div>

        <!-- Acciones -->
        <div class="flex gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('cultivos.edit', $cultivo) }}" 
               class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">edit</span>
                Editar
            </a>
            <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar este cultivo?');" class="flex-1">
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
        <button onclick="toggleExpandCultivos()" 
                class="w-full px-4 py-3 bg-gray-50 text-azul-marino font-medium flex items-center justify-between">
            <span>Ver {{ $remaining->count() }} más</span>
            <span id="expand-icon-cultivos" class="material-symbols-outlined transition-transform">expand_more</span>
        </button>
        
        <div id="expandable-content-cultivos" class="hidden space-y-4 p-4 bg-gray-50">
            @foreach($remaining as $cultivo)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-azul-marino text-lg">{{ $cultivo->nombre }}</h3>
                        <p class="text-sm text-gray-500">{{ $cultivo->tipo }} - {{ $cultivo->estacion }}</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400">agriculture</span>
                </div>

                <div class="space-y-2 mb-4">
                    @if(isset($cultivo->propiedad) && $cultivo->propiedad)
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Propiedad:</span>
                        <span class="text-gray-800">{{ $cultivo->propiedad->ubicacion ?? '' }} {{ $cultivo->propiedad->direccion ?? '' }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Hectáreas:</span>
                        <span class="text-gray-800">{{ number_format($cultivo->hectareas, 2, '.', ',') }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Manejo:</span>
                        <span class="text-gray-800">{{ $cultivo->manejo_cultivo }}</span>
                    </div>
                    @if($cultivo->tecnologia_riego)
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-32">Riego:</span>
                        <span class="text-gray-800">{{ $cultivo->tecnologia_riego }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-200">
                    <a href="{{ route('cultivos.edit', $cultivo) }}" 
                       class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Editar
                    </a>
                    <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" 
                          onsubmit="return confirm('¿Seguro que deseas eliminar este cultivo?');" class="flex-1">
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
        function toggleExpandCultivos() {
            const content = document.getElementById('expandable-content-cultivos');
            const icon = document.getElementById('expand-icon-cultivos');
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    </script>
    @endif
</div>
