@extends('layouts.app')

@section('no_header', true)

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
    <div class="hidden md:flex md:w-1/2 w-full bg-gray-100 items-center justify-center p-0 overflow-hidden" style="height: 100vh; max-height: 100vh;">
        <img src="{{ asset('images/rupal.png') }}" alt="Logo Municipalidad de Lavalle" class="w-full h-full object-cover" style="max-height: 100vh;">
    </div>
    
    <div class="w-full md:w-1/2 flex flex-col justify-center relative p-8 bg-white min-h-screen">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-8 right-8 h-20">
        
        <div class="max-w-md w-full mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <span class="block text-xs md:text-sm font-semibold text-gray-400 uppercase tracking-widest mb-1">
                    Bienvenidos al
                </span>
                
                <h1 class="text-2xl md:text-3xl font-bold text-amarillo-claro leading-tight mb-3">
                    Registro Único de Productores Agropecuarios de Lavalle
                </h1>
                
            
                <div class="inline-block mt-1 px-4 py-2 border border-amarillo-claro/30 bg-amarillo-claro/5 rounded-lg shadow-inner">
                    <span class="text-2xl md:text-3xl font-black text-amarillo-claro tracking-tight">
                        R U P A L
                    </span>
                </div>
            </div>

            <p class="text-gray-700 mb-10 text-center leading-relaxed">
                Gestiona propiedades, maquinarias, cultivos y más.<br>
                <span class="text-sm opacity-80 italic">Accede con tu usuario o regístrate para comenzar.</span>
            </p>

            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="px-6 py-2 bg-naranja-oscuro text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Ingresar</a>
                <a href="{{ route('register') }}" class="px-6 py-2 bg-azul-marino text-white rounded hover:bg-opacity-90 font-semibold shadow transition duration-300 ease-in-out">Registrarse</a>
            </div>
        </div>
    </div>
</div>
@endsection