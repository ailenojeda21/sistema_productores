@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[ ['name' => 'Perfil', 'route' => 'profile'] ]" />
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-azul-marino">Perfil de Usuario</h1>
    </div>

    <!-- Mensajes de error/éxito -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4 md:p-6">

        {{-- Contenedor Principal: Invertido en mobile (flex-col-reverse) --}}
        <div class="flex flex-col-reverse lg:flex-row gap-8 items-start">
            
            {{-- Información del usuario --}}
            <div class="flex-1 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">badge</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nombre</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">mail</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Email</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">credit_card</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">DNI</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->dni ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">phone</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Teléfono</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->telefono ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">calendar_today</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Creado</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t pt-4 lg:border-none lg:pt-0">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-gray-400 shrink-0">groups</span>
                        <div class="flex-1">
                            <span class="text-xs text-gray-500 block mb-2">Cooperativas</span>
                            @if(is_array($user->cooperativas) && count($user->cooperativas) > 0)
                                <div id="cooperativas-container" class="flex flex-wrap gap-2 items-center">
                                    @php
                                        $displayLimit = 6;
                                        $cooperativas = $user->cooperativas;
                                        $remaining = count($cooperativas) - $displayLimit;
                                    @endphp
                                    @foreach($cooperativas as $index => $cooperativa)
                                        <span class="{{ $index >= $displayLimit ? 'hidden cooperativa-extra' : '' }} inline-flex items-center px-3 py-1 bg-azul-marino text-white text-[10px] sm:text-xs rounded-full font-medium">
                                            {{ $cooperativa }}
                                        </span>
                                    @endforeach
                                    
                                    @if($remaining > 0)
                                        <button id="toggle-cooperativas-btn" onclick="toggleCooperativas()" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium hover:bg-gray-200">
                                            +{{ $remaining }}
                                        </button>
                                        <button id="show-less-btn" onclick="toggleCooperativas()" class="hidden text-xs text-azul-marino font-semibold hover:underline ml-1">
                                            Ver menos
                                        </button>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm italic">No pertenece a ninguna cooperativa</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bloque Avatar: Primero en mobile, Lateral en desktop --}}
            <div class="w-full lg:w-48 shrink-0 flex flex-col items-center">
                <div class="bg-gradient-to-br from-azul-marino to-blue-600 lg:bg-transparent rounded-xl p-4 lg:p-6 w-full flex flex-col items-center lg:border-none">
                    <div class="relative group">
                        <x-user-avatar :user="$user" size="lg" :gradient="false" :showName="false" :yellow-only="true" />
                        {{-- Icono flotante opcional para mobile (puedes borrar el <a> de abajo si prefieres este) --}}
                    </div>
                    
                    <div class="mt-3 text-base font-bold text-white uppercase tracking-tighter">Avatar</div>
                    
                    <a href="{{ route('profile.avatar') }}"
                       class="mt-3 px-4 py-2 bg-white lg:bg-gray-100 text-azul-marino lg:text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-100 lg:hover:bg-azul-marino lg:hover:text-white transition-all duration-300 flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                        Editar foto
                    </a>
                </div>
            </div>
        </div>

        {{-- Botón editar perfil: Acción principal con el nuevo hover --}}
       <div class="flex justify-center mt-10 pt-6 border-t w-full">
    <a href="{{ route('profile.edit') }}"
       class="w-full sm:w-auto justify-center px-8 py-3 bg-naranja-oscuro text-white rounded-lg hover:bg-amarillo-claro transition-all duration-200 font-bold shadow-lg flex items-center gap-2">
        <span class="material-symbols-outlined">edit</span>
        Editar perfil
    </a>
</div>
    </div>

    <script>
        function toggleCooperativas() {
            const extras = document.querySelectorAll('.cooperativa-extra');
            const toggleBtn = document.getElementById('toggle-cooperativas-btn');
            const showLessBtn = document.getElementById('show-less-btn');

            extras.forEach(el => el.classList.toggle('hidden'));
            if (toggleBtn && showLessBtn) {
                toggleBtn.classList.toggle('hidden');
                showLessBtn.classList.toggle('hidden');
            }
        }
    </script>
</div>
@endsection