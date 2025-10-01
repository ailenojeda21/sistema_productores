<!-- Recordatorio: Si no ves los cambios, ejecuta 'php artisan view:clear' y recarga con Ctrl+F5 -->

@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-azul-marino">Comercios</h1>
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
                
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Infraestructura Empaque</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Comercio Feria</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Vende en finca</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Ferias</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($comercios as $comercio)
                    <tr>
                        
                        <td class="px-4 py-2 text-base text-gray-700">{{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}</td>
                        <td class="px-4 py-2 text-base text-gray-700">{{ $comercio->comercio_feria ? 'Sí' : 'No' }}</td>
                        <td class="px-4 py-2 text-base text-gray-700">{{ $comercio->vende_en_finca ? 'Sí' : 'No' }}</td>
                        <td class="px-4 py-2 text-base text-gray-700">
                            @php
                                $ferias = isset($comercio->ferias) ? (is_array($comercio->ferias) ? $comercio->ferias : json_decode($comercio->ferias, true)) : [];
                            @endphp
                            @if($ferias && is_array($ferias) && count($ferias))
                                <ul class="list-disc list-inside">
                                    @foreach($ferias as $feria)
                                        <li>{{ $feria }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400 italic">Sin ferias</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No hay comercios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
