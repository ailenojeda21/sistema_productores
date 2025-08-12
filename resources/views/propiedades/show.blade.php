@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Propiedad</h2>
        <div class="mb-4">
            <strong>Ubicación:</strong> {{ $propiedad->ubicacion }}
        </div>
        <div class="mb-4">
            <strong>Dirección:</strong> {{ $propiedad->direccion }}
        </div>
        <div class="mb-4">
            <strong>Hectáreas:</strong> {{ $propiedad->hectareas }}
        </div>
        <div class="mb-4">
            <strong>¿Es propietario?:</strong> {{ $propiedad->es_propietario ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>¿Tiene malla?:</strong> {{ $propiedad->malla ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>¿Derecho de riego?:</strong> {{ $propiedad->derecho_riego ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>¿RUT?:</strong> {{ $propiedad->rut ? 'Sí' : 'No' }}
        </div>
        <div class="mb-4">
            <strong>Valor RUT:</strong> {{ $propiedad->rut_valor }}
        </div>
        <div class="mb-4">
            <strong>Archivo RUT:</strong> {{ $propiedad->rut_archivo }}
        </div>
        <div class="mb-4">
            <strong>Hectáreas con malla:</strong> {{ $propiedad->hectareas_malla }}
        </div>
        <div class="mb-4">
            <strong>Tecnología de riego:</strong> {{ $propiedad->tecnologia_riego }}
        </div>
        <div class="mb-4">
            <strong>Condición de acceso:</strong> {{ $propiedad->condicion_acceso }}
        </div>
        <div class="mb-4">
            <strong>Cierre perimetral:</strong> {{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}
        </div>
        <a href="{{ route('propiedades.index') }}" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Volver</a>
    </div>
</div>
@endsection
