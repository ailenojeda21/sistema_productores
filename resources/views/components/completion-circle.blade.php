@props(['percentage' => 0])

@php
    $percentage = min(100, max(0, (int) $percentage));
    
    if ($percentage >= 100) {
        $color = '#10b981';
    } elseif ($percentage >= 50) {
        $color = '#f59e0b';
    } else {
        $color = '#ef4444';
    }
    
    $isComplete = $percentage >= 100;
    $deg = $percentage * 3.6;
@endphp

<div {{ $attributes->merge(['class' => 'relative inline-flex items-center justify-center']) }}>
    @if($isComplete)
    <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background-color: {{ $color }};">
        <svg width="18" height="18" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="white" />
        </svg>
    </div>
    @else
    <div class="w-7 h-7 rounded-full relative flex items-center justify-center" 
         style="background: conic-gradient({{ $color }} {{ $deg }}deg, rgba(255,255,255,0.15) {{ $deg }}deg);">
        <div class="absolute w-5 h-5 rounded-full flex items-center justify-center" style="background-color: #1e3a5f;">
            <span class="text-[7px] font-bold text-white leading-none">
                {{ $percentage }}%
            </span>
        </div>
    </div>
    @endif
</div>
