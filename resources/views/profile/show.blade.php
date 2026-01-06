@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Perfil', 'route' => 'profile']
    ]" />
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Perfil de Usuario</h1>
    </div>

    <div class="flex flex-col md:flex-row md:items-stretch md:space-x-4">

        {{-- Contenedor blanco SOLO para la tabla --}}
        <div class="w-full md:w-2/3 bg-white rounded-lg shadow p-6 overflow-x-auto min-h-72">


            <table class="min-w-full divide-y divide-gray-200 text-base">
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">badge</span>
                            Nombre:
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">mail</span>
                            Email:
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">credit_card</span>
                            DNI:
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->dni ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">phone</span>
                            Teléfono:
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->telefono ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700 font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">calendar_today</span>
                            Creado:
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
    
    
                </tbody>
            </table>

              {{-- Botón dentro del cuadro --}}
            <div class="flex justify-end mb-4">
                <a href="{{ route('profile.edit') }}" 
                   class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow flex items-center gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    Editar perfil
                </a>
            </div>
        </div>
{{-- Contenedor blanco cuadrado para el avatar --}}
<div class="w-full md:w-1/3 flex justify-center md:justify-end mt-4 md:mt-0">
    <div class="bg-white rounded-lg shadow-lg p-6 w-72 h-auto flex flex-col items-center">

        {{-- Usa el mismo componente de avatar que en dashboard --}}
        <x-user-avatar :user="$user" size="lg" :gradient="false" :showName="false"  />

        <div class="mt-4 text-xl font-semibold text-gray-700">Avatar</div>

        {{-- Botón de editar avatar --}}
        <a href="{{ route('profile.avatar') }}"
           class="mt-3 px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow flex items-center gap-2">
            <span class="material-symbols-outlined">photo_camera</span>
            Editar avatar
        </a>

    </div>
</div>


    </div>
</div>
@endsection
