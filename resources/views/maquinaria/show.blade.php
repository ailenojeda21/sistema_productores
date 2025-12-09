@extends('layouts.main')

@section('dashboard-content')
    <div class="w-full max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold text-azul-marino mb-6">Detalle de Maquinaria</h2>
            <div class="mb-4">
                <strong>Tractor:</strong> {{ $maquinaria->tractor ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Modelo (Año):</strong> {{ $maquinaria->modelo_tractor ?? '-' }}
            </div>
            <div class="mb-4">
                <strong>Arado:</strong> {{ $maquinaria->arado ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Rastra:</strong> {{ $maquinaria->rastra ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Niveleta común:</strong> {{ $maquinaria->niveleta_comun ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Niveleta láser:</strong> {{ $maquinaria->niveleta_laser ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Cincel o cultivadora:</strong> {{ $maquinaria->cincel_cultivadora ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Desmalezadora:</strong> {{ $maquinaria->desmalezadora ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Pulverizadora de tractor:</strong> {{ $maquinaria->pulverizadora_tractor ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Mochila pulverizadora:</strong> {{ $maquinaria->mochila_pulverizadora ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Cosechadora:</strong> {{ $maquinaria->cosechadora ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Enfardadora:</strong> {{ $maquinaria->enfardadora ? 'Sí' : 'No' }}
            </div>
            <div class="mb-4">
                <strong>Retroexcavadora:</strong> {{ $maquinaria->retroexcavadora ? 'Sí' : 'No' }}
            </div>
            <a href="{{ route('maquinaria.index') }}"
               class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Volver</a>
        </div>
    </div>
@endsection
