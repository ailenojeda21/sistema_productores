@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Cultivo</h2>
        <div class="mb-4">
            <strong>Propiedad:</strong> {{ $cultivo->propiedad_id }}
        </div>
        <div class="mb-4">
            <strong>Manejo de Cultivos:</strong> {{ $cultivo->manejo_cultivo }}
        </div>
        <div class="mb-4">
            <strong>Estación:</strong> {{ $cultivo->estacion }}
        </div>
        <div class="mb-4">
            <strong>Tipo:</strong> {{ $cultivo->tipo }}
        </div>
        <div class="mb-4">
            <strong>Hectáreas:</strong> {{ number_format($cultivo->hectareas, 0, '', '') }}
        </div>
        <div class="mb-4">
            <strong>Tecnología de riego:</strong> {{ $cultivo->tecnologia_riego ?? '-' }}
        </div>
        <a href="{{ route('cultivos.index') }}" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Volver</a>
    </div>
</div>
@endsection
