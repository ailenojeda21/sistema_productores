@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Comercialización</h1>
        @if($comercios->isEmpty())
            <a href="{{ route('comercios.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nuevo Comercio</a>
        @else
            <a href="{{ route('comercios.edit', $comercios->first()->id) }}" class="px-4 py-2 bg-azul-marino text-white rounded hover:bg-amarillo-claro font-semibold shadow">Editar Comercio</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50">
                <tr>
                    <th style="width:20%" class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Infraestructura Empaque
                    </th>
                    <th style="width:20%" class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Comercialización en Mercados
                    </th>
                    <th style="width:15%" class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Vende en Finca
                    </th>
                    <th style="width:20%" class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Comercializa en Cooperativas
                    </th>
                    <th style="width:40%" class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                        Mercados
                    </th>
                </tr>
            </thead>

            <tbody id="comercios-tbody" class="bg-white divide-y divide-gray-200">
                @forelse($comercios as $comercio)
                <tr>
                    <td style="width:15%" class="px-2 py-2 text-base text-gray-700 text-center">
                        @if($comercio->infraestructura_empaque)
                            <span class="text-green-600 font-semibold">✓ Sí</span>
                        @else
                            <span class="text-red-600 font-semibold">✗ No</span>
                        @endif
                    </td>
                    <td style="width:15%" class="px-2 py-2 text-base text-gray-700 text-center">
                        @if($comercio->comercio_mercado)
                            <span class="text-green-600 font-semibold">✓ Sí</span>
                        @else
                            <span class="text-red-600 font-semibold">✗ No</span>
                        @endif
                    </td>
                    <td style="width:15%" class="px-2 py-2 text-base text-gray-700 text-center">
                        @if($comercio->vende_en_finca)
                            <span class="text-green-600 font-semibold">✓ Sí</span>
                        @else
                            <span class="text-red-600 font-semibold">✗ No</span>
                        @endif
                    </td>
                    <td style="width:15%" class="px-2 py-2 text-base text-gray-700 text-center">
                        @if($comercio->comercializa_cooperativas)
                            <span class="text-green-600 font-semibold">✓ Sí</span>
                        @else
                            <span class="text-red-600 font-semibold">✗ No</span>
                        @endif
                    </td>
                    <td style="width:40%" class="px-2 py-2 text-base text-gray-700">
                        @php
                            $mercados = isset($comercio->mercados)
                                ? (is_array($comercio->mercados)
                                    ? $comercio->mercados
                                    : json_decode($comercio->mercados, true))
                                : [];
                        @endphp
                        @if(is_array($mercados) && count($mercados))
                            <div class="flex flex-col gap-1">
                                @foreach($mercados as $mercado)
                                  <span class="inline-flex max-w-fit px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded text-left">

                                        {{ $mercado }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 italic text-sm">Sin mercados</span>
                        @endif
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        @if($comercios->isEmpty())
        @include('comercios.partials.empty-state')
        @endif
    </div>
    
    <!-- Paginación -->
    @if($comercios->count() > 2)
    <div class="px-4 py-3 flex items-center justify-center space-x-4" role="navigation" aria-label="Paginación tabla">
        <button id="comercio-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Página anterior">◀</button>
        <span id="comercio-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="comercio-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Siguiente página">▶</button>
    </div>
    @endif
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-azul-marino">Comercialización</h1>
        @if($comercios->isEmpty())
            <a href="{{ route('comercios.create') }}" class="p-2 bg-naranja-oscuro text-white rounded-full shadow-lg">
                <span class="material-symbols-outlined">add</span>
            </a>
        @else
            <a href="{{ route('comercios.edit', $comercios->first()->id) }}" class="p-2 bg-azul-marino text-white rounded-full shadow-lg">
                <span class="material-symbols-outlined">edit</span>
            </a>
        @endif
    </div>
    
    @if($comercios->count() > 0)
        @include('comercios.partials.mobile-list')
    @else
        @include('comercios.partials.empty-state')
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('#comercios-tbody tr'));
    if (rows.length === 0) return;
    
    const perPage = 2;
    let currentPage = 1;
    const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

    const prevBtn = document.getElementById('comercio-prev');
    const nextBtn = document.getElementById('comercio-next');
    const info = document.getElementById('comercio-page-info');
    
    if (!prevBtn || !nextBtn || !info) return;

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
@endpush
@endsection
