@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Cultivos</h1>
        <a href="{{ route('cultivos.create') }}" 
           class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">
           Nuevo Cultivo
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Estación</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Hectáreas Totales</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Manejo de cultivos</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Tecnología de riego</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody id="cultivos-tbody" class="bg-white divide-y divide-gray-200">
                @foreach($cultivos as $cultivo)
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->nombre }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->tipo }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->estacion }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ number_format($cultivo->hectareas, 2, '.', ',') }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->manejo_cultivo }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->tecnologia_riego ?? '-' }}</td>
                    
                    <!-- Botones Editar / Eliminar -->
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <!-- Editar -->
                            <a href="{{ route('cultivos.edit', $cultivo) }}" 
                               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow text-center">
                                Editar
                            </a>

                            <!-- Eliminar -->
                            <form action="{{ route('cultivos.destroy', $cultivo) }}" method="POST" 
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este cultivo?');" 
                                  class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold shadow text-center">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="px-4 py-3 flex items-center justify-center space-x-4" role="navigation" aria-label="Paginación tabla">
        <button id="cult-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Página anterior">◀</button>
        <span id="cult-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="cult-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Siguiente página">▶</button>
    </div>
</div>
@endsection

<!-- Script de paginación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('#cultivos-tbody tr'));
    const perPage = 4;
    let currentPage = 1;
    const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

    const prevBtn = document.getElementById('cult-prev');
    const nextBtn = document.getElementById('cult-next');
    const info = document.getElementById('cult-page-info');

    function renderPage(page) {
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

    prevBtn.addEventListener('click', () => renderPage(currentPage - 1));
    nextBtn.addEventListener('click', () => renderPage(currentPage + 1));

    renderPage(1);
});
</script>
