@extends('layouts.app')

@section('no_header', true)

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
    <!-- Mitad Izquierda -->
    <div class="hidden md:flex md:w-1/2 w-full bg-gray-100 items-center justify-center p-0 overflow-hidden" style="height: 100vh; max-height: 100vh;">
        <img src="{{ asset('images/fruta2.png') }}" alt="Logo Municipalidad de Lavalle" class="w-full h-full object-cover" style="max-height: 100vh;">
    </div>
    <!-- Mitad Derecha -->
    <div class="w-full md:w-1/2 flex flex-col justify-center relative p-4 sm:p-6 md:p-8 bg-white min-h-screen">
        <!-- Logo arriba a la derecha -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-4 sm:top-6 md:top-8 right-4 sm:right-6 md:right-8 h-16 sm:h-20 md:h-24 w-auto">
        <div class="max-w-md w-full mx-auto flex flex-col items-center mt-20 sm:mt-0">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-amarillo-claro mb-3 sm:mb-4 text-center">Bienvenido al Sistema Agrícola Municipal</h1>
            <p class="text-sm sm:text-base text-gray-700 mb-6 sm:mb-8 text-center leading-relaxed">Gestiona productores, propiedades, maquinaria, cultivos y más.<br>Accede con tu usuario o regístrate para comenzar.</p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                <a href="{{ route('login') }}" class="px-6 py-2 sm:py-3 bg-naranja-oscuro text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out text-center text-sm sm:text-base">Ingresar</a>
                <a href="{{ route('register') }}" class="px-6 py-2 sm:py-3 bg-azul-marino text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out text-center text-sm sm:text-base">Registrarse</a>
            </div>
        </div>
    </div>
</div>
@endsection