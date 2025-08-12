@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Perfil</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="name">Nombre</label>
                    <input id="name" name="name" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" class="w-full p-2 border border-gray-300 rounded" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="dni">DNI</label>
                    <input id="dni" name="dni" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('dni', $user->dni) }}">
                    @error('dni')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 flex items-center md:col-span-2">
                    <input id="es_propietario" name="es_propietario" type="checkbox" class="mr-2 custom-checkbox" value="1" {{ old('es_propietario', $user->es_propietario) ? 'checked' : '' }}>
                    <label for="es_propietario" class="text-gray-700 font-semibold">Es propietario</label>
                </div>
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
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px #93c5fd;
    }
</style>
@endsection
