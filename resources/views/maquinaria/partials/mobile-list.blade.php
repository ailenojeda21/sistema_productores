<!-- Mobile Card List View -->
<div class="space-y-4">
    @php
        $firstTwo = $maquinarias->take(2);
        $remaining = $maquinarias->slice(2);
    @endphp

    <!-- Primeras 2 maquinarias -->
    @foreach($firstTwo as $maq)
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-azul-marino text-lg">
                    {{ $maq->propiedad->direccion_completa ?? 'Sin dirección' }}
                </h3>
                <p class="text-sm text-gray-500">
                   {{ $maq->tractor ? 'Tractor - ' . ($maq->modelo_tractor ?? '-') : '' }}
                </p>
            </div>
            <span class="material-symbols-outlined text-gray-400">precision_manufacturing</span>
        </div>

        <!-- Detalles -->
        <div class="space-y-2 mb-4">

            @if($maq->implementos_activos)
            <div class="text-sm">
                <span class="font-medium text-gray-600 block mb-2">Implementos:</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($maq->implementos_activos as $item)
                    <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        {{ $item }}
                    </span>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-sm">
                <span class="font-medium text-gray-600">Implementos:</span>
                <span class="text-gray-400 italic text-xs ml-2">Sin implementos</span>
            </div>
            @endif

        </div>

        <!-- Acciones -->
        <div class="flex flex-col gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('maquinaria.edit', $maq) }}" 
               class="w-full py-2.5 bg-yellow-500 text-white rounded-lg text-center font-medium flex items-center justify-center gap-2 hover:bg-yellow-600 transition">
                <span class="material-symbols-outlined text-lg">edit</span>
                Editar
            </a>

            <form action="{{ route('maquinaria.destroy', $maq) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta maquinaria?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full py-2.5 bg-red-500 text-white rounded-lg font-medium flex items-center justify-center gap-2 hover:bg-red-600 transition">
                    <span class="material-symbols-outlined text-lg">delete</span>
                    Eliminar
                </button>
            </form>
        </div>

    </div>
    @endforeach

    <!-- Expandible -->
    @if($remaining->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <button onclick="toggleExpandMaquinaria()" 
                class="w-full px-4 py-3 bg-gray-50 text-azul-marino font-medium flex items-center justify-between">
            <span>Ver {{ $remaining->count() }} más</span>
            <span id="expand-icon-maquinaria" class="material-symbols-outlined transition-transform">expand_more</span>
        </button>
        
        <div id="expandable-content-maquinaria" class="hidden space-y-4 p-4 bg-gray-50">
            @foreach($remaining as $maq)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">

                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-azul-marino text-lg">
                            {{ $maq->propiedad->direccion_completa ?? 'Sin dirección' }}
                        </h3>
                        <p class="text-sm text-gray-500">
                      {{ $maq->tractor ? 'Tractor - ' . ($maq->modelo_tractor ?? '-') : '' }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400">precision_manufacturing</span>
                </div>

                <div class="space-y-2 mb-4">

                    @if($maq->implementos_activos)
                    <div class="text-sm">
                        <span class="font-medium text-gray-600 block mb-2">Implementos:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($maq->implementos_activos as $item)
                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                {{ $item }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-sm">
                        <span class="font-medium text-gray-600">Implementos:</span>
                        <span class="text-gray-400 italic text-xs ml-2">Sin implementos</span>
                    </div>
                    @endif

                </div>

                <div class="flex flex-col gap-2 pt-3 border-t border-gray-200">
                    <a href="{{ route('maquinaria.edit', $maq) }}" 
                       class="w-full py-2.5 bg-yellow-500 text-white rounded-lg text-center font-medium flex items-center justify-center gap-2 hover:bg-yellow-600 transition">
                        <span class="material-symbols-outlined text-lg">edit</span>
                        Editar
                    </a>

                    <form action="{{ route('maquinaria.destroy', $maq) }}" method="POST" 
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta maquinaria?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full py-2.5 bg-red-500 text-white rounded-lg font-medium flex items-center justify-center gap-2 hover:bg-red-600 transition">
                            <span class="material-symbols-outlined text-lg">delete</span>
                            Eliminar
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleExpandMaquinaria() {
            const content = document.getElementById('expandable-content-maquinaria');
            const icon = document.getElementById('expand-icon-maquinaria');

            content.classList.toggle('hidden');

            icon.style.transform = content.classList.contains('hidden') 
                ? 'rotate(0deg)' 
                : 'rotate(180deg)';
        }
    </script>
    @endif
</div>