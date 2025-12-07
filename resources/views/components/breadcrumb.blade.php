@props(['items'])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        @foreach($items as $index => $item)
            @if($loop->last)
                <li class="inline-flex items-center">
                    <span class="text-gray-500 text-sm font-medium">{{ $item['label'] }}</span>
                </li>
            @else
                <li class="inline-flex items-center">
                    @if($loop->first)
                        <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            {{ $item['label'] }}
                        </a>
                    @else
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ $item['url'] }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $item['label'] }}
                            </a>
                        </div>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
