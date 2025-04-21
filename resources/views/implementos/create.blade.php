@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-indigo-700 mb-6">Nuevo Implemento</h2>
        <form method="POST" action="{{ route('implementos.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                <input id="tipo" name="tipo" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded">Guardar</button>
        </form>
    </div>
</div>
@endsection
