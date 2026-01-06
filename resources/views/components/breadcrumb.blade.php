@props([
    'items' => [],
    'home' => true
])

@php
    $allItems = collect($items);
    if ($home) {
        $homeItem = collect([
            'name' => 'Inicio',
            'route' => 'dashboard',
            'icon' => 'home'
        ]);
        $allItems = $homeItem->merge($allItems);
    }
@endphp

<nav class="flex items-center space-x-2 text-sm mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        @foreach($allItems as $index => $item)
            @php
                $isLast = $loop->last;
                $hasRoute = isset($item['route']) && route($item['route'], isset($item['params']) ? $item['params'] : [], false) !== false;
                $url = $hasRoute ? route($item['route'], $item['params'] ?? [], false) : ($item['url'] ?? '#');
            @endphp
            
            {{-- Separador --}}
            @if(!$loop->first)
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-18">chevron_right</span>
                </li>
            @endif
            
            {{-- Item --}}
            <li>
                @if($isLast || !$hasRoute)
                    {{-- Último item (activo) o sin ruta --}}
                    <span class="flex items-center {{ $isLast ? 'text-azul-marino font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        @if(isset($item['icon']))
                            <span class="material-symbols-outlined text-18 mr-1">{{ $item['icon'] }}</span>
                        @endif
                        {{ $item['name'] }}
                    </span>
                @else
                    {{-- Item con enlace --}}
                    <a href="{{ $url }}" class="flex items-center text-gray-500 hover:text-azul-marino transition-colors">
                        @if(isset($item['icon']))
                            <span class="material-symbols-outlined text-18 mr-1">{{ $item['icon'] }}</span>
                        @endif
                        {{ $item['name'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
