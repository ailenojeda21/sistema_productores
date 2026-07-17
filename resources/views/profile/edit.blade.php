@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Perfil', 'route' => 'profile'],
        ['name' => 'Editar']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-naranja-oscuro mb-6">Editar Perfil</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form id="profile-form-desktop" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">badge</span>
                        Nombre completo
                    </label>
                    <input name="name" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">mail</span>
                        Correo electrónico
                    </label>
                    <input name="email" type="email"
                           class="w-full p-2 border border-gray-200 rounded bg-gray-50 text-gray-600"
                           value="{{ old('email', $user->email) }}" disabled>
                </div>

                <!-- DNI -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">credit_card</span>
                        DNI
                    </label>
                    <input name="dni" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('dni', $user->dni) }}" required>
                    @error('dni') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">phone</span>
                        Teléfono
                    </label>
                    <input name="telefono" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('telefono', $user->telefono) }}" required>
                    @error('telefono') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">home</span>
                        Dirección
                    </label>
                    <input name="direccion" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('direccion', $user->direccion) }}" required>
                    @error('direccion') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Creado (solo lectura) -->
                <div class="md:col-span-2">
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">calendar_today</span>
                        Cuenta creada
                    </label>
                    <input type="text"
                           class="w-full p-2 border border-gray-200 rounded bg-gray-50 text-gray-600"
                           value="{{ $user->created_at->format('d/m/Y H:i') }}"
                           readonly>
                </div>

                <!-- ¿Pertenece a alguna cooperativa? -->
                <div class="md:col-span-2 flex items-center mt-4">
                    <input type="checkbox" name="tiene_cooperativas" id="tiene_cooperativas" class="mr-2 rounded-full custom-checkbox"
                        {{ is_array(old('cooperativas')) && !empty(old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && !empty($user->cooperativas) ? 'checked' : '') }}>
                    <label for="tiene_cooperativas">¿Pertenece a alguna cooperativa?¿Cual?</label>
                </div>
            </div>

            <div id="cooperativa-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @foreach(\App\Models\User::getCooperativasForForm() as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array(old('cooperativas')) && in_array($label, old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && in_array($label, $user->cooperativas) ? 'checked' : '') }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="mt-6 w-full py-2 px-4 bg-[#F39200] text-white font-bold rounded hover:bg-[#E07F00] transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <x-breadcrumb :items="[
    ['name' => 'Perfil', 'route' => 'profile'],
    ['name' => 'Editar']
]" />
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-naranja-oscuro">Editar Perfil</h2>
       
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form id="profile-form-mobile" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')


            <div class="p-4 space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">badge</span>
                        Nombre completo
                    </label>
                    <input name="name" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">mail</span>
                        Correo electrónico
                    </label>
                    <input name="email" type="email"
                           class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                           value="{{ old('email', $user->email) }}" disabled>
                </div>

                <!-- DNI -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">credit_card</span>
                        DNI
                    </label>
                    <input name="dni" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('dni', $user->dni) }}" required>
                    @error('dni') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">phone</span>
                        Teléfono
                    </label>
                    <input name="telefono" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('telefono', $user->telefono) }}" required>
                    @error('telefono') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">home</span>
                        Dirección
                    </label>
                    <input name="direccion" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('direccion', $user->direccion) }}" required>
                    @error('direccion') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Creado (solo lectura) -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">calendar_today</span>
                        Cuenta creada
                    </label>
                    <input type="text"
                           class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                           value="{{ $user->created_at->format('d/m/Y H:i') }}"
                           readonly>
                </div>

                <!-- ¿Pertenece a alguna cooperativa? -->
                <div class="flex items-center">
                    <input type="checkbox" name="tiene_cooperativas" id="tiene_cooperativas_mobile" class="mr-2 rounded-full custom-checkbox"
                        {{ is_array(old('cooperativas')) && !empty(old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && !empty($user->cooperativas) ? 'checked' : '') }}>
                    <label for="tiene_cooperativas_mobile" class="text-sm">¿Pertenece a alguna cooperativa?¿Cual?</label>
                </div>
            </div>

            <div id="cooperativa-fields-mobile" class="hidden mt-4 px-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 gap-1">
                    @foreach(\App\Models\User::getCooperativasForForm() as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}_mobile" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array(old('cooperativas')) && in_array($label, old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && in_array($label, $user->cooperativas) ? 'checked' : '') }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t">
                <button type="submit"
                        class="w-full py-3 bg-[#F39200] text-white font-semibold rounded-lg hover:bg-[#E07F00] transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        outline: none !important;
        outline-style: none !important;
        box-shadow: none !important;
        -webkit-tap-highlight-color: transparent;
        transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
        margin: 0;
    }
    .custom-checkbox:hover {
        border-color: #F39200 !important;
    }
    .custom-checkbox:focus {
        outline: none !important;
        outline-style: none !important;
        box-shadow: 0 0 0 3px rgba(243, 146, 0, 0.3) !important;
        border-color: #F39200 !important;
    }
    .custom-checkbox:focus-visible {
        outline: none !important;
        outline-style: none !important;
        box-shadow: 0 0 0 3px rgba(243, 146, 0, 0.4) !important;
        border-color: #F39200 !important;
    }
    .custom-checkbox:checked {
        background-color: #F39200 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:checked:hover {
        background-color: #D97706 !important;
        border-color: #D97706 !important;
    }
    .custom-checkbox:checked:focus {
        background-color: #F39200 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:active {
        background-color: #FCE7A3 !important;
        border-color: #F39200 !important;
        box-shadow: none !important;
    }
    .custom-checkbox:checked:active {
        background-color: #FCE7A3 !important;
        border-color: #F39200 !important;
        box-shadow: 0 0 0 2px #FCE7A3 !important;
    }
    .custom-checkbox:disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        border-color: #e5e7eb !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cooperativaCheckbox = document.getElementById('tiene_cooperativas');
        const cooperativaFields = document.getElementById('cooperativa-fields');
        const cooperativaCheckboxMobile = document.getElementById('tiene_cooperativas_mobile');
        const cooperativaFieldsMobile = document.getElementById('cooperativa-fields-mobile');

        function toggleCooperativaFields() {
            cooperativaFields.classList.toggle('hidden', !cooperativaCheckbox.checked);
        }

        function toggleCooperativaFieldsMobile() {
            cooperativaFieldsMobile.classList.toggle('hidden', !cooperativaCheckboxMobile.checked);
        }

        if (cooperativaCheckbox) {
            cooperativaCheckbox.addEventListener('change', toggleCooperativaFields);
            toggleCooperativaFields();
        }

        if (cooperativaCheckboxMobile) {
            cooperativaCheckboxMobile.addEventListener('change', toggleCooperativaFieldsMobile);
            toggleCooperativaFieldsMobile();
        }

        // OLD (backup 2026-06-28): simple .text-red-600 query — replaced by robust version below
        // const firstError = document.querySelector('.text-red-600');
        // if (firstError) {
        //     const input = firstError.closest('div')?.querySelector('input, select, textarea');
        //     if (input) {
        //         input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        //         input.focus();
        //     }
        // }

        function isElementVisible(el) {
            while (el && el !== document.body) {
                const style = getComputedStyle(el);
                if (style.display === 'none' || style.visibility === 'hidden') {
                    return false;
                }
                el = el.parentElement;
            }
            return true;
        }

        function getVisibleForm() {
            const desktop = document.getElementById('profile-form-desktop');
            const mobile = document.getElementById('profile-form-mobile');
            for (const f of [desktop, mobile]) {
                if (f && isElementVisible(f)) {
                    return f;
                }
            }
            return null;
        }

        function getFirstInputWithError(form) {
            const inputs = form.querySelectorAll('input, select, textarea');
            for (const input of inputs) {
                const parent = input.closest('div');
                if (parent && parent.querySelector('.text-red-600')) {
                    return input;
                }
            }
            return null;
        }

        const form = getVisibleForm();

        if (form) {
            // Servidor: errores post-redirect
            const inputWithError = getFirstInputWithError(form);
            if (inputWithError) {
                inputWithError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                inputWithError.focus({ preventScroll: true });
            }

            // Nativo: required fields
            let firstInvalid = null;
            form.addEventListener('invalid', function(e) {
                if (!firstInvalid) {
                    firstInvalid = e.target;
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function() {
                            if (firstInvalid) {
                                console.log('[profile-edit] invalid event fired on:', firstInvalid);
                                console.log('[profile-edit] scrollY before:', window.scrollY);
                                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalid.focus({ preventScroll: true });
                                console.log('[profile-edit] scrollIntoView called, scrollY after:', window.scrollY);
                            }
                            firstInvalid = null;
                        });
                    });
                }
            }, true);
        }
    });
</script>
@endsection
