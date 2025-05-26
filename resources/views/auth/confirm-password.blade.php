@extends('layouts.app')

@section('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .animate-bounce-slow {
        animation: bounceSlow 3s infinite;
    }

    .sap-form-input:focus + .input-focus-effect {
        width: 100%;
    }

    .input-focus-effect {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--sap-primary);
        transition: width 0.3s ease-in-out;
    }

    .sap-btn {
        position: relative;
        overflow: hidden;
    }

    .sap-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%, -50%);
        transform-origin: 50% 50%;
    }

    .sap-btn:focus:not(:active)::after {
        animation: ripple 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounceSlow {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        20% {
            transform: scale(25, 25);
            opacity: 0.5;
        }
        100% {
            opacity: 0;
            transform: scale(40, 40);
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="w-full max-w-md p-0 bg-white rounded-lg shadow-lg overflow-hidden sap-card animate-fade-in">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-center">
                <i class="material-icons mr-3 animate-bounce-slow" style="font-size:28px;">security</i>
                <h2 class="text-2xl font-bold">Confirmar Contraseña</h2>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.</p>
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

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div class="sap-form-group">
                    <label class="sap-form-label" for="password">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">lock</i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="sap-form-input @error('password') error @enderror">
                        <span class="input-focus-effect"></span>
                    </div>
                    @error('password')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="sap-btn sap-btn-primary w-full py-2" id="confirmBtn">
                        <i class="material-icons mr-2" style="font-size:18px;">check_circle</i>
                        <span id="confirmBtnText">Confirmar contraseña</span>
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center text-gray-500 text-xs">
            <p>&copy; {{ date('Y') }} Sistema Agrícola SAP. Todos los derechos reservados.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const confirmBtn = document.getElementById('confirmBtn');
        const confirmBtnText = document.getElementById('confirmBtnText');

        form.addEventListener('submit', function() {
            // Disable button and show loading state
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-75');
            confirmBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Verificando...';

            // Return true to allow form submission
            return true;
        });
    });
</script>
@endsection
