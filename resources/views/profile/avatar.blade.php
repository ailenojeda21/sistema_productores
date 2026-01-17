@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-4xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Perfil', 'route' => 'profile'],
        ['name' => 'Avatar']
    ]" />

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-azul-marino/10 to-blue-100/20 px-6 py-4 border-b border-blue-100">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-xl text-azul-marino">face</span>
                Seleccionar Avatar
            </h2>
        </div>

        <form method="POST" action="{{ route('profile.updateAvatar') }}" class="p-6">
            @csrf

            @php
                $avatars = ['uno.png', 'dos.png', 'tres.png', 'cuatro.png', 'cinco.png'];
                $names = ['Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco'];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
                @foreach ($avatars as $index => $avatar)
                    <label class="block cursor-pointer">
                        {{-- INPUT --}}
                        <input
                            type="radio"
                            name="avatar"
                            value="{{ $avatar }}"
                            class="sr-only avatar-radio"
                            {{ $user->avatar === $avatar ? 'checked' : '' }}
                        >

                        {{-- AVATAR --}}
                        <div class="avatar-card relative w-24 h-24 sm:w-28 sm:h-28 mx-auto
                                    rounded-2xl overflow-hidden bg-gray-50
                                    border-2 border-gray-200
                                    transition-all duration-300">
                            <img
                                src="{{ asset('images/avatars/' . $avatar) }}"
                                alt="Avatar {{ $names[$index] }}"
                                class="object-cover w-full h-full"
                            >

                            {{-- CHECK --}}
                            <div class="avatar-check absolute top-1 right-1
                                        bg-azul-marino text-white rounded-full
                                        w-6 h-6 flex items-center justify-center
                                        shadow-md opacity-0 scale-75
                                        transition pointer-events-none">
                                <span class="material-symbols-outlined text-sm">check</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <button
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3 bg-azul-marino text-white font-semibold rounded-xl
                           hover:bg-blue-800 transition-all duration-300
                           shadow-lg shadow-azul-marino/20
                           hover:shadow-xl hover:shadow-azul-marino/30
                           flex items-center justify-center gap-2 mx-auto">
                    <span class="material-symbols-outlined">save</span>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('profile') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-azul-marino transition-colors text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Volver al perfil
        </a>
    </div>
</div>
@endsection
<style>
    /* Avatar seleccionado */
.avatar-radio:checked + .avatar-card {
    border-color: #0f2a44; /* azul-marino */
    box-shadow: 0 0 0 4px rgba(15, 42, 68, 0.25);
}

/* Mostrar check */
.avatar-radio:checked + .avatar-card .avatar-check {
    opacity: 1;
    transform: scale(1);
}

/* Hover */
.avatar-card:hover {
    border-color: #0f2a44;
}

</style>