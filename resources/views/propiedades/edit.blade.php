@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Editar Propiedad</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('propiedades.update', $propiedad) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('nombre', $propiedad->nombre) }}" required>
                @error('nombre')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="ubicacion">Ubicación</label>
                <input id="ubicacion" name="ubicacion" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('ubicacion', $propiedad->ubicacion) }}" required>
                @error('ubicacion')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded">Guardar Cambios</button>
        </form>
    </div>
</div>
@endsection
