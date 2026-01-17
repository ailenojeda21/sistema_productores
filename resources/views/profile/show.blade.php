@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Perfil', 'route' => 'profile']
    ]" />
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Perfil de Usuario</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            {{-- Información del usuario --}}
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">badge</span>
                        <div>
                            <span class="text-xs text-gray-500">Nombre:</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">mail</span>
                        <div>
                            <span class="text-xs text-gray-500">Email:</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">credit_card</span>
                        <div>
                            <span class="text-xs text-gray-500">DNI:</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->dni ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">phone</span>
                        <div>
                            <span class="text-xs text-gray-500">Teléfono:</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->telefono ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">calendar_today</span>
                        <div>
                            <span class="text-xs text-gray-500">Creado:</span>
                            <p class="text-sm text-gray-700 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-gray-500 shrink-0">groups</span>
                        <div class="flex-1">
                            <span class="text-xs text-gray-500 block mb-2">Cooperativas a las que pertenece:</span>
                            @if(is_array($user->cooperativas) && count($user->cooperativas) > 0)
                                <div id="cooperativas-container" class="flex flex-wrap gap-2 items-center">
                                    @php
                                        $displayLimit = 6;
                                        $cooperativas = $user->cooperativas;
                                        $hasMore = count($cooperativas) > $displayLimit;
                                        $remaining = count($cooperativas) - $displayLimit;
                                    @endphp
                                    @foreach($cooperativas as $index => $cooperativa)
                                        @if($index < $displayLimit)
                                            <span class="inline-flex items-center px-3 py-0.5 bg-azul-marino text-white text-xs rounded-full font-medium">
                                                {{ $cooperativa }}
                                            </span>
                                        @elseif($index === $displayLimit)
                                            <button id="toggle-cooperativas-btn" onclick="toggleCooperativas()" class="inline-flex items-center px-3 py-0.5 bg-gray-200 text-gray-700 text-xs rounded-full font-medium hover:bg-gray-300 cursor-pointer">
                                                +{{ $remaining }}
                                            </button>
                                            <span class="hidden cooperativa-extra inline-flex items-center px-3 py-0.5 bg-azul-marino text-white text-xs rounded-full font-medium">
                                                {{ $cooperativa }}
                                            </span>
                                        @else
                                            <span class="hidden cooperativa-extra inline-flex items-center px-3 py-0.5 bg-azul-marino text-white text-xs rounded-full font-medium">
                                                {{ $cooperativa }}
                                            </span>
                                        @endif
                                    @endforeach
                                    <button id="show-less-btn" onclick="toggleCooperativas()" class="hidden text-xs text-azul-marino font-medium hover:underline cursor-pointer ml-1">
                                        Ver menos
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-500 text-sm">No pertenece a ninguna cooperativa</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Avatar --}}
            <div class="flex flex-col items-center lg:w-48 shrink-0">
                <div class="bg-gray-50 rounded-lg p-6 w-full flex flex-col items-center">
                    <x-user-avatar :user="$user" size="lg" :gradient="false" :showName="false" :yellow-only="true" />
                    <div class="mt-4 text-lg font-semibold text-gray-800">Avatar</div>
                    <a href="{{ route('profile.avatar') }}"
                       class="mt-3 px-3 py-1.5 text-gray-600 text-sm border border-gray-600 rounded hover:text-azul-marino hover:border-azul-marino transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                        Editar avatar
                    </a>
                </div>
            </div>
        </div>

        {{-- Botón editar perfil --}}
        <div class="flex justify-center mt-8 pt-4 border-t">
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">edit</span>
                Editar perfil
            </a>
        </div>
    </div>

    <script>
        function toggleCooperativas() {
            const extras = document.querySelectorAll('.cooperativa-extra');
            const toggleBtn = document.getElementById('toggle-cooperativas-btn');
            const showLessBtn = document.getElementById('show-less-btn');

            extras.forEach(el => {
                el.classList.toggle('hidden');
            });

            if (toggleBtn && showLessBtn) {
                toggleBtn.classList.toggle('hidden');
                showLessBtn.classList.toggle('hidden');
            }
        }
    </script>
</div>
@endsection
