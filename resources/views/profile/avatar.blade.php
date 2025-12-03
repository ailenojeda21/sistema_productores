@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-3xl mx-auto">

    <div class="bg-white rounded-lg shadow p-8">

        <h2 class="text-3xl font-bold text-azul-marino mb-6 text-center">
            Seleccionar Avatar
        </h2>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
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
                    <label class="cursor-pointer flex flex-col items-center">

                        {{-- Imagen --}}
                        <div class="w-32 h-32 rounded-full overflow-hidden shadow-lg border-4 transition
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

            <button type="submit"
                class="w-full mt-8 py-2 px-4 bg-naranja-oscuro text-white font-bold rounded hover:bg-amarillo-claro hover:text-azul-marino transition">
                Guardar Avatar
            </button>
        </form>

    </div>

</div>
@endsection
