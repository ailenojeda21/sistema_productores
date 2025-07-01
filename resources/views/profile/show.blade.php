@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Perfil de Usuario</h1>
    <div class="bg-white shadow rounded p-6">
        <p><strong>Nombre:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>DNI:</strong> {{ $user->dni ?? '-' }}</p>
        <p><strong>Es propietario:</strong> {{ $user->es_propietario ? 'Sí' : 'No' }}</p>
        <a href="{{ route('profile.edit') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Editar perfil</a>
    </div>
</div>
@endsection
