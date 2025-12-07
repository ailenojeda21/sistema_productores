@extends('layouts.dashboard')

@php
$breadcrumbs = [
    ['url' => route('dashboard'), 'label' => 'Inicio'],
    ['url' => '#', 'label' => 'Ejemplo'],
];
@endphp

@section('dashboard-content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Título de la Página</h1>
        <p>Contenido de ejemplo para mostrar el breadcrumb.</p>
    </div>
@endsection
