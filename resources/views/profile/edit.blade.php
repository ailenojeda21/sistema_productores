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
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">badge</span>
                        Nombre completo
                    </label>
                    <input name="name" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">mail</span>
                        Correo electrónico
                    </label>
                    <input name="email" type="email"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- DNI -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">credit_card</span>
                        DNI
                    </label>
                    <input name="dni" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('dni', $user->dni) }}">
                    @error('dni') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">phone</span>
                        Teléfono
                    </label>
                    <input name="telefono" type="text"
                           class="w-full p-2 border border-gray-300 rounded"
                           value="{{ old('telefono', $user->telefono) }}">
                    @error('telefono') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <!-- Creado (solo lectura) -->
                <div class="md:col-span-2">
                    <label class="flex items-center text-gray-700 font-semibold mb-1">
                        <span class="material-symbols-outlined mr-2">calendar_today</span>
                        Cuenta creada
                    </label>
                    <input type="text"
                           class="w-full p-2 border border-gray-200 rounded bg-gray-50 text-gray-600"
                           value="{{ $user->created_at->format('d/m/Y H:i') }}"
                           readonly>
                </div>

                <!-- ¿Pertenece a alguna cooperativa? -->
                <div class="md:col-span-2 flex items-center mt-4">
                    <input type="checkbox" name="tiene_cooperativas" id="tiene_cooperativas" class="mr-2 rounded-full custom-checkbox"
                        {{ is_array(old('cooperativas')) && !empty(old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && !empty($user->cooperativas) ? 'checked' : '') }}>
                    <label for="tiene_cooperativas">¿Pertenece a alguna cooperativa?¿Cual?</label>
                </div>
            </div>

            <div id="cooperativa-fields" class="hidden mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @php
                        $cooperativas = [
                            'cooperativa_nueva_california' => 'Coop Nueva California',
                            'cooperativa_tulumaya' => 'Coop Tulumaya',
                            'cooperativa_norte_mendocino' => 'Coop Norte Mendocino',
                            'cooperativa_tres_de_mayo' => 'Coop Tres de Mayo',
                            'cooperativa_altas_cumbres' => 'Coop Altas Cumbres',
                            'cooperativa_tres_portenas' => 'Coop Tres Porteñas',
                            'cooperativa_el_poniente' => 'Coop El Poniente',
                            'cooperativa_pampanos_mendocinos' => 'Coop Pámpanos Mendocinos',
                            'cooperativa_ingeniero_giangnoni' => 'Coop Ingeniero Giangnoni',
                            'cooperativa_las_trincheras' => 'Coop Las Trincheras',
                            'cooperativa_agricola_beltran' => 'Coop Agrícola Beltrán',
                            'cooperativa_la_dormida' => 'Coop La Dormida',
                            'cooperativa_del_algarrobal' => 'Coop Del Algarrobal',
                            'cooperativa_el_libertador' => 'Coop El Libertador',
                            'cooperativa_brindis' => 'Coop Brindis',
                            'cooperativa_productores_junin' => 'Coop Productores de Junín',
                            'cooperativa_colonia_california' => 'Coop Colonia California',
                            'cooperativa_mendoza' => 'Coop Mendoza',
                            'cooperativa_norte_lavallino' => 'Coop Norte Lavallino',
                            'cooperativa_maipu' => 'Coop Maipú',
                            'cooperativa_lacofrut' => 'Coop Lacofrut',
                        ];
                    @endphp

                    @foreach($cooperativas as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array(old('cooperativas')) && in_array($label, old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && in_array($label, $user->cooperativas) ? 'checked' : '') }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
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

                <!-- ¿Pertenece a alguna cooperativa? -->
                <div class="flex items-center">
                    <input type="checkbox" name="tiene_cooperativas" id="tiene_cooperativas_mobile" class="mr-2 rounded-full custom-checkbox"
                        {{ is_array(old('cooperativas')) && !empty(old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && !empty($user->cooperativas) ? 'checked' : '') }}>
                    <label for="tiene_cooperativas_mobile" class="text-sm">¿Pertenece a alguna cooperativa?¿Cual?</label>
                </div>
            </div>

            <div id="cooperativa-fields-mobile" class="hidden mt-4 px-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccione la cooperativa</h3>
                <div class="grid grid-cols-1 gap-1">
                    @php
                        $cooperativas = [
                            'cooperativa_nueva_california_mobile' => 'Coop Nueva California',
                            'cooperativa_tulumaya_mobile' => 'Coop Tulumaya',
                            'cooperativa_norte_mendocino_mobile' => 'Coop Norte Mendocino',
                            'cooperativa_tres_de_mayo_mobile' => 'Coop Tres de Mayo',
                            'cooperativa_altas_cumbres_mobile' => 'Coop Altas Cumbres',
                            'cooperativa_tres_portenas_mobile' => 'Coop Tres Porteñas',
                            'cooperativa_el_poniente_mobile' => 'Coop El Poniente',
                            'cooperativa_pampanos_mendocinos_mobile' => 'Coop Pámpanos Mendocinos',
                            'cooperativa_ingeniero_giangnoni_mobile' => 'Coop Ingeniero Giangnoni',
                            'cooperativa_las_trincheras_mobile' => 'Coop Las Trincheras',
                            'cooperativa_agricola_beltran_mobile' => 'Coop Agrícola Beltrán',
                            'cooperativa_la_dormida_mobile' => 'Coop La Dormida',
                            'cooperativa_del_algarrobal_mobile' => 'Coop Del Algarrobal',
                            'cooperativa_el_libertador_mobile' => 'Coop El Libertador',
                            'cooperativa_brindis_mobile' => 'Coop Brindis',
                            'cooperativa_productores_junin_mobile' => 'Coop Productores de Junín',
                            'cooperativa_colonia_california_mobile' => 'Coop Colonia California',
                            'cooperativa_mendoza_mobile' => 'Coop Mendoza',
                            'cooperativa_norte_lavallino_mobile' => 'Coop Norte Lavallino',
                            'cooperativa_maipu_mobile' => 'Coop Maipú',
                            'cooperativa_lacofrut_mobile' => 'Coop Lacofrut',
                        ];
                    @endphp

                    @foreach($cooperativas as $field => $label)
                        <label class="flex items-center space-x-1">
                            <input type="checkbox" name="cooperativas[]" id="{{ $field }}" value="{{ $label }}" class="custom-checkbox"
                                {{ is_array(old('cooperativas')) && in_array($label, old('cooperativas')) ? 'checked' : (is_array($user->cooperativas) && in_array($label, $user->cooperativas) ? 'checked' : '') }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
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

<style>
    .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
        border: 2px solid #cbd5e1;
        background: #fff;
        appearance: none;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px #93c5fd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cooperativaCheckbox = document.getElementById('tiene_cooperativas');
        const cooperativaFields = document.getElementById('cooperativa-fields');
        const cooperativaCheckboxMobile = document.getElementById('tiene_cooperativas_mobile');
        const cooperativaFieldsMobile = document.getElementById('cooperativa-fields-mobile');

        function toggleCooperativaFields() {
            cooperativaFields.classList.toggle('hidden', !cooperativaCheckbox.checked);
        }

        function toggleCooperativaFieldsMobile() {
            cooperativaFieldsMobile.classList.toggle('hidden', !cooperativaCheckboxMobile.checked);
        }

        if (cooperativaCheckbox) {
            cooperativaCheckbox.addEventListener('change', toggleCooperativaFields);
            toggleCooperativaFields();
        }

        if (cooperativaCheckboxMobile) {
            cooperativaCheckboxMobile.addEventListener('change', toggleCooperativaFieldsMobile);
            toggleCooperativaFieldsMobile();
        }
    });
</script>
@endsection
