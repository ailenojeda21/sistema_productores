@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-3xl mx-auto">
    {{-- Back button --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center text-naranja-oscuro hover:text-azul-marino font-medium">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-3xl font-bold text-azul-marino mb-8 text-center">
            Elige tu Avatar
        </h2>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.updateAvatar') }}">
            @csrf

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-5 place-items-center">

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
                    <label class="cursor-pointer flex flex-col items-center">

                        {{-- Imagen --}}
                        <div class="w-36 h-36 sm:w-40 sm:h-40 rounded-full overflow-hidden shadow-lg border-4 transition
                            {{ $user->avatar === $avatar ? 'border-azul-marino' : 'border-transparent' }}">
                            <img src="{{ asset('images/avatars/' . $avatar) }}"
                                 alt="{{ $avatar }}"
                                 class="object-cover w-full h-full">
                        </div>

                        {{-- Opción radio --}}
                        <input type="radio"
                               name="avatar"
                               value="{{ $avatar }}"
                               class="mt-3"
                               {{ $user->avatar === $avatar ? 'checked' : '' }}>
                    </label>
                @endforeach

            </div>

            <div class="mt-10 text-center">
                <button type="submit"
                    class="px-8 py-3 bg-naranja-oscuro text-white text-lg font-bold rounded-lg hover:bg-amarillo-claro hover:text-azul-marino transition shadow-md">
                    Confirmar
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
