@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Comercialización', 'route' => 'comercios.index'],
        ['name' => 'Detalle']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-orange-700 mb-6">Detalle de Comercio</h2>
        <div class="mb-4">
            <strong>ID:</strong> {{ $comercio->id }}
        </div>
        <div class="mb-4">
            <strong>Usuario:</strong> {{ $comercio->usuario_id }}
        </div>
        <div class="mb-4">
            <strong>Infraestructura de Empaque:</strong> {{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>¿Vende en finca?:</strong> {{ $comercio->vende_en_finca ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>¿Vende en mercado?:</strong> {{ $comercio->comercio_mercado ? 'Sí' : 'No' }}
        </div>
        @if($comercio->comercio_mercado)
        <div class="mb-4">
            <strong>Nombre del mercado:</strong> {{ $comercio->nombre_mercado }}
        </div>
        @endif
        <a href="{{ route('comercios.index') }}" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 font-semibold">Volver al listado</a>
    </div>
</div>
@endsection
