@extends('layouts.app')

@section('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .animate-bounce-slow {
        animation: bounceSlow 3s infinite;
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
                <i class="material-icons mr-3 animate-bounce-slow" style="font-size:28px;">mark_email_read</i>
                <h2 class="text-2xl font-bold">Verificar Correo Electrónico</h2>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviar? Si no recibiste el correo, te enviaremos otro.</p>
            </div>
        </div>

        <div class="p-6">
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 animate-fade-in">
                    <div class="flex items-center">
                        <i class="material-icons text-green-500 mr-2" style="font-size:20px;">check_circle</i>
                        <p>Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.</p>
                    </div>
                </div>
            @endif

            <div class="mt-4 flex flex-col space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="sap-btn sap-btn-primary w-full py-2" id="resendBtn">
                        <i class="material-icons mr-2" style="font-size:18px;">send</i>
                        <span id="resendBtnText">Reenviar email de verificación</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sap-btn sap-btn-outline w-full py-2">
                        <i class="material-icons mr-2" style="font-size:18px;">logout</i>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center text-gray-500 text-xs">
            <p>&copy; {{ date('Y') }} Sistema Agrícola SAP. Todos los derechos reservados.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resendForm = document.querySelector('form[action*="verification.send"]');
        const resendBtn = document.getElementById('resendBtn');
        const resendBtnText = document.getElementById('resendBtnText');

        if (resendForm && resendBtn && resendBtnText) {
            resendForm.addEventListener('submit', function() {
                // Disable button and show loading state
                resendBtn.disabled = true;
                resendBtn.classList.add('opacity-75');
                resendBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Enviando...';

                // Return true to allow form submission
                return true;
            });
        }
    });
</script>
@endsection
