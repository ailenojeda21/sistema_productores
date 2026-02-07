@extends('layouts.app')

@section('no_header', true)

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
    <!-- Mitad Izquierda -->
    <div class="hidden md:flex md:w-1/2 w-full bg-gray-100 items-center justify-center p-0 overflow-hidden" style="height: 100vh; max-height: 100vh;">
        <img src="{{ asset('images/rupal.png') }}" alt="Logo Municipalidad de Lavalle" class="w-full h-full" style="max-height: 100vh;">
    </div>
    <!-- Mitad Derecha -->
    <div class="w-full md:w-1/2 flex flex-col justify-center relative p-8 bg-white min-h-screen">
        <!-- Logo arriba a la derecha -->
         <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-8 right-8 h-20">
        <div class="max-w-md w-full mx-auto flex flex-col items-center">
            <h1 class="text-3xl font-bold text-amarillo-claro mb-2 text-center">Bienvenido al Sistema Agrícola Municipal</h1>
            <p class="text-gray-700 mb-6 text-center">Gestiona productores, propiedades, maquinaria, cultivos y más.<br>Accede con tu usuario o regístrate para comenzar.</p>
            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="px-6 py-2 bg-naranja-oscuro text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Ingresar</a>
                <a href="{{ route('register') }}" class="px-6 py-2 bg-azul-marino text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Registrarse</a>
            </div>
        </div>
    </div>
</div>
@endsection