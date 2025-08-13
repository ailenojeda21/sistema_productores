@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-azul-marino">Comercios</h1>
        <a href="{{ route('comercios.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nuevo Comercio</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Infraestructura Empaque</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comercio Feria</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vende en finca</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Feria</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($comercios as $comercio)
                <tr>
                    <td class="px-4 py-2">{{ $comercio->id }}</td>
                    <td class="px-4 py-2">{{ $comercio->usuario_id }}</td>
                    <td class="px-4 py-2">{{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ $comercio->comercio_feria ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ $comercio->vende_en_finca ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">{{ $comercio->nombre_feria }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('comercios.edit', $comercio) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                        <form action="{{ route('comercios.destroy', $comercio) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este comercio?');">
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
