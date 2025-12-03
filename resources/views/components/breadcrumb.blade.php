@props(['items' => []])

<nav {{ $attributes->merge(['class' => 'flex mb-4']) }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center font-medium text-gray-700 hover:text-blue-600 transition-colors">
                <i class="nf nf-fa-home mr-1.5"></i>
                Inicio
            </a>
        </li>
        
        @foreach($items as $item)
            <li aria-current="{{ $loop->last ? 'page' : 'false' }}">
                <div class="flex items-center">
                    <i class="nf nf-fa-chevron_right text-gray-400 mx-1.5"></i>
                    @if($loop->last)
                        <span class="text-gray-500 font-medium">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['url'] ?? '#' }}" class="font-medium text-gray-700 hover:text-blue-600 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
