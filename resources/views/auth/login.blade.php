@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-azul-marino"> {{-- Fondo principal con tu azul-marino --}}
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-amarillo-claro mb-6">Ingreso al Sistema Agrícola</h2> {{-- Título amarillo-claro para resaltar --}}
        
        @if(session('status'))
            <div class="mb-4 text-sm text-green-600"> {{-- Mantengo este color para mensajes de estado si no hay un color específico en la paleta --}}
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-naranja-oscuro"> {{-- Focus ring naranja-oscuro --}}
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="password">Contraseña</label>
                <input id="password" type="password" name="password" required 
                       class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-naranja-oscuro"> {{-- Focus ring naranja-oscuro --}}
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-naranja-oscuro hover:underline text-sm">¿Olvidaste tu contraseña?</a> {{-- Enlace naranja-oscuro --}}
            </div>
            
            <button type="submit" class="w-full py-2 px-4 bg-naranja-oscuro hover:bg-opacity-90 text-white font-bold rounded transition duration-300 ease-in-out">Ingresar</button> {{-- Botón principal naranja-oscuro --}}
        </form>
        
        <div class="mt-6 text-center">
            <span class="text-gray-600">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="text-azul-marino font-semibold hover:underline ml-1">Regístrate</a> {{-- Enlace secundario azul-marino --}}
        </div>
    </div>
</div>
@endsection