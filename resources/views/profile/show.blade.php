@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-azul-marino mb-4">Perfil de Usuario</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full">
            <tbody>
                <tr>
                    <td class="py-2 font-semibold text-gray-600">Nombre:</td>
                    <td class="py-2">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold text-gray-600">Email:</td>
                    <td class="py-2">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold text-gray-600">Creado:</td>
                    <td class="py-2">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold text-gray-600">DNI:</td>
                    <td class="py-2">{{ $user->dni ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold text-gray-600">Es propietario:</td>
                    <td class="py-2">{{ $user->es_propietario ? 'Sí' : 'No' }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('profile.edit') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Editar perfil</a>
    </div>
</div>
@endsection
