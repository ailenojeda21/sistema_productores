@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-azul-marino">Propiedades</h1>
        <a href="{{ route('propiedades.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nueva Propiedad</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direccion</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hectáreas</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Es propietario?</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiene derecho de riego?</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo derecho de riego</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RUT</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº RUT</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adjunto RUT</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Malla antigranizo</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hectáreas con malla</th>
    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cierre perimetral</th>
    <th class="px-4 py-2"></th>
</tr>

            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($propiedades as $propiedad)
                <tr>
                    <td class="px-4 py-2">{{ $propiedad->id }}</td>
                    <td class="px-4 py-2">{{ $propiedad->direccion }}</td>
                    <td class="px-4 py-2">{{ $propiedad->ubicacion }}</td>
                    <td class="px-4 py-2">{{ number_format($propiedad->hectareas, 0, '', '') }}</td>
                    <td class="px-4 py-2">{{ $propiedad->es_propietario ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ $propiedad->tipo_derecho_riego ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $propiedad->rut ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ number_format($propiedad->rut_valor, 0, '', '') }}</td>
                    <td class="px-4 py-2">{{ $propiedad->rut_archivo }}</td>
                    <td class="px-4 py-2">{{ $propiedad->malla ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ number_format($propiedad->hectareas_malla, 0, '', '') }}</td>
                    <td class="px-4 py-2">{{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('propiedades.edit', $propiedad) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                        <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta propiedad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
