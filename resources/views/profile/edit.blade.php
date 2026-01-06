@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Perfil', 'route' => 'profile'],
        ['name' => 'Editar']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Perfil</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1">Nombre</label>
                    <input name="name" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1">Email</label>
                    <input name="email" type="email"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- DNI -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1">DNI</label>
                    <input name="dni" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('dni', $user->dni) }}">
                    @error('dni') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Teléfono -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1">Teléfono</label>
                    <input name="telefono" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('telefono', $user->telefono) }}">
                    @error('telefono') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit"
                    class="mt-6 w-full py-2 px-4 bg-azul-marino text-white font-bold rounded hover:bg-amarillo-claro hover:text-azul-marino transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-azul-marino">Mi Perfil</h2>
        <a href="{{ route('profile.avatar') }}" class="p-2 bg-azul-marino text-white rounded-full shadow-lg">
            <span class="material-symbols-outlined">photo_camera</span>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <x-user-avatar :user="$user" size="lg" :showEmail="true" />

            <div class="p-4 space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">badge</span>
                        Nombre completo
                    </label>
                    <input name="name" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">mail</span>
                        Correo electrónico
                    </label>
                    <input name="email" type="email"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- DNI -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">credit_card</span>
                        DNI
                    </label>
                    <input name="dni" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('dni', $user->dni) }}">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">phone</span>
                        Teléfono
                    </label>
                    <input name="telefono" type="text"
                           class="w-full p-3 border border-gray-300 rounded-lg"
                           value="{{ old('telefono', $user->telefono) }}">
                </div>

                <!-- Creado (solo lectura) -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-2 text-sm">
                        <span class="material-symbols-outlined mr-2">calendar_today</span>
                        Cuenta creada
                    </label>
                    <input type="text"
                           class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                           value="{{ $user->created_at->format('d/m/Y H:i') }}"
                           readonly>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t">
                <button type="submit"
                        class="w-full py-3 bg-azul-marino text-white font-semibold rounded-lg">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
