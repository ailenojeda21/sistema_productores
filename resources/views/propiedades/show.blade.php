@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Propiedad</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4"><strong>Ubicación:</strong> {{ $propiedad->ubicacion }}</div>
            <div class="mb-4"><strong>Dirección:</strong> {{ $propiedad->direccion }}</div>
            <div class="mb-4"><strong>Hectáreas:</strong> {{ number_format($propiedad->hectareas, 2, ',', '.') }}</div>
            <div class="mb-4"><strong>¿Es propietario?:</strong> {{ $propiedad->es_propietario ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>¿Tiene derecho de riego?:</strong> {{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</div>
    @if($propiedad->derecho_riego)
    <div class="mb-4"><strong>Tipo de derecho de riego:</strong> {{ $propiedad->tipo_derecho_riego ?? '-' }}</div>
    @endif
            <div class="mb-4"><strong>¿RUT?:</strong> {{ $propiedad->rut ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>Nº RUT:</strong> {{ number_format($propiedad->rut_valor, 0, '', '') }}</div>
            <div class="mb-4"><strong>Adjunto RUT:</strong> {{ $propiedad->rut_archivo }}</div>
            <div class="mb-4"><strong>Malla antigranizo:</strong> {{ $propiedad->malla ? 'Sí' : 'No' }}</div>
            <div class="mb-4"><strong>Hectáreas con malla:</strong> {{ number_format($propiedad->hectareas_malla, 2, '.', ',') }}</div>
            <div class="mb-4"><strong>Cierre perimetral:</strong> {{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</div>
        </div>
        <a href="{{ route('propiedades.index') }}" class="mt-8 px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro hover:text-azul-marino font-semibold shadow transition">Volver al listado</a>
    </div>
</div>
@endsection
