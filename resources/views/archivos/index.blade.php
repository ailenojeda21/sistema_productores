@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card overflow-hidden">
        <div class="flex justify-between items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center">
                <i class="material-icons mr-3" style="font-size:28px;">folder</i>
                <h1 class="text-2xl font-bold">Archivos</h1>
            </div>
            <a href="{{ route('archivos.create') }}" class="sap-button-outline bg-white text-blue-600 hover:bg-blue-50 flex items-center px-4 py-2 rounded shadow-sm">
                <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Archivo
            </a>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Administre todos sus archivos desde este panel. Puede subir, descargar o eliminar archivos según sea necesario.</p>
            </div>
        </div>

        <div class="bg-white p-6">
            <div class="overflow-x-auto sap-table-responsive">
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
                        @foreach($archivos ?? [] as $archivo)
                        <tr>
                            <td>{{ $archivo->id }}</td>
                            <td class="font-medium">{{ $archivo->nombre }}</td>
                            <td>{{ $archivo->tipo ?? 'Documento' }}</td>
                            <td class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('archivos.download', $archivo->id ?? 1) }}" class="sap-btn bg-green-100 text-green-600 hover:bg-green-200 flex items-center px-3 py-1 rounded-md">
                                    <i class="material-icons mr-1" style="font-size:16px;">download</i> Descargar
                                </a>
                                <form action="{{ route('archivos.destroy', $archivo->id ?? 1) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este archivo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="sap-btn bg-red-100 text-red-600 hover:bg-red-200 flex items-center px-3 py-1 rounded-md">
                                        <i class="material-icons mr-1" style="font-size:16px;">delete</i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($archivos ?? []))
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="material-icons text-gray-400 mb-3" style="font-size:48px;">folder_off</i>
                                    <p>No hay archivos registrados</p>
                                    <a href="{{ route('archivos.create') }}" class="mt-3 text-blue-600 hover:underline flex items-center">
                                        <i class="material-icons mr-1" style="font-size:16px;">add_circle</i> Subir nuevo archivo
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(!empty($archivos ?? []))
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Mostrando {{ count($archivos ?? []) }} archivo(s)
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('archivos.create') }}" class="sap-btn sap-btn-primary flex items-center px-4 py-2 rounded-md">
                        <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Archivo
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
