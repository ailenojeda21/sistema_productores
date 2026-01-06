@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-6xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Maquinaria', 'route' => 'maquinaria.index']
    ]" />

    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Maquinarias</h1>
        <a href="{{ route('maquinaria.create') }}"
           class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
           Nueva Maquinaria
        </a>
    </div>

    <!-- Mensajes de error/éxito -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Propiedad</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Máquina</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Modelo (Año)</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider w-1/2">
                        Implementos
                    </th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>

            <tbody id="maquinarias-tbody" class="bg-white divide-y divide-gray-200">
                @forelse($maquinarias as $maq)
                <tr class="align-top">

                    <!-- Propiedad -->
                    <td class="px-4 py-2 text-base text-gray-700">
                        @if(isset($maq->propiedad))
                            {{ $maq->propiedad->ubicacion ?? '' }} {{ $maq->propiedad->direccion ?? '' }}
                        @else
                            -
                        @endif
                    </td>

                    <!-- Máquina -->
                    <td class="px-4 py-2 text-base text-gray-700">
                        {{ $maq->tractor ? 'Tractor' : '-' }}
                    </td>

                    <!-- Modelo -->
                    <td class="px-4 py-2 text-base text-gray-700">
                        {{ $maq->modelo_tractor ?? '-' }}
                    </td>

                    <!-- Implementos -->
                    <td class="px-4 py-3 text-base text-gray-700">

                        @php
                            $items = [
                                'arado' => 'Arado',
                                'rastra' => 'Rastra',
                                'niveleta_comun' => 'Niveleta común',
                                'niveleta_laser' => 'Niveleta láser',
                                'cincel_cultivadora' => 'Cincel/Cultivadora',
                                'desmalezadora' => 'Desmalezadora',
                                'pulverizadora_tractor' => 'Pulverizadora',
                                'mochila_pulverizadora' => 'Mochila pulverizadora',
                                'cosechadora' => 'Cosechadora',
                                'enfardadora' => 'Enfardadora',
                                'retroexcavadora' => 'Retroexcavadora',
                                'carro_carreton' => 'Carro/Carretón',
                            ];

                            // Solo los que están activos
                            $activos = [];
                            foreach ($items as $key => $label) {
                                if (!empty($maq->$key)) {
                                    $activos[] = $label;
                                }
                            }
                        @endphp

                        @if(count($activos))
                            <div class="flex flex-wrap gap-2">
                                @foreach($activos as $item)
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        {{ $item }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 italic text-sm">Sin implementos</span>
                        @endif
                    </td>

                    <!-- Botones -->
                    <td class="px-4 py-3 align-middle">
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('maquinaria.edit', $maq->id) }}"
                               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow text-center text-sm">
                                Editar
                            </a>

                            <form action="{{ route('maquinaria.destroy', $maq->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta máquina?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold shadow text-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                @endforelse
            </tbody>
        </table>

        @if($maquinarias->isEmpty())
        @include('maquinaria.partials.empty-state')
        @endif
    </div>

    <!-- Paginación -->
    <div class="px-4 py-3 flex items-center justify-center space-x-4">
        <button id="maq-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50">◀</button>
        <span id="maq-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="maq-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50">▶</button>
    </div>

</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-azul-marino">Maquinarias</h1>
        <a href="{{ route('maquinaria.create') }}" class="p-2 bg-naranja-oscuro text-white rounded-full shadow-lg">
            <span class="material-symbols-outlined">add</span>
        </a>
    </div>
    
    <!-- Mensajes de error/éxito -->
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">error</span>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    
    @if($maquinarias->count() > 0)
        @include('maquinaria.partials.mobile-list')
    @else
        @include('maquinaria.partials.empty-state')
    @endif
</div>
@endsection

<!-- Script paginación -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const rows = Array.from(document.querySelectorAll('#maquinarias-tbody tr'));
    const perPage = 2;
    let currentPage = 1;
    const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

    const prevBtn = document.getElementById('maq-prev');
    const nextBtn = document.getElementById('maq-next');
    const info = document.getElementById('maq-page-info');

    function render(page) {
        currentPage = Math.min(Math.max(1, page), totalPages);
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        rows.forEach((r, i) => {
            r.style.display = (i >= start && i < end) ? '' : 'none';
        });

        info.textContent = `Página ${currentPage} de ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    prevBtn.addEventListener('click', () => render(currentPage - 1));
    nextBtn.addEventListener('click', () => render(currentPage + 1));

    render(1);
});
</script>
