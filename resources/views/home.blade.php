@extends('layouts.app')

@section('no_header', true)

@section('content')
<!-- Mobile View -->
<x-content-mobile 
    title="Sistema Agrícola Municipal de Lavalle"
    subtitle="Gestiona tu producción agrícola de manera eficiente con nuestra plataforma integral."
>
</x-content-mobile>

<!-- Desktop View -->
<div class="hidden md:flex min-h-screen">
    <!-- Left Side - Image -->
    <div class="w-1/2 bg-gray-100 overflow-hidden">
        <img src="{{ asset('images/fruta2.png') }}" alt="Fondo agrícola" class="w-full h-full object-cover">
    </div>
    
    <!-- Right Side - Content -->
    <div class="w-1/2 flex flex-col justify-center p-8 bg-white relative">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-8 right-8 h-20 w-auto">
        
        <div class="max-w-md w-full mx-auto">
            <h1 class="text-3xl font-bold text-azul-marino mb-6 text-center">
                Sistema Agrícola Municipal<br>de Lavalle
            </h1>
            
            <p class="text-gray-600 text-center mb-8">
                Gestiona tu producción agrícola de manera eficiente con nuestra plataforma integral.
            </p>
            
            <!-- Login Button -->
            <div class="mb-6">
                <a href="{{ route('login') }}" 
                   class="block w-full px-6 py-3.5 bg-gradient-to-r from-naranja-oscuro to-amber-700 text-white rounded-lg 
                          font-semibold text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    Iniciar Sesión
                </a>
            </div>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">¿Nuevo en la plataforma?</span>
                </div>
            </div>
            
            <!-- Register Button -->
            <div class="mb-8">
                <a href="{{ route('register') }}" 
                   class="block w-full px-6 py-3 bg-white text-azul-marino rounded-lg font-medium text-center 
                          border-2 border-azul-marino hover:bg-azul-marino hover:text-white 
                          transition-all duration-300">
                    Crear Cuenta
                </a>
            </div>
            
            <!-- Forgot Password -->
            <div class="text-center">
                <a href="{{ route('password.request') }}" 
                   class="text-sm text-gray-500 hover:text-azul-marino transition-colors duration-200">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
