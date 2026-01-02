@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Perfil</h2>
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="name">Nombre</label>
                    <input id="name" name="name" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" class="w-full p-2 border border-gray-300 rounded" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="dni">DNI</label>
                    <input id="dni" name="dni" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('dni', $user->dni) }}">
                    @error('dni')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1" for="telefono">Telefono</label>
                    <input id="telefono" name="telefono" type="text" class="w-full p-2 border border-gray-300 rounded" value="{{ old('telefono', $user->telefono) }}">
                    @error('telefono')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-3" for="tenencia">Tipo de tenencia de la propiedad</label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tipo_tenencia" value="propietario" class="mr-2 tenencia-radio" {{ old('tipo_tenencia', $user->tipo_tenencia) == 'propietario' ? 'checked' : '' }}>
                            <span class="text-gray-700">Propietario</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tipo_tenencia" value="arrendatario" class="mr-2 tenencia-radio" {{ old('tipo_tenencia', $user->tipo_tenencia) == 'arrendatario' ? 'checked' : '' }}>
                            <span class="text-gray-700">Arrendatario</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tipo_tenencia" value="otros" class="mr-2 tenencia-radio" {{ old('tipo_tenencia', $user?->tipo_tenencia) == 'otros' ? 'checked' : '' }}>
                            <span class="text-gray-700">Otro</span>
                            <div id="otros-tenencia-container" class="ml-6 {{ old('tipo_tenencia', $user?->tipo_tenencia) == 'otros' ? '' : 'hidden' }}">
                                <input id="especificar_tenencia" name="especificar_tenencia" type="text" class="w-96 p-2 border border-gray-300 rounded text-sm" placeholder="Especifique la condición" value="{{ old('especificar_tenencia', $user?->especificar_tenencia) }}">
                            </div>
                        </label>

                    </div>
                    @error('tipo_tenencia')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    @error('especificar_tenencia')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar Cambios</button>
        </form>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-azul-marino">Mi Perfil</h2>
        <a href="{{ route('profile.avatar') }}" class="p-2 bg-azul-marino text-white rounded-full shadow-lg">
            <span class="material-symbols-outlined">photo_camera</span>
        </a>
    </div>
    
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-green-700">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form method="POST" action="{{ route('profile.update') }}" id="profileFormMobile">
            @csrf
            @method('PATCH')
            
            <!-- Avatar Section using Component -->
            <x-user-avatar :user="$user" size="lg" :showEmail="true" />
            
            <!-- Form Fields -->
            <div class="p-4 space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm" for="name-mobile">
                        <span class="material-symbols-outlined text-azul-marino mr-2 text-xl">badge</span>
                        Nombre completo
                    </label>
                    <input id="name-mobile" name="name" type="text" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-marino focus:border-transparent" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm" for="email-mobile">
                        <span class="material-symbols-outlined text-azul-marino mr-2 text-xl">mail</span>
                        Correo electrónico
                    </label>
                    <input id="email-mobile" name="email" type="email" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-marino focus:border-transparent" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- DNI -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm" for="dni-mobile">
                        <span class="material-symbols-outlined text-azul-marino mr-2 text-xl">credit_card</span>
                        DNI
                    </label>
                    <input id="dni-mobile" name="dni" type="text" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-marino focus:border-transparent" 
                           value="{{ old('dni', $user->dni) }}">
                    @error('dni')
                        <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Tipo de Tenencia -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-3 text-sm">
                        <span class="material-symbols-outlined text-azul-marino mr-2 text-xl">home_work</span>
                        Tipo de tenencia de la propiedad
                    </label>
                    <div class="space-y-3 ml-9">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tipo_tenencia" value="propietario" class="mr-3 tenencia-radio-mobile" {{ old('tipo_tenencia', $user->tipo_tenencia) == 'propietario' ? 'checked' : '' }}>
                            <span class="text-gray-700">Propietario</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="tipo_tenencia" value="arrendatario" class="mr-3 tenencia-radio-mobile" {{ old('tipo_tenencia', $user->tipo_tenencia) == 'arrendatario' ? 'checked' : '' }}>
                            <span class="text-gray-700">Arrendatario</span>
                        </label>
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="tipo_tenencia" value="otros" class="mr-3 tenencia-radio-mobile" {{ old('tipo_tenencia', $user->tipo_tenencia) == 'otros' ? 'checked' : '' }}>
                                <span class="text-gray-700">Otro</span>
                                <div id="otros-tenencia-container-mobile" class="ml-6 {{ old('tipo_tenencia', $user->tipo_tenencia) == 'otros' ? '' : 'hidden' }}">
                                    <input id="especificar_tenencia_mobile" name="especificar_tenencia" type="text" 
                                           class="w-40 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-marino focus:border-transparent text-sm" 
                                           placeholder="Especifique la condición"
                                           value="{{ old('especificar_tenencia', $user->especificar_tenencia) }}">
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('tipo_tenencia')
                        <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                    @error('especificar_tenencia')
                        <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-azul-marino text-white font-semibold rounded-lg shadow-md hover:bg-blue-800 transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 0 0 0 #2563eb;
        cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px #93c5fd;
    }
    
    .custom-checkbox-mobile {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 0.375rem;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none;
        outline: none;
        transition: all 0.2s;
        cursor: pointer;
        position: relative;
    }
    .custom-checkbox-mobile:checked {
        background-color: #223362;
        border-color: #223362;
    }
    .custom-checkbox-mobile:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 1rem;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tenenciaRadios = document.querySelectorAll('.tenencia-radio');
        const otrosContainer = document.getElementById('otros-tenencia-container');
        
        const tenenciaRadiosMobile = document.querySelectorAll('.tenencia-radio-mobile');
        const otrosContainerMobile = document.getElementById('otros-tenencia-container-mobile');
        
        function toggleOtrosField() {
            const isOtros = document.querySelector('.tenencia-radio:checked')?.value === 'otros';
            otrosContainer.classList.toggle('hidden', !isOtros);
        }
        
        function toggleOtrosFieldMobile() {
            const isOtros = document.querySelector('.tenencia-radio-mobile:checked')?.value === 'otros';
            otrosContainerMobile.classList.toggle('hidden', !isOtros);
        }
        
        tenenciaRadios.forEach(radio => {
            radio.addEventListener('change', toggleOtrosField);
        });
        
        tenenciaRadiosMobile.forEach(radio => {
            radio.addEventListener('change', toggleOtrosFieldMobile);
        });
        
        // Inicializar al cargar
        toggleOtrosField();
        toggleOtrosFieldMobile();
    });
</script>
@endsection
