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

    /* Estilos para fortaleza de contraseña */
    .password-strength-meter {
        height: 4px;
        width: 100%;
        background: #DDD;
        margin-top: 6px;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-meter span {
        display: block;
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
    }

    .strength-weak span { width: 25%; background: #FF4136; }
    .strength-medium span { width: 50%; background: #FF851B; }
    .strength-good span { width: 75%; background: #FFDC00; }
    .strength-strong span { width: 100%; background: #2ECC40; }

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
                <i class="material-icons mr-3 animate-bounce-slow" style="font-size:28px;">lock_reset</i>
                <h2 class="text-2xl font-bold">Restablecer Contraseña</h2>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Ingrese su nueva contraseña para completar el restablecimiento.</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="sap-form-group">
                    <label class="sap-form-label" for="email">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">email</i>
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                            class="sap-form-input @error('email') error @enderror bg-gray-50">
                        <span class="input-focus-effect"></span>
                    </div>
                    @error('email')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="password">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">lock</i>
                        Nueva contraseña
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autofocus
                            class="sap-form-input @error('password') error @enderror">
                        <span class="input-focus-effect"></span>
                    </div>
                    <div id="password-strength-meter" class="password-strength-meter">
                        <span></span>
                    </div>
                    <p id="password-strength-text" class="text-xs text-gray-500 mt-1"></p>
                    @error('password')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="password_confirmation">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">lock_clock</i>
                        Confirmar nueva contraseña
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="sap-form-input">
                        <span class="input-focus-effect"></span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="sap-btn sap-btn-primary w-full py-2" id="resetBtn">
                        <i class="material-icons mr-2" style="font-size:18px;">save</i>
                        <span id="resetBtnText">Restablecer contraseña</span>
                    </button>
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
        // Form submit handling
        const form = document.querySelector('form');
        const resetBtn = document.getElementById('resetBtn');
        const resetBtnText = document.getElementById('resetBtnText');

        form.addEventListener('submit', function() {
            // Disable button and show loading state
            resetBtn.disabled = true;
            resetBtn.classList.add('opacity-75');
            resetBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Procesando...';

            // Return true to allow form submission
            return true;
        });

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordStrengthMeter = document.getElementById('password-strength-meter');
        const passwordStrengthText = document.getElementById('password-strength-text');

        if (passwordInput && passwordStrengthMeter && passwordStrengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);
                updatePasswordStrengthUI(strength);
            });

            // Add match validation
            if (passwordConfirmInput) {
                passwordConfirmInput.addEventListener('input', function() {
                    if (passwordInput.value !== this.value) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }
        }

        function checkPasswordStrength(password) {
            let strength = 0;

            if (password.length === 0) {
                return 0;
            }

            // Length contribution
            if (password.length >= 8) {
                strength += 1;
            }

            // Complexity contributions
            if (/[a-z]/.test(password)) strength += 1; // lowercase
            if (/[A-Z]/.test(password)) strength += 1; // uppercase
            if (/[0-9]/.test(password)) strength += 1; // numbers
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1; // special chars

            return Math.min(strength, 4); // Max strength is 4
        }

        function updatePasswordStrengthUI(strength) {
            // Remove all classes
            passwordStrengthMeter.className = 'password-strength-meter';

            // Add appropriate class based on strength
            if (strength === 0) {
                passwordStrengthMeter.className += '';
                passwordStrengthText.textContent = '';
            } else if (strength === 1) {
                passwordStrengthMeter.className += ' strength-weak';
                passwordStrengthText.textContent = 'Débil';
                passwordStrengthText.className = 'text-xs text-red-600 mt-1';
            } else if (strength === 2) {
                passwordStrengthMeter.className += ' strength-medium';
                passwordStrengthText.textContent = 'Media';
                passwordStrengthText.className = 'text-xs text-orange-600 mt-1';
            } else if (strength === 3) {
                passwordStrengthMeter.className += ' strength-good';
                passwordStrengthText.textContent = 'Buena';
                passwordStrengthText.className = 'text-xs text-yellow-600 mt-1';
            } else {
                passwordStrengthMeter.className += ' strength-strong';
                passwordStrengthText.textContent = 'Fuerte';
                passwordStrengthText.className = 'text-xs text-green-600 mt-1';
            }
        }
    });
</script>
@endsection
