@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-6xl mx-auto">

    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Maquinarias</h1>
        <a href="{{ route('maquinaria.create') }}"
           class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
           Nueva Maquinaria
        </a>
    </div>

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
                    <td class="px-4 py-2 text-base text-gray-700">

                        @php
                            $items = [
                                'arado' => 'Arado',
                                'rastra' => 'Rastra',
                                'niveleta_comun' => 'Niveleta común',
                                'niveleta_laser' => 'Niveleta láser',
                                'cincel_cultivadora' => 'Cincel/Cultivadora',
                                'desmalezadora' => 'Desmalezadora',
                                'pulverizadora_tractor' => 'Pulverizadora tractor',
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

                            // Dividir en dos columnas
                            $col1 = array_slice($activos, 0, 6);
                            $col2 = array_slice($activos, 6, 6);
                        @endphp

                        @if(count($activos))
                            <div class="grid grid-cols-2 gap-x-10">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($col1 as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>

                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($col2 as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Sin implementos</span>
                        @endif
                    </td>

                    <!-- Botones -->
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('maquinaria.edit', $maq->id) }}"
                               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow">
                                Editar
                            </a>

                            <form action="{{ route('maquinaria.destroy', $maq->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta máquina?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold shadow">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                        No hay maquinaria registrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="px-4 py-3 flex items-center justify-center space-x-4">
        <button id="maq-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50">◀</button>
        <span id="maq-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="maq-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50">▶</button>
    </div>

</div>
@endsection

<!-- Script paginación -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const rows = Array.from(document.querySelectorAll('#maquinarias-tbody tr'));
    const perPage = 4;
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
