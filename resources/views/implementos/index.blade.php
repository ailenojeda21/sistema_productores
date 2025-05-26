@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card overflow-hidden">
        <div class="flex justify-between items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center">
                <i class="material-icons mr-3" style="font-size:28px;">construction</i>
                <h1 class="text-2xl font-bold">Implementos</h1>
            </div>
            <a href="{{ route('implementos.create') }}" class="sap-button-outline bg-white text-blue-600 hover:bg-blue-50 flex items-center px-4 py-2 rounded shadow-sm">
                <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Implemento
            </a>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Administre todos sus implementos agrícolas desde este panel. Puede agregar, editar o eliminar implementos según sea necesario.</p>
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
                        @foreach($implementos ?? [] as $implemento)
                        <tr>
                            <td>{{ $implemento->id }}</td>
                            <td class="font-medium">{{ $implemento->nombre }}</td>
                            <td>{{ $implemento->tipo }}</td>
                            <td class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('implementos.edit', $implemento->id ?? 1) }}" class="sap-btn bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center px-3 py-1 rounded-md">
                                    <i class="material-icons mr-1" style="font-size:16px;">edit</i> Editar
                                </a>
                                <form action="{{ route('implementos.destroy', $implemento->id ?? 1) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este implemento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="sap-btn bg-red-100 text-red-600 hover:bg-red-200 flex items-center px-3 py-1 rounded-md">
                                        <i class="material-icons mr-1" style="font-size:16px;">delete</i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($implementos ?? []))
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="material-icons text-gray-400 mb-3" style="font-size:48px;">build_circle</i>
                                    <p>No hay implementos registrados</p>
                                    <a href="{{ route('implementos.create') }}" class="mt-3 text-blue-600 hover:underline flex items-center">
                                        <i class="material-icons mr-1" style="font-size:16px;">add_circle</i> Agregar nuevo implemento
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(!empty($implementos ?? []))
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Mostrando {{ count($implementos ?? []) }} implemento(s)
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('implementos.create') }}" class="sap-btn sap-btn-primary flex items-center px-4 py-2 rounded-md">
                        <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Implemento
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
