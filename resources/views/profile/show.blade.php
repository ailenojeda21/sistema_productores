@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Perfil de Usuario</h1>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Editar perfil</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700 font-semibold">Nombre:</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700 font-semibold">Email:</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700 font-semibold">Creado:</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700 font-semibold">DNI:</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $user->dni ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700 font-semibold">Es propietario:</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $user->es_propietario ? 'Sí' : 'No' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
