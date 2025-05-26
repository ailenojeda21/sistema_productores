@extends('layouts.app')
@section('styles')

@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="w-full max-w-md p-0 bg-white rounded-lg shadow-lg overflow-hidden sap-card animate-fade-in">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-center">
                <i class="material-icons mr-3 animate-bounce-slow" style="font-size:28px;">agriculture</i>
                <h2 class="text-2xl font-bold">Sistema Agrícola SAP</h2>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Ingrese sus credenciales para acceder al sistema de gestión agrícola.</p>
            </div>
        </div>

        <div class="p-6">
            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 animate-fade-in">
                    <div class="flex items-center">
                        <i class="material-icons text-green-500 mr-2" style="font-size:20px;">check_circle</i>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 animate-fade-in">
                    <div class="flex items-center">
                        <i class="material-icons text-red-500 mr-2" style="font-size:20px;">error</i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="sap-form-group">
                    <label class="sap-form-label" for="email">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">email</i>
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="sap-form-input @error('email') error @enderror">
                        <span class="input-focus-effect"></span>
                    </div>
                    @error('email')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="password">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">lock</i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="sap-form-input @error('password') error @enderror">
                        <span class="input-focus-effect"></span>
                    </div>
                    @error('password')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Recordarme
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <div class="pt-4">
                    <button type="submit" class="sap-btn sap-btn-primary w-full py-2" id="loginBtn">
                        <i class="material-icons mr-2" style="font-size:18px;">login</i>
                        <span id="loginBtnText">Ingresar</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                <span class="text-gray-600 text-sm">¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-800 ml-1 text-sm">
                    Regístrate aquí
                </a>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center text-gray-500 text-xs">
            <p>&copy; {{ date('Y') }} Sistema Agrícola SAP. Todos los derechos reservados.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const loginBtn = document.getElementById('loginBtn');
        const loginBtnText = document.getElementById('loginBtnText');
        const originalBtnText = loginBtnText.textContent;

        form.addEventListener('submit', function() {
            // Disable button and show loading state
            loginBtn.disabled = true;
            loginBtn.classList.add('opacity-75');
            loginBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Iniciando sesión...';

            // Return true to allow form submission
            return true;
        });
    });
</script>
@endsection
