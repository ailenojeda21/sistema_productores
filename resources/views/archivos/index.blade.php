@extends('layouts.app')

@section('content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[['label' => 'Archivos']]" />
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-700">Archivos</h1>
        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">Nuevo Archivo</a>
    </div>
    <div class="bg-white rounded shadow p-6">
        <div class="overflow-x-auto">
            <div class="min-w-max w-full">
                <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Tipo</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí irán las filas de archivos --}}
            </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
