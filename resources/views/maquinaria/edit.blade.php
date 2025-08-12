@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Maquinaria</h2>
        <form method="POST" action="{{ route('maquinaria.update', $maquinaria) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('nombre', $maquinaria->nombre) }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                <input id="tipo" name="tipo" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('tipo', $maquinaria->tipo) }}" required>
            </div>
            <div class="mb-4 flex items-center">
                <input id="funciona" name="funciona" type="checkbox" class="mr-2 custom-checkbox" value="1" {{ old('funciona', $maquinaria->funciona) ? 'checked' : '' }}>
                <label for="funciona" class="text-gray-700 font-semibold">¿Funciona?</label>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar Cambios</button>
        </form>
    </div>
</div>
<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 0 0 0 #2563eb;
        cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: #ea580c;
        border-color: #ea580c;
        box-shadow: 0 0 0 2px #fdba74;
    }
</style>
@endsection
