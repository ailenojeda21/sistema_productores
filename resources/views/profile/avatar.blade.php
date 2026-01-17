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

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 sm:gap-6">
                @php
                    $avatars = [
                        'uno.png',
                        'dos.png',
                        'tres.png',
                        'cuatro.png',
                        'cinco.png'
                    ];
                    $names = ['Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco'];
                @endphp

                @foreach ($avatars as $index => $avatar)
                    <label class="cursor-pointer group block">
                        <input type="radio"
                               name="avatar"
                               value="{{ $avatar }}"
                               class="sr-only"
                               {{ $user->avatar === $avatar ? 'checked' : '' }}>

                        <div class="relative">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto rounded-2xl overflow-hidden bg-gray-50 border-2 transition-all duration-300
                                {{ $user->avatar === $avatar ? 'border-azul-marino ring-4 ring-azul-marino/20 shadow-lg shadow-azul-marino/20' : 'border-gray-200 group-hover:border-azul-marino group-hover:shadow-md' }}">
                                <img src="{{ asset('images/avatars/' . $avatar) }}"
                                     alt="Avatar {{ $names[$index] }}"
                                     class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                            </div>

                            @if($user->avatar === $avatar)
                            <div class="absolute top-1 right-1 bg-azul-marino text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md pointer-events-none">
                                <span class="material-symbols-outlined text-sm">check</span>
                            </div>
                            @endif

                            <div class="absolute top-0 left-0 w-full h-full rounded-2xl transition-all duration-300 pointer-events-none
                                {{ $user->avatar === $avatar ? '' : 'bg-black/0 group-hover:bg-black/5' }}"></div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="w-full sm:w-auto px-8 py-3 bg-azul-marino text-white font-semibold rounded-xl hover:bg-blue-800 transition-all duration-300 shadow-lg shadow-azul-marino/20 hover:shadow-xl hover:shadow-azul-marino/30 flex items-center justify-center gap-2 mx-auto">
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
