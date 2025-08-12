@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-azul-marino">Cultivos</h1>
        <a href="{{ route('cultivos.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nuevo Cultivo</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estación</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hectáreas Totales</th>
                    <!-- Eliminado malla antigranizo -->
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tecnología de riego</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($cultivos as $cultivo)
                <tr>
                    <td class="px-4 py-2">{{ $cultivo->id }}</td>
                    <td class="px-4 py-2">{{ $cultivo->nombre }}</td>
                    <td class="px-4 py-2">{{ $cultivo->tipo }}</td>
                    <td class="px-4 py-2">{{ $cultivo->estacion }}</td>
                    <td class="px-4 py-2">{{ $cultivo->hectareas_totales }}</td>
                    <!-- Eliminado malla antigranizo -->
                    <td class="px-4 py-2">{{ $cultivo->tecnologia_riego }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('cultivos.edit', $cultivo) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                        <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este cultivo?');">
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
