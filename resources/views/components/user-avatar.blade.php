@props([
    'user' => null,
    'size' => 'md', // sm, md, lg, xl
    'editable' => false,
    'showName' => true,
    'showEmail' => false,
    'gradient' => true
])

@php
    $user = $user ?? Auth::user();
    
    $sizes = [
        'sm' => 'h-12 w-12',
        'md' => 'h-24 w-24',
        'lg' => 'h-32 w-32',
        'xl' => 'h-40 w-40'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    $textSizes = [
        'sm' => 'text-sm',
        'md' => 'text-lg',
        'lg' => 'text-xl',
        'xl' => 'text-2xl'
    ];
    
    $textSize = $textSizes[$size] ?? $textSizes['md'];
    
    // Determinar la imagen a mostrar - siempre usar una imagen
    $defaultAvatar = asset('images/avatars/uno.png');
    
    if ($user && !empty($user->avatar)) {
        $avatarSrc = asset('images/avatars/' . $user->avatar);
    } else {
        $avatarSrc = $defaultAvatar;
    }
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    @if($gradient)
    <div class="bg-gradient-to-r from-azul-marino to-blue-700 p-6 rounded-lg w-full flex flex-col items-center">
    @endif
    
    <div class="relative">
        <div class="bg-white rounded-full p-1 shadow-lg border-4 border-amarillo-claro">
            <div class="bg-blue-50 rounded-full overflow-hidden {{ $sizeClass }}">
                <img
                    id="avatar-preview-{{ uniqid() }}"
                    src="{{ $avatarSrc }}"
                    alt="Avatar de {{ $user->name ?? 'Usuario' }}"
                    class="h-full w-full object-cover"
                    onerror="this.onerror=null; this.src='{{ $defaultAvatar }}';"
                >
            </div>
        </div>

        @if($editable)
        <label for="avatar-upload"
               class="absolute bottom-0 right-0 bg-white text-azul-marino rounded-full p-2 shadow-md cursor-pointer hover:bg-amber-100 transition border border-azul-marino">
            <span class="material-symbols-outlined text-sm">photo_camera</span>
            <input type="file"
                   id="avatar-upload"
                   name="avatar"
                   accept="image/*"
                   class="hidden"
                   onchange="previewAvatar(event)">
        </label>
        @endif
    </div>
    
    @if($showName && $user)
    <h3 class="{{ $gradient ? 'text-white' : 'text-azul-marino' }} font-semibold {{ $textSize }} mt-3">
        {{ $user->name }}
    </h3>
    @endif
    
    @if($showEmail && $user)
    <p class="{{ $gradient ? 'text-blue-200' : 'text-gray-600' }} text-sm">
        {{ $user->email }}
    </p>
    @endif
    
    @if($gradient)
    </div>
    @endif
</div>

@if($editable)
@push('scripts')
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previews = document.querySelectorAll('[id^="avatar-preview-"]');
            previews.forEach(preview => {
                preview.src = e.target.result;
            });
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endif


