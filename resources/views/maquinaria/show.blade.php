@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Maquinaria</h2>
        <div class="mb-4">
            <strong>Nombre:</strong> {{ $maquinaria->nombre }}
        </div>
        <div class="mb-4">
            <strong>Tipo:</strong> {{ $maquinaria->tipo }}
        </div>
        <div class="mb-4">
            <strong>¿Funciona?:</strong> {{ $maquinaria->funciona ? 'Sí' : 'No' }}
        </div>
        <a href="{{ route('maquinaria.index') }}" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Volver</a>
    </div>
</div>
@endsection
