@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-azul-marino"> {{-- Fondo principal con tu azul-marino --}}
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-xl w-full">
        <h1 class="text-3xl font-bold text-amarillo-claro mb-4">Panel de Control</h1> {{-- Título amarillo-claro --}}
        <p class="text-gray-700 mb-6">Bienvenido al sistema agrícola. Desde aquí puedes acceder a la gestión de productores, propiedades, maquinaria, cultivos y tecnologías de riego.</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Botón Perfil --}}
            <a href="{{ route('archivos.index') }}" class="block px-6 py-4 bg-naranja-oscuro text-white rounded-lg text-center font-semibold shadow hover:bg-opacity-90 transition duration-300 ease-in-out">Perfil</a>
            
            {{-- Botón Propiedades --}}
            <a href="{{ route('propiedades.index') }}" class="block px-6 py-4 bg-naranja-oscuro text-white rounded-lg text-center font-semibold shadow hover:bg-opacity-90 transition duration-300 ease-in-out">Propiedades</a>
            
            {{-- Botón Maquinaria --}}
            <a href="{{ route('maquinaria.index') }}" class="block px-6 py-4 bg-naranja-oscuro text-white rounded-lg text-center font-semibold shadow hover:bg-opacity-90 transition duration-300 ease-in-out">Maquinaria</a>
            
            {{-- Botón Cultivos --}}
            <a href="{{ route('cultivos.index') }}" class="block px-6 py-4 bg-naranja-oscuro text-white rounded-lg text-center font-semibold shadow hover:bg-opacity-90 transition duration-300 ease-in-out">Cultivos</a>

        </div>
    </div>
</div>
@endsection