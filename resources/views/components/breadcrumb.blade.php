@props([
    'items' => [],
    'home' => true
])

@php
    $allItems = collect($items);
    if ($home) {
        $allItems->prepend([
            'name' => 'Inicio',
            'route' => 'dashboard',
            'icon' => 'home'
        ]);
    }
@endphp

<nav class="flex items-center space-x-1 text-sm mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center">
        @foreach($allItems as $index => $item)
            @php
                if (is_string($item)) {
                    $item = ['name' => $item];
                }
                $isLast = $loop->last;
                $hasRoute = !empty($item['route']) && route($item['route'], $item['params'] ?? [], false) !== false;
                $url = $hasRoute ? route($item['route'], $item['params'] ?? [], false) : ($item['url'] ?? '#');
            @endphp

            @if(!$loop->first)
                <li class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-18 px-1">chevron_right</span>
                </li>
            @endif

            <li>
                @if($isLast || !$hasRoute)
                    <span class="flex items-center {{ $isLast ? 'text-azul-marino font-semibold' : 'text-gray-500' }}">
                        @if(!empty($item['icon']))
                            <span class="material-symbols-outlined text-18 mr-1">{{ $item['icon'] }}</span>
                        @endif
                        {{ $item['name'] ?? '' }}
                    </span>
                @else
                    <a href="{{ $url }}" class="flex items-center text-gray-500 hover:text-azul-marino transition-colors">
                        @if(!empty($item['icon']))
                            <span class="material-symbols-outlined text-18 mr-1">{{ $item['icon'] }}</span>
                        @endif
                        {{ $item['name'] ?? '' }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
