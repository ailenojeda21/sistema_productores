@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">

    {{-- Imagen lateral --}}
    <div class="hidden md:flex md:w-1/2 w-full bg-gray-100 items-center justify-center p-0 overflow-hidden"
         style="height: 100vh; max-height: 100vh;">

        <img src="{{ asset('images/rupal.png') }}"
             alt="Logo Municipalidad de Lavalle"
             class="w-full h-full object-cover"
             style="max-height: 100vh;">
    </div>

    {{-- Contenido --}}
    <div class="w-full md:w-1/2 flex flex-col justify-center relative p-8 bg-white h-screen overflow-y-auto">

        {{-- Logo --}}
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo"
             class="absolute top-8 right-8 h-20">

        <div class="max-w-lg w-full mx-auto">

            

            {{-- Encabezado --}}
            <div class="text-center mb-3">

                <span class="material-symbols-outlined text-naranja-oscuro mb-2"
                      style="font-size: 46px;">
                    mark_email_unread
                </span>

                <h1 class="text-2xl font-bold text-naranja-oscuro mb-1">
                    Verifica tu correo
                </h1>

                <p class="text-gray-400 text-xs">
                    Último paso para activar tu cuenta
                </p>
            </div>

            {{-- Alertas --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-3 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">
                        check_circle
                    </span>

                    <span class="text-sm">
                        Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                    </span>
                </div>

            @elseif (session('status') == 'link-expired')
                <div class="mb-3 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg flex items-center gap-2">

                    <span class="material-symbols-outlined text-lg">
                        timer_off
                    </span>

                    <span class="text-sm">
                        El enlace de verificación ha expirado. Solicita uno nuevo debajo.
                    </span>
                </div>
            @endif

            {{-- Información --}}
            <div class="bg-gray-50 rounded-lg p-5 mb-5 border border-gray-200">

                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                    Hemos enviado un enlace de verificación a
                    <strong class="text-azul-marino">
                        {{ auth()->user()->email }}
                    </strong>.
                </p>

                <p class="text-gray-600 text-sm leading-relaxed">
                    Revisa tu bandeja de entrada y haz clic en el botón
                    <strong>"Verificar correo"</strong>
                    para activar tu cuenta.
                    Si no lo encuentras, revisá la carpeta de spam.
                </p>
            </div>

            {{-- Formulario --}}
            <form method="POST"
                  action="{{ route('verification.send') }}"
                  class="space-y-4">

                @csrf

                <p class="text-sm text-gray-500 text-center mb-2">
                    ¿No recibiste el correo?
                </p>

                <button type="submit"
                        class="w-full py-2 px-4 bg-naranja-oscuro text-white font-bold rounded hover:bg-amarillo-claro transition flex items-center justify-center gap-2">

                    <span class="material-symbols-outlined text-lg">
                        refresh
                    </span>

                    Reenviar verificación
                </button>
            </form>

            {{-- Logout --}}
            <div class="mt-3 flex flex-col items-center space-y-2 text-center">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="text-gray-400 font-semibold hover:underline hover:text-red-500 transition text-sm">

                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection