@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-pink-700 mb-6">Editar Perfil</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="name">Nombre</label>
                <input id="name" name="name" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="w-full p-2 border border-gray-300 rounded" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="dni">DNI</label>
                <input id="dni" name="dni" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('dni', $user->dni) }}">
                @error('dni')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 flex items-center">
                <input id="es_propietario" name="es_propietario" type="checkbox" class="mr-2" value="1" {{ old('es_propietario', $user->es_propietario) ? 'checked' : '' }}>
                <label for="es_propietario" class="text-gray-700 font-semibold">Es propietario</label>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded">Guardar Cambios</button>
        </form>
    </div>
</div>
@endsection
