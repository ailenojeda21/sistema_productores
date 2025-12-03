@extends('layouts.app')

@section('no_header', true)

@section('content')
<!-- Mobile View -->
<x-content-mobile 
    title="Restablecer Contraseña"
    subtitle="Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña."
>
    <form method="POST" action="{{ route('password.email') }}" class="w-full max-w-xs mx-auto">
        @csrf
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <!-- Email Input -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-100 mb-2">Correo Electrónico</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus
                   class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 
                          text-white placeholder-white placeholder-opacity-70 focus:outline-none focus:ring-2 
                          focus:ring-white focus:ring-opacity-50 transition duration-200"
                   placeholder="tucorreo@ejemplo.com">
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full py-3.5 bg-white text-azul-marino rounded-lg font-semibold 
                       hover:bg-opacity-90 transition duration-200 focus:outline-none focus:ring-2 
                       focus:ring-white focus:ring-offset-2 focus:ring-offset-azul-marino">
            Enviar enlace de recuperación
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" 
           class="text-sm text-white text-opacity-80 hover:text-opacity-100 font-medium transition duration-200">
            ← Volver al inicio de sesión
        </a>
    </div>
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
        <a href="{{ route('home') }}" class="absolute top-8 right-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto">
        </a>
        
        <div class="max-w-md w-full mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-azul-marino mb-2">
                    ¿Olvidaste tu contraseña?
                </h1>
                <p class="text-gray-600">
                    No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <!-- Email Input -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-azul-marino 
                                  focus:ring focus:ring-azul-marino focus:ring-opacity-50 transition duration-200"
                           placeholder="tucorreo@ejemplo.com">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 bg-azul-marino text-white rounded-lg font-semibold 
                               hover:bg-opacity-90 transition duration-200 focus:outline-none focus:ring-2 
                               focus:ring-azul-marino focus:ring-offset-2">
                    Enviar enlace de recuperación
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" 
                   class="text-sm text-azul-marino font-medium hover:text-opacity-80 transition duration-200">
                    ← Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
