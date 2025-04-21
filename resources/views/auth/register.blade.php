@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-200">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Registro de Usuario</h2>
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="name">Nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="password">Contraseña</label>
                <input id="password" type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded transition">Registrarse</button>
        </form>
        <div class="mt-6 text-center">
            <span class="text-gray-600">¿Ya tienes cuenta?</span>
            <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline ml-1">Ingresar</a>
        </div>
    </div>
</div>
@endsection
