@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-700">Propiedades</h1>
        <a href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">Nueva Propiedad</a>
    </div>
    <div class="bg-white rounded shadow p-6">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Ubicación</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($propiedades as $propiedad)
                <tr>
                    <td class="px-4 py-2">{{ $propiedad->id }}</td>
                    <td class="px-4 py-2">{{ $propiedad->nombre }}</td>
                    <td class="px-4 py-2">{{ $propiedad->ubicacion }}</td>
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
