@php
    // Obtener la ruta actual
    $currentRoute = request()->route();
    $routeName = $currentRoute ? $currentRoute->getName() : '';
    $routeParameters = $currentRoute ? $currentRoute->parameters() : [];

    // Definir los nombres amigables para cada ruta
    $friendlyNames = [
        'dashboard' => 'Inicio',
        'profile' => 'Perfil',
        'propiedades.index' => 'Propiedades',
        'propiedades.create' => 'Nueva Propiedad',
        'propiedades.edit' => 'Editar Propiedad',
        'propiedades.show' => 'Detalle de Propiedad',
        'cultivos.index' => 'Cultivos',
        'cultivos.create' => 'Nuevo Cultivo',
        'cultivos.edit' => 'Editar Cultivo',
        'cultivos.show' => 'Detalle de Cultivo',
        'maquinaria.index' => 'Maquinarias',
        'maquinaria.create' => 'Nueva Maquinaria',
        'maquinaria.edit' => 'Editar Maquinaria',
        'comercios.index' => 'Comercialización',
        'comercios.create' => 'Nueva Transacción',
        'comercios.edit' => 'Editar Transacción',
    ];

    // Si no hay nombre de ruta, mostrar solo el breadcrumb de inicio
    if (empty($routeName)) {
        $items = [['url' => route('dashboard'), 'label' => 'Inicio']];
    } else {
        // Obtener las partes de la ruta
        $segments = explode('.', $routeName);
        $items = [];
        $url = '';

        // Construir el breadcrumb dinámicamente
        foreach ($segments as $index => $segment) {
            $url = $url ? "$url.$segment" : $segment;

            // Saltar rutas específicas que no queremos en el breadcrumb
            if (in_array($url, ['dashboard', 'index'])) {
                continue;
            }

            // Verificar si la ruta existe
            $routeExists = false;
            try {
                $routeUrl = route($url, $routeParameters);
                $routeExists = true;
            } catch (\Exception $e) {
                $routeExists = false;
            }

            // Obtener el nombre amigable o generar uno a partir del segmento
            $label = $friendlyNames[$url] ?? ucwords(str_replace(['-', '_'], ' ', $segment));

            // Si es el último segmento o la ruta no existe, no es un enlace
            if ($index === count($segments) - 1 || !$routeExists) {
                $items[] = ['label' => $label];
            } else {
                try {
                    $items[] = ['url' => route($url, $routeParameters), 'label' => $label];
                } catch (\Exception $e) {
                    $items[] = ['label' => $label];
                }
            }
        }

        // Agregar el enlace de inicio al principio si no está vacío
        if (!empty($items)) {
            array_unshift($items, ['url' => route('dashboard'), 'label' => 'Inicio']);
        }
    }
@endphp

@if(count($items) > 1)
    <nav class="w-full max-w-2xl mb-6" aria-label="breadcrumb">
        <ol class="flex items-center text-sm text-gray-600 space-x-2">
            @foreach($items as $index => $item)
                <li>
                    @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:underline text-gray-500">
                            @if(!empty($item['icon']))
                                <i class="{{ $item['icon'] }} mr-2" aria-hidden="true"></i>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="{{ $item['class'] ?? 'text-azul-marino font-semibold' }}" aria-current="page">
                            @if(!empty($item['icon']))
                                <i class="{{ $item['icon'] }} mr-2" aria-hidden="true"></i>
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>

                @if($index !== array_key_last($items))
                    <li>
                        <span class="text-gray-400">/</span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
