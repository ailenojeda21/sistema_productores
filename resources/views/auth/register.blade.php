@extends('layouts.app')

@section('no_header', true)

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
   <!-- Mitad Izquierda -->
    <div class="hidden md:flex md:w-1/2 w-full bg-gray-100 items-center justify-center p-0 overflow-hidden" style="height: 100vh; max-height: 100vh;">
        <img src="{{ asset('images/rupal.png') }}" alt="Logo Municipalidad de Lavalle" class="w-full h-full" style="max-height: 100vh;">
    </div>

    <!-- Mitad Derecha -->
    <div class="w-full md:w-1/2 flex flex-col justify-center relative p-4 bg-white min-h-screen">
        <!-- Logo arriba a la derecha -->
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-8 right-8 h-20"> 
          
        <div class="max-w-sm w-full mx-auto">
            <h1 class="text-3xl font-bold text-center text-naranja-oscuro mb-6">Regístrate</h1>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input id="name" name="name" type="text" required autofocus class="mt-1 block w-full rounded border-gray-300 focus:border-naranja-oscuro focus:ring focus:ring-naranja-oscuro/30">
                    @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input id="email" name="email" type="email" required class="mt-1 block w-full rounded border-gray-300 focus:border-naranja-oscuro focus:ring focus:ring-naranja-oscuro/30">
                    @error('email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password" required class="mt-1 block w-full rounded border-gray-300 focus:border-naranja-oscuro focus:ring focus:ring-naranja-oscuro/30">
                    @error('password')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full rounded border-gray-300 focus:border-naranja-oscuro focus:ring focus:ring-naranja-oscuro/30">
                </div>
                <button type="submit" class="w-full py-2 px-4 bg-naranja-oscuro text-white font-bold rounded hover:bg-amarillo-claro transition">Registrarse</button>
            </form>
            <div class="mt-6 flex flex-col items-center space-y-2 text-center">
                <a href="{{ route('login') }}" class="text-azul-marino font-semibold hover:underline hover:text-blue-600 transition">¿Ya tienes cuenta? Inicia sesión</a>
                <a href="{{ url('/') }}" class="text-gray-400 font-semibold hover:underline hover:text-blue-600 transition">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection
