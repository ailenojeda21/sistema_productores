@extends('layouts.main')

@section('dashboard-content')
    <div class="w-full max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-azul-marino">Perfil de Usuario</h1>
        </div>

        <div class="flex flex-col md:flex-row md:items-stretch md:space-x-4">

            {{-- Contenedor blanco SOLO para la tabla --}}
            <div class="w-full md:w-2/3 bg-white rounded-lg shadow p-6 overflow-x-auto min-h-72">


                <table class="min-w-full divide-y divide-gray-200 text-base">
                    <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold">Nombre:</td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold">Email:</td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold">Creado:</td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold">DNI:</td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->dni ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold">Es propietario:</td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->es_propietario ? 'Sí' : 'No' }}</td>
                    </tr>
                    </tbody>
                </table>

                {{-- Botón dentro del cuadro --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
                        Editar perfil
                    </a>
                </div>
            </div>
            {{-- Contenedor blanco cuadrado para el avatar --}}
            <div class="w-full md:w-1/3 flex justify-center md:justify-end mt-4 md:mt-0">
                <div class="bg-white rounded-lg shadow-lg p-6 w-72 h-auto flex flex-col items-center">

                    <div class="h-44 w-44 md:h-48 md:w-48 rounded-full overflow-hidden shadow-lg">
                        <img src="{{ $user->avatar
                ? asset('images/avatars/'.$user->avatar)
                : asset('images/melon2.png') }}"
                             alt="Avatar"
                             class="h-full w-full object-cover">
                    </div>

                    <div class="mt-4 text-xl font-semibold text-gray-700">Avatar</div>

                    {{-- Botón de editar avatar --}}
                    <a href="{{ route('profile.avatar') }}"
                       class="mt-3 px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
                        Editar avatar
                    </a>

                </div>
            </div>


        </div>
    </div>
@endsection
