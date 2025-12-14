<!-- Mobile Card List View -->
<div class="space-y-4">
    @php
        $firstTwo = $comercios->take(2);
        $remaining = $comercios->slice(2);
    @endphp

    <!-- Primeros 2 comercios -->
    @foreach($firstTwo as $comercio)
    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-azul-marino text-lg">Comercialización</h3>
                <p class="text-sm text-gray-500">Información de mercados</p>
            </div>
            <span class="material-symbols-outlined text-gray-400">shopping_cart</span>
        </div>

        <!-- Detalles -->
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-40">Infraestructura:</span>
                <span class="text-gray-800">{{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-40">Comercio mercado:</span>
                <span class="text-gray-800">{{ $comercio->comercio_mercado ? 'Sí' : 'No' }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="font-medium text-gray-600 w-40">Vende en finca:</span>
                <span class="text-gray-800">{{ $comercio->vende_finca ? 'Sí' : 'No' }}</span>
            </div>
            
            @php
                $mercados = array_filter([
                    $comercio->mercado_local ? 'Local' : null,
                    $comercio->mercado_regional ? 'Regional' : null,
                    $comercio->mercado_nacional ? 'Nacional' : null,
                    $comercio->mercado_internacional ? 'Internacional' : null,
                ]);
            @endphp
            
            @if(count($mercados) > 0)
            <div class="text-sm">
                <span class="font-medium text-gray-600">Mercados:</span>
                <div class="mt-1 flex flex-wrap gap-1">
                    @foreach($mercados as $mercado)
                    <span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs">{{ $mercado }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Acciones -->
        <div class="flex gap-2 pt-3 border-t border-gray-200">
            <a href="{{ route('comercios.edit', $comercio) }}" 
               class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">edit</span>
                Editar
            </a>
            <form action="{{ route('comercios.destroy', $comercio) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');" class="flex-1">
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
        <button onclick="toggleExpandComercios()" 
                class="w-full px-4 py-3 bg-gray-50 text-azul-marino font-medium flex items-center justify-between">
            <span>Ver {{ $remaining->count() }} más</span>
            <span id="expand-icon-comercios" class="material-symbols-outlined transition-transform">expand_more</span>
        </button>
        
        <div id="expandable-content-comercios" class="hidden space-y-4 p-4 bg-gray-50">
            @foreach($remaining as $comercio)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-azul-marino text-lg">Comercialización</h3>
                        <p class="text-sm text-gray-500">Información de mercados</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400">shopping_cart</span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-40">Infraestructura:</span>
                        <span class="text-gray-800">{{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-40">Comercio mercado:</span>
                        <span class="text-gray-800">{{ $comercio->comercio_mercado ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="font-medium text-gray-600 w-40">Vende en finca:</span>
                        <span class="text-gray-800">{{ $comercio->vende_finca ? 'Sí' : 'No' }}</span>
                    </div>
                    
                    @php
                        $mercados = array_filter([
                            $comercio->mercado_local ? 'Local' : null,
                            $comercio->mercado_regional ? 'Regional' : null,
                            $comercio->mercado_nacional ? 'Nacional' : null,
                            $comercio->mercado_internacional ? 'Internacional' : null,
                        ]);
                    @endphp
                    
                    @if(count($mercados) > 0)
                    <div class="text-sm">
                        <span class="font-medium text-gray-600">Mercados:</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($mercados as $mercado)
                            <span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs">{{ $mercado }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-200">
                    <a href="{{ route('comercios.edit', $comercio) }}" 
                       class="flex-1 py-2 bg-yellow-500 text-white rounded text-center font-medium flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Editar
                    </a>
                    <form action="{{ route('comercios.destroy', $comercio) }}" method="POST" 
                          onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');" class="flex-1">
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
        function toggleExpandComercios() {
            const content = document.getElementById('expandable-content-comercios');
            const icon = document.getElementById('expand-icon-comercios');
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    </script>
    @endif
</div>
