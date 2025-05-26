@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card overflow-hidden">
        <div class="flex justify-between items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center">
                <i class="material-icons mr-3" style="font-size:28px;">eco</i>
                <h1 class="text-2xl font-bold">Cultivos</h1>
            </div>
            <a href="{{ route('cultivos.create') }}" class="sap-button-outline bg-white text-blue-600 hover:bg-blue-50 flex items-center px-4 py-2 rounded shadow-sm">
                <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Cultivo
            </a>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Administre todos los tipos de cultivos desde este panel. Puede agregar, editar o eliminar cultivos según sea necesario.</p>
            </div>
        </div>

        <div class="bg-white p-6">
            <div class="overflow-x-auto">
                <table class="sap-table w-full">
                    <thead>
                        <tr>
                            <th class="w-16">ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th class="w-56 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($cultivos) && count($cultivos) > 0)
                            @foreach($cultivos as $cultivo)
                            <tr>
                                <td>{{ $cultivo->id }}</td>
                                <td class="font-medium">{{ $cultivo->nombre }}</td>
                                <td>{{ $cultivo->tipo }}</td>
                                <td class="flex justify-end space-x-2">
                                    <a href="{{ route('cultivos.edit', $cultivo) }}" class="sap-btn bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center px-3 py-1 rounded-md">
                                        <i class="material-icons mr-1" style="font-size:16px;">edit</i> Editar
                                    </a>
                                    <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este cultivo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="sap-btn bg-red-100 text-red-600 hover:bg-red-200 flex items-center px-3 py-1 rounded-md">
                                            <i class="material-icons mr-1" style="font-size:16px;">delete</i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="material-icons text-gray-400 mb-3" style="font-size:48px;">grass</i>
                                        <p>No hay cultivos registrados</p>
                                        <a href="{{ route('cultivos.create') }}" class="mt-3 text-blue-600 hover:underline flex items-center">
                                            <i class="material-icons mr-1" style="font-size:16px;">add_circle</i> Agregar nuevo cultivo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($cultivos) && count($cultivos) > 0)
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Mostrando {{ count($cultivos) }} cultivo(s)
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('cultivos.create') }}" class="sap-btn sap-btn-primary flex items-center px-4 py-2 rounded-md">
                        <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Cultivo
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
