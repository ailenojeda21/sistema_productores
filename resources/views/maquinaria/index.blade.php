@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-yellow-700">Maquinaria</h1>
        <a href="#" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold">Nueva Máquina</a>
    </div>
    <div class="bg-white rounded shadow p-6">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Tipo</th>
                   
                </tr>
            </thead>
            <tbody>
                {{-- Aquí irán las filas de maquinaria --}}
            </tbody>
        </table>
    </div>
</div>
@endsection
