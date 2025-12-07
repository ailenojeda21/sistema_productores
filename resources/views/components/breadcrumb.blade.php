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
        $breadcrumbs = [['url' => route('dashboard'), 'label' => 'Inicio']];
    } else {
        // Obtener las partes de la ruta
        $segments = explode('.', $routeName);
        $breadcrumbs = [];
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
                $breadcrumbs[] = ['label' => $label];
            } else {
                try {
                    $breadcrumbs[] = ['url' => route($url, $routeParameters), 'label' => $label];
                } catch (\Exception $e) {
                    $breadcrumbs[] = ['label' => $label];
                }
            }
        }
        
        // Agregar el enlace de inicio al principio si no está vacío
        if (!empty($breadcrumbs)) {
            array_unshift($breadcrumbs, ['url' => route('dashboard'), 'label' => 'Inicio']);
        }
    }
@endphp

@if(count($breadcrumbs) > 1)
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        @foreach($breadcrumbs as $index => $item)
            @if($loop->last)
                <li class="inline-flex items-center">
                    <span class="text-gray-500 text-sm font-medium">{{ $item['label'] ?? 'Inicio' }}</span>
                </li>
            @else
                <li class="inline-flex items-center">
                    @if(isset($item['url']))
                        @if($loop->first)
                            <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-home mr-2"></i>
                                {{ $item['label'] ?? 'Inicio' }}
                            </a>
                        @else
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ $item['url'] }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                    {{ $item['label'] ?? 'Inicio' }}
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center">
                            @if(!$loop->first)
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-500">
                                {{ $item['label'] ?? 'Inicio' }}
                            </span>
                        </div>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif
