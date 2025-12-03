@props([
    'user' => null,
    'size' => 'md',
    'class' => '',
])

@php
    // Default sizes
    $sizes = [
        'sm' => 'h-10 w-10',
        'md' => 'h-12 w-12',
        'lg' => 'h-16 w-16',
        'xl' => 'h-20 w-20',
        '2xl' => 'h-32 w-32',
        'profile' => 'h-44 w-44 md:h-48 md:w-48',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    // Text size based on avatar size
    $textSizes = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl',
        'profile' => 'text-6xl',
    ];
    $textSize = $textSizes[$size] ?? $textSizes['md'];
    
    // Get user initials for fallback
    $initials = '';
    if ($user) {
        $nameParts = explode(' ', $user->name);
        $initials = count($nameParts) > 1 
            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
            : strtoupper(substr($user->name, 0, 2));
    }
    
    // Background colors for initials
    $colors = [
        'bg-blue-100 text-blue-600',
        'bg-green-100 text-green-600',
        'bg-yellow-100 text-yellow-600',
        'bg-red-100 text-red-600',
        'bg-purple-100 text-purple-600',
        'bg-pink-100 text-pink-600',
        'bg-indigo-100 text-indigo-600',
    ];
    $colorClass = $colors[abs(crc32($user ? $user->id : '') % count($colors))];
    
    // Check if avatar exists
    $hasAvatar = $user && $user->avatar && file_exists(public_path('images/avatars/' . $user->avatar));
@endphp

<div {{ $attributes->merge(['class' => 'rounded-full overflow-hidden shadow-md flex items-center justify-center ' . $sizeClass . ' ' . $class]) }}>
    @if($hasAvatar)
        <img 
            src="{{ asset('images/avatars/' . $user->avatar) }}" 
            alt="{{ $user->name }}" 
            class="h-full w-full object-cover"
        >
    @else
        <div class="w-full h-full flex items-center justify-center bg-gray-200">
            <i class="nf nf-fa-user text-4xl text-gray-500"></i>
        </div>
    @endif
</div>
