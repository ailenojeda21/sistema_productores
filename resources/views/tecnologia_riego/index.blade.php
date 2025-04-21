@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-teal-700">Tecnología de Riego</h1>
        <a href="#" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-semibold">Nueva Tecnología</a>
    </div>
    <div class="bg-white rounded shadow p-6">
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
                {{-- Aquí irán las filas de tecnologías de riego --}}
            </tbody>
        </table>
    </div>
</div>
@endsection
