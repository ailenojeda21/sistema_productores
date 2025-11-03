@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Maquinaria</h1>
        @if(!isset($hasMaquinaria) || !$hasMaquinaria)
            <a href="{{ route('maquinaria.create') }}" 
               class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
               Nueva Máquina
            </a>
        @else
            @php $first = $maquinarias->first(); @endphp
            <a href="{{ route('maquinaria.edit', $first->id) }}" 
               class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">
               Editar Máquina
            </a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
 
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Máquina</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Modelo (Año)</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Implementos</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($maquinarias as $maquinaria)
                    <tr>
           
                        <td class="px-4 py-2 text-base text-gray-700">{{ $maquinaria->tractor ? 'Tractor' : '-' }}</td>
                        <td class="px-4 py-2 text-base text-gray-700">{{ $maquinaria->modelo_tractor ?? '-' }}</td>
                        <td class="px-4 py-2 text-base text-gray-700">
                            <ul class="list-disc list-inside">
                                @if($maquinaria->arado) <li>Arado</li> @endif
                                @if($maquinaria->rastra) <li>Rastra</li> @endif
                                @if($maquinaria->niveleta_comun) <li>Niveleta común</li> @endif
                                @if($maquinaria->niveleta_laser) <li>Niveleta láser</li> @endif
                                @if($maquinaria->cincel_cultivadora) <li>Cincel o cultivadora</li> @endif
                                @if($maquinaria->desmalezadora) <li>Desmalezadora</li> @endif
                                @if($maquinaria->pulverizadora_tractor) <li>Pulverizadora de tractor</li> @endif
                                @if($maquinaria->mochila_pulverizadora) <li>Mochila pulverizadora</li> @endif
                                @if($maquinaria->cosechadora) <li>Cosechadora</li> @endif
                                @if($maquinaria->enfardadora) <li>Enfardadora</li> @endif
                                @if($maquinaria->retroexcavadora) <li>Retroexcavadora</li> @endif
                                @if($maquinaria->carro_carreton) <li>Carro o carreton</li> @endif
                            </ul>
                            @if(!($maquinaria->arado || $maquinaria->rastra || $maquinaria->niveleta_comun || $maquinaria->niveleta_laser || $maquinaria->cincel_cultivadora || $maquinaria->desmalezadora || $maquinaria->pulverizadora_tractor || $maquinaria->mochila_pulverizadora || $maquinaria->cosechadora || $maquinaria->enfardadora || $maquinaria->retroexcavadora))
                                <span class="text-gray-400 italic">Sin implementos</span>
                            @endif
                        </td>
               <td class="px-4 py-2 text-right">
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No hay maquinaria registrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
