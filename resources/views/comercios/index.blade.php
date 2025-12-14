@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Comercialización</h1>
        @if($comercios->isEmpty())
            <a href="{{ route('comercios.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nuevo Comercio</a>
        @else
            <a href="{{ route('comercios.edit', $comercios->first()->id) }}" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Editar Comercio</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-28 px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Infraestructura Empaque
                    </th>

                    <th class="w-28 px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Comercialización en Mercados
                    </th>

                    <!-- Ensanchado -->
                    <th class="w-32 px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Vende en Finca
                    </th>

                    <!-- Movido a la derecha -->
                    <th class="px-4 py-2 pl-6 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Mercados
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($comercios as $comercio)
                <tr>
                    <td class="w-28 px-2 py-2 text-base text-gray-700">
                        {{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}
                    </td>

                    <td class="w-28 px-2 py-2 text-base text-gray-700">
                        {{ $comercio->comercio_mercado ? 'Sí' : 'No' }}
                    </td>

                    <!-- Ensanchado -->
                    <td class="w-32 px-2 py-2 text-base text-gray-700">
                        {{ $comercio->vende_en_finca ? 'Sí' : 'No' }}
                    </td>

                    <!-- Movido a la derecha -->
                    <td class="px-4 py-2 pl-6 text-base text-gray-700">
                        @php
                            $mercados = isset($comercio->mercados)
                                ? (is_array($comercio->mercados) ? $comercio->mercados : json_decode($comercio->mercados, true))
                                : [];
                        @endphp

                        @if($mercados && is_array($mercados) && count($mercados))
                            <ul class="list-disc list-inside">
                                @foreach($mercados as $mercado)
                                    <li>{{ $mercado }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-400 italic">Sin mercados</span>
                        @endif
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        @if($comercios->isEmpty())
        @include('comercios.partials.empty-state')
        @endif
    </div>
</div>
@endsection
