@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[ ['name' => 'Perfil', 'route' => 'profile'] ]" />
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-naranja-oscuro">Perfil de Usuario</h1>
    </div>

    <x-session-messages />

    <div class="bg-white rounded-lg shadow p-4 md:p-6">

        {{-- Contenedor Principal: Invertido en mobile (flex-col-reverse) --}}
        <div class="flex flex-col-reverse lg:flex-row gap-8 items-start">
            
            {{-- Información del usuario --}}
            <div class="flex-1 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">badge</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nombre</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">mail</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Email</span>
                            <p class="text-sm text-gray-700 font-medium">
                                {{ $user->email }}
                                @if($user->hasVerifiedEmail())
                                    <span class="material-symbols-outlined text-green-500 align-middle text-lg ml-1" title="Verificado">verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">credit_card</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">DNI</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->dni ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">phone</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Teléfono</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->telefono ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">home</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Dirección</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->direccion ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">calendar_today</span>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Creado</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t pt-4 lg:border-none lg:pt-0">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-naranja-oscuro shrink-0">groups</span>
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
                                        <span class="{{ $index >= $displayLimit ? 'hidden cooperativa-extra' : '' }} inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-[10px] sm:text-xs rounded-full font-medium">
                                            {{ $cooperativa }}
                                        </span>
                                    @endforeach
                                    
                                    @if($remaining > 0)
                                        <button id="toggle-cooperativas-btn" onclick="toggleCooperativas()" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium hover:bg-gray-200">
                                            +{{ $remaining }}
                                        </button>
                                        <button id="show-less-btn" onclick="toggleCooperativas()" class="hidden text-xs text-[#F39200] font-semibold hover:underline ml-1">
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
                <div class="bg-gray-50 border-2 border-[#F39200]/25 rounded-xl p-4 lg:p-6 w-full flex flex-col items-center shadow-sm">
                    <x-user-avatar :user="$user" size="lg" :gradient="false" :showName="false" :yellow-only="true" />
               

                <div class="mt-3 text-base font-bold text-gray-700 uppercase tracking-tighter">Avatar</div>

                <a href="{{ route('profile.avatar') }}"
                    class="mt-3 px-4 py-2 bg-[#F39200] text-white text-xs font-semibold rounded-lg hover:bg-[#E07F00] transition-all duration-300 flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-sm">photo_camera</span>
                    Editar foto
                </a>
            </div>
             </div>
        </div>

        {{-- Acciones del perfil --}}
        <div class="flex flex-col items-center mt-10 pt-6 border-t w-full">
    <div class="flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 items-center w-full sm:w-auto">
        <div class="relative group/dl w-full sm:w-auto">
            <button onclick="printCertificate()"
                {{ $user->canDownloadCertificate() ? '' : 'disabled' }}
                id="btn-download"
                ontouchstart="toggleTooltip(event, this)"
                class="w-full sm:w-auto h-11 justify-center px-5 bg-[#223362] text-white rounded-lg hover:bg-[#1A2850] transition-all duration-200 font-bold shadow-lg flex items-center gap-2 text-sm whitespace-nowrap {{ $user->canDownloadCertificate() ? '' : 'opacity-50 cursor-not-allowed' }}">
                <span class="material-symbols-outlined text-lg shrink-0">download</span>
                <span>Descargar comprobante</span>
            </button>
            @if(!$user->canDownloadCertificate())
                <div id="tooltip-download"
                     class="pointer-events-none opacity-0 group-hover/dl:opacity-100 transition-opacity duration-200 absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-64 sm:w-72 z-50 tooltip-download">
                    <div class="bg-gray-800 text-white text-xs leading-relaxed rounded-md px-3 py-2 text-center shadow-lg">
                        Debes completar tus registros para poder descargar el comprobante.
                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-t-[6px] border-t-gray-800"></div>
                    </div>
                </div>
            @endif
        </div>
        <a href="{{ route('profile.edit') }}"
            class="w-full sm:w-auto h-11 justify-center px-5 bg-white text-[#F39200] border-2 border-[#F39200] rounded-lg hover:bg-orange-50 transition-all duration-200 font-bold flex items-center gap-2 text-sm whitespace-nowrap">
            <span class="material-symbols-outlined text-lg shrink-0">edit</span>
            <span>Editar perfil</span>
        </a>
    </div>
</div>
    {{-- Comprobante PDF oculto en pantalla, visible al imprimir --}}
    @include('profile.partials.pdf-certificate')

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

        function toggleTooltip(e, btn) {
            e.preventDefault();
            const tip = document.getElementById('tooltip-download');
            if (!tip) return;
            const isVisible = tip.classList.contains('mobile-show');
            tip.classList.toggle('mobile-show');
            const closeOnOutside = (ev) => {
                if (!btn.contains(ev.target) && !tip.contains(ev.target)) {
                    tip.classList.remove('mobile-show');
                    document.removeEventListener('touchstart', closeOnOutside);
                }
            };
            if (!isVisible) {
                setTimeout(() => document.addEventListener('touchstart', closeOnOutside), 10);
            }
        }
    </script>
</div>
@endsection