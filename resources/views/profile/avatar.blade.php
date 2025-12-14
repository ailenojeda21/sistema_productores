@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-3xl font-bold text-azul-marino mb-6 text-center">
            Seleccionar Avatar
        </h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.updateAvatar') }}">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 place-items-center">
                @php
                    $avatars = [
                        'uno.png',
                        'dos.png',
                        'tres.png',
                        'cuatro.png',
                        'cinco.png'
                    ];
                @endphp

                @foreach ($avatars as $avatar)
                    <label class="cursor-pointer flex flex-col items-center group">
                        <div class="w-32 h-32 rounded-full overflow-hidden shadow-lg border-4 transition group-hover:scale-105
                            {{ $user->avatar === $avatar ? 'border-azul-marino ring-4 ring-blue-200' : 'border-transparent' }}">
                            <img src="{{ asset('images/avatars/' . $avatar) }}"
                                 alt="{{ $avatar }}"
                                 class="object-cover w-full h-full">
                        </div>

                        <div class="mt-3 flex items-center">
                            <input type="radio"
                                   name="avatar"
                                   value="{{ $avatar }}"
                                   class="w-5 h-5 text-azul-marino"
                                   {{ $user->avatar === $avatar ? 'checked' : '' }}>
                            @if($user->avatar === $avatar)
                            <span class="ml-2 text-azul-marino font-semibold text-sm">Actual</span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            <button type="submit"
                class="w-full mt-8 py-3 px-4 bg-azul-marino text-white font-bold rounded hover:bg-blue-800 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Guardar Avatar
            </button>
        </form>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-azul-marino">Avatar</h2>
        <span class="material-symbols-outlined text-azul-marino text-3xl">account_circle</span>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-green-700">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.updateAvatar') }}">
        @csrf
        
        <!-- Preview del avatar actual -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <p class="text-center text-gray-600 text-sm mb-4">Avatar actual</p>
            <x-user-avatar :user="$user" size="lg" :gradient="false" />
        </div>

        <!-- Lista de avatares disponibles -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-azul-marino p-4">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined">photo_library</span>
                    Elige tu avatar
                </h3>
            </div>
            
            <div class="p-4 space-y-3">
                @php
                    $avatars = [
                        'uno.png',
                        'dos.png',
                        'tres.png',
                        'cuatro.png',
                        'cinco.png'
                    ];
                @endphp

                @foreach ($avatars as $index => $avatar)
                    <label class="flex items-center p-3 rounded-lg border-2 transition cursor-pointer
                        {{ $user->avatar === $avatar ? 'border-azul-marino bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                        
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full overflow-hidden shadow">
                                <img src="{{ asset('images/avatars/' . $avatar) }}"
                                     alt="Avatar {{ $index + 1 }}"
                                     class="object-cover w-full h-full">
                            </div>
                        </div>
                        
                        <div class="flex-1 ml-4">
                            <p class="font-semibold text-gray-800">Avatar {{ $index + 1 }}</p>
                            @if($user->avatar === $avatar)
                            <p class="text-xs text-azul-marino flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Seleccionado actualmente
                            </p>
                            @endif
                        </div>
                        
                        <input type="radio"
                               name="avatar"
                               value="{{ $avatar }}"
                               class="w-6 h-6 text-azul-marino"
                               {{ $user->avatar === $avatar ? 'checked' : '' }}>
                    </label>
                @endforeach
            </div>
            
            <!-- Submit Button -->
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <button type="submit"
                        class="w-full py-3 px-4 bg-azul-marino text-white font-semibold rounded-lg shadow-md hover:bg-blue-800 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Guardar Avatar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
