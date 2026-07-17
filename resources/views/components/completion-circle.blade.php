@props(['percentage' => 0])

@php
    $percentage = min(100, max(0, (int) $percentage));

    if ($percentage >= 100) {
        $color = '#10b981'; // Verde
    } elseif ($percentage >= 70) {
        $color = '#14b8a6'; // Turquesa
    } elseif ($percentage >= 40) {
        $color = '#3b82f6'; // Azul
    } else {
        $color = '#ef4444'; // Rojo
    }

    $isComplete = $percentage >= 100;
    $deg = $percentage * 3.6;
@endphp

<div {{ $attributes->merge(['class' => 'relative inline-flex items-center justify-center']) }}>

    @if ($isComplete)

        <div
            class="w-7 h-7 rounded-full flex items-center justify-center"
            style="background-color: {{ $color }};">

            <svg width="18" height="18" viewBox="0 0 24 24">
                <path
                    d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"
                    fill="white" />
            </svg>

        </div>

  @else

    <div
    class="w-7 h-7 rounded-full relative flex items-center justify-center"
    style="
        background: conic-gradient(
            {{ $color }} {{ $deg }}deg,
            rgba(255,255,255,0.75) {{ $deg }}deg
        );
    ">

    <div
        class="absolute w-5 h-5 rounded-full flex items-center justify-center"
        style="background-color: #F39200;">

        <span class="text-[8px] font-bold leading-none text-white">
            {{ $percentage }}%
        </span>

    </div>

</div>

@endif

</div>