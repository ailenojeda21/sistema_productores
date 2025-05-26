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

    /* Two-factor specific styles */
    .digit-input-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .digit-input {
        width: 2.5rem;
        height: 3rem;
        font-size: 1.5rem;
        text-align: center;
        border-radius: 0.25rem;
        border: 1px solid var(--sap-border);
        background-color: white;
        transition: all 0.2s ease-in-out;
    }

    .digit-input:focus {
        outline: none;
        border-color: var(--sap-primary);
        box-shadow: 0 0 0 3px rgba(10, 110, 209, 0.2);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="w-full max-w-md p-0 bg-white rounded-lg shadow-lg overflow-hidden sap-card animate-fade-in">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-center">
                <i class="material-icons mr-3 animate-bounce-slow" style="font-size:28px;">security</i>
                <h2 class="text-2xl font-bold">Autenticación de dos factores</h2>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Ingresa el código de autenticación proporcionado por tu aplicación autenticadora.</p>
            </div>
        </div>

        <div class="p-6">            @if(session('status'))
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

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4" id="twoFactorForm">
                @csrf

                <div class="sap-form-group">
                    <label class="sap-form-label" for="code">
                        <i class="material-icons align-middle text-blue-600 mr-1" style="font-size:18px;">pin</i>
                        Código de autenticación
                    </label>

                    <div class="digit-input-container">
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                        <input type="text" class="digit-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required>
                    </div>

                    <input id="code" type="hidden" name="code" required>

                    @error('code')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="sap-btn sap-btn-primary w-full py-2" id="verifyBtn">
                        <i class="material-icons mr-2" style="font-size:18px;">login</i>
                        <span id="verifyBtnText">Verificar</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">¿Perdiste tu dispositivo de autenticación?</p>
                <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                    @csrf
                    <button type="submit" class="sap-btn sap-btn-outline w-full py-2 text-sm">
                        <i class="material-icons mr-2" style="font-size:16px;">restore</i>
                        Usar código de recuperación
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
        const form = document.getElementById('twoFactorForm');
        const digitInputs = document.querySelectorAll('.digit-input');
        const codeInput = document.getElementById('code');
        const verifyBtn = document.getElementById('verifyBtn');
        const verifyBtnText = document.getElementById('verifyBtnText');

        // Focus on first input when page loads
        if (digitInputs.length > 0) {
            digitInputs[0].focus();
        }

        // Setup event listeners for digit inputs
        digitInputs.forEach((input, index) => {
            // Auto-focus next input when a digit is entered
            input.addEventListener('input', function() {
                if (this.value.length === this.maxLength) {
                    if (index < digitInputs.length - 1) {
                        digitInputs[index + 1].focus();
                    }
                }

                // Combine all digits into the hidden input
                updateCodeInput();
            });

            // Handle backspace to go to previous input
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    digitInputs[index - 1].focus();
                }
            });

            // Validate input to allow only numbers
            input.addEventListener('keypress', function(e) {
                if (isNaN(e.key) || e.key === ' ') {
                    e.preventDefault();
                    return false;
                }
            });

            // Paste handling for the entire code
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                if (/^\d+$/.test(pastedData)) {
                    const digits = pastedData.split('');
                    digits.forEach((digit, i) => {
                        if (i < digitInputs.length) {
                            digitInputs[i].value = digit;
                        }
                    });
                    // Focus on last field or the next empty one
                    const lastFilledIndex = Math.min(digits.length, digitInputs.length) - 1;
                    digitInputs[lastFilledIndex].focus();

                    // Update the hidden input
                    updateCodeInput();
                }
            });
        });

        // Function to combine all digits into the hidden input
        function updateCodeInput() {
            let code = '';
            digitInputs.forEach(input => {
                code += input.value;
            });
            codeInput.value = code;
        }

        // Form submission handling
        form.addEventListener('submit', function(e) {
            updateCodeInput();

            // Check if all digits are filled
            let isComplete = true;
            digitInputs.forEach(input => {
                if (input.value.length === 0) {
                    isComplete = false;
                }
            });

            if (!isComplete) {
                e.preventDefault();
                alert('Por favor, ingrese el código completo de 6 dígitos.');
                return false;
            }

            // Disable button and show loading state
            verifyBtn.disabled = true;
            verifyBtn.classList.add('opacity-75');
            verifyBtnText.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Verificando...';

            // Allow form submission
            return true;
        });
    });
</script>
@endsection
