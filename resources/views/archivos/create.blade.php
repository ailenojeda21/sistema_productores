@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Nuevo Archivo</h2>
        <form method="POST" action="{{ route('archivos.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="archivo">Archivo</label>
                <input id="archivo" name="archivo" type="file" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Guardar</button>
        </form>
    </div>
</div>
@endsection
