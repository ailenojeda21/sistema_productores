@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-azul-marino"> {{-- Fondo principal con tu azul-marino --}}
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-lg w-full flex flex-col items-center">
        {{-- Aquí se usa el helper asset() para la imagen, si la ruta es correcta --}}
        {{--   <img src="" alt="Logo" class="w-24 h-24 mb-4"> --}}
      
        
        <h1 class="text-3xl font-bold text-amarillo-claro mb-2">Bienvenido al Sistema Agrícola</h1> {{-- Título amarillo-claro --}}
        
        <p class="text-gray-700 mb-6 text-center">Gestiona productores, propiedades, maquinaria, cultivos y más.<br>Accede con tu usuario o regístrate para comenzar.</p>
        
        <div class="flex space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-2 bg-naranja-oscuro text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Ingresar</a> {{-- Botón naranja-oscuro para la acción principal --}}
            
            <a href="{{ route('register') }}" class="px-6 py-2 bg-azul-marino text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Registrarse</a> {{-- Botón azul-marino para la acción secundaria --}}
        </div>
    </div>
</div>
@endsection