@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
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
            <strong>¿Vende en feria?:</strong> {{ $comercio->comercio_feria ? 'Sí' : 'No' }}
        </div>
        @if($comercio->comercio_feria)
        <div class="mb-4">
            <strong>Nombre de la feria:</strong> {{ $comercio->nombre_feria }}
        </div>
        @endif
        <a href="{{ route('comercios.index') }}" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 font-semibold">Volver al listado</a>
    </div>
</div>
@endsection
