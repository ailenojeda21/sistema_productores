@props(['small' => false])

@php
$iconSize = $small ? 'text-sm' : '';
$textSize = $small ? 'text-sm' : '';
@endphp

@if(session('error'))
    <div {{ $attributes->merge(['class' => 'mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2']) }}>
        <span class="material-symbols-outlined {{ $iconSize }}">error</span>
        <span class="{{ $textSize }}">{{ session('error') }}</span>
    </div>
@endif

@if(session('success'))
    <div {{ $attributes->merge(['class' => 'mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2']) }}>
        <span class="material-symbols-outlined {{ $iconSize }}">check_circle</span>
        <span class="{{ $textSize }}">{{ session('success') }}</span>
    </div>
@endif
