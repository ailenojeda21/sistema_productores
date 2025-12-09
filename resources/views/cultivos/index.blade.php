@extends('layouts.main')

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
                    <!-- Nueva columna: Propiedad -->
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Propiedad</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Estación
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Hectáreas
                        Totales
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Manejo de
                        cultivos
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Tecnología de riego
                    </th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody id="cultivos-tbody" class="bg-white divide-y divide-gray-200">
                @foreach($cultivos as $cultivo)
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $cultivo->nombre }}</td>

                    <!-- Celda nueva: mostrar propiedad asociada -->
                    <td class="px-4 py-2 text-base text-gray-700"> @if(isset($cultivo->propiedad) && $cultivo->propiedad) {{ $cultivo->propiedad->ubicacion ?? '' }}@if(!empty($cultivo->propiedad->direccion))  {{ $cultivo->propiedad->direccion }}@endif
                        @else
                        @endif
                    </td>
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
                @empty
                    <tr class="empty-row">
                        <td colspan="7" class="px-4 py-6 text-center text-gray-600">
                            <div class="p-2">
                                @include('cultivos.partials._table_empty')
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div id="cult-pagination" class="px-4 py-3 flex items-center justify-center space-x-4" role="navigation"
             aria-label="Paginación tabla">
            <button id="cult-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50"
                    aria-label="Página anterior">◀
            </button>
            <span id="cult-page-info" class="text-sm text-gray-700">Página 1</span>
            <button id="cult-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50"
                    aria-label="Siguiente página">▶
            </button>
        </div>
    </div>

    <!-- Script: paginación cliente para cultivos (4 filas por página) -->
    <script>
        function setupTablePagination({
                                          tbodySelector,
                                          prevBtnId,
                                          nextBtnId,
                                          pageInfoId,
                                          paginationContainerId,
                                          perPage = 4
                                      }) {
            const rows = Array.from(document.querySelectorAll(`${tbodySelector} tr`));
            const dataRows = rows.filter(r => !r.classList.contains('empty-row'));
            const emptyRow = rows.find(r => r.classList.contains('empty-row'));
            let currentPage = 1;
            const totalPages = Math.max(1, Math.ceil(Math.max(1, dataRows.length) / perPage));

            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);
            const info = document.getElementById(pageInfoId);
            const paginationContainer = document.getElementById(paginationContainerId);

            function renderPage(page) {
                currentPage = Math.min(Math.max(1, page), totalPages);
                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                // Mostrar/ocultar solo filas con datos. La fila empty-row (si existe) siempre se muestra cuando no hay datos.
                dataRows.forEach((r, i) => {
                    r.style.display = (i >= start && i < end) ? '' : 'none';
                });
                if (emptyRow) {
                    emptyRow.style.display = (dataRows.length === 0) ? '' : 'none';
                }
                info.textContent = `Página ${currentPage} de ${totalPages}`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
            }

            prevBtn.addEventListener('click', () => renderPage(currentPage - 1));
            nextBtn.addEventListener('click', () => renderPage(currentPage + 1));

            // Si no hay filas de datos, ocultar paginación y mostrar la fila de 'No registros'
            if (dataRows.length === 0) {
                if (paginationContainer) paginationContainer.style.display = 'none';
                if (emptyRow) emptyRow.style.display = '';
                return;
            }

            // Inicializar paginación
            renderPage(1);
            return {
                getCurrentPage: () => currentPage,
                getTotalPages: () => totalPages
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Cultivos table pagination
            setupTablePagination({
                tbodySelector: '#cultivos-tbody',
                prevBtnId: 'cult-prev',
                nextBtnId: 'cult-next',
                pageInfoId: 'cult-page-info',
                paginationContainerId: 'cult-pagination',
                perPage: 4
            });
        });
    </script>
@endsection
