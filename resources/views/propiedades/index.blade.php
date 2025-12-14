
@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:block w-full max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-azul-marino">Propiedades</h1>
    <a href="{{ route('propiedades.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nueva Propiedad</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        @if($propiedades->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Direccion</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[100px]">Ubicación</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Hectáreas</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Propietario</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Derecho de riego</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Tipo derecho de riego</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">RUT</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Nº RUT</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Adjunto RUT</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Malla antigranizo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Hectáreas con malla</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Cierre perimetral</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody id="propiedades-tbody" class="bg-white divide-y divide-gray-200">
                @foreach($propiedades as $propiedad)
                <tr>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->direccion }}</td>
                    <td class="px-4 py-2 text-base text-gray-700 whitespace-nowrap">
                        @if($propiedad->lat && $propiedad->lng)
                            <button onclick="showLocationModal({{ $propiedad->lat }}, {{ $propiedad->lng }})" 
                                    class="text-blue-600 hover:text-blue-800 underline">Ver Mapa</button>
                        @else
                            Sin ubicación
                        @endif
                    </td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ number_format($propiedad->hectareas, 2, ',', '.') }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->es_propietario ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->tipo_derecho_riego ? $propiedad->tipo_derecho_riego : '-' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->rut ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->rut_valor ? number_format($propiedad->rut_valor, 0, '', '') : '-' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700 whitespace-nowrap">
                        @if($propiedad->rut_archivo)
                            <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Archivo RUT</a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->malla ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->hectareas_malla ? number_format($propiedad->hectareas_malla, 2, ',', '.') : '-' }}</td>
                    <td class="px-4 py-2 text-base text-gray-700">{{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('propiedades.edit', $propiedad) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow text-center">Editar</a>

                            <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta propiedad?');" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold shadow text-center">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        @include('propiedades.partials.empty-state')
        @endif
    </div>
    
    <!-- Controles de paginación (cliente) -->
    <div class="px-4 py-3 flex items-center justify-center space-x-4" role="navigation" aria-label="Paginación tabla">
        <button id="prop-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Página anterior">◀</button>
        <span id="prop-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="prop-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Siguiente página">▶</button>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-azul-marino">Propiedades</h1>
        <a href="{{ route('propiedades.create') }}" class="p-2 bg-naranja-oscuro text-white rounded-full shadow-lg">
            <span class="material-symbols-outlined">add</span>
        </a>
    </div>
    
    @if($propiedades->count() > 0)
        @include('propiedades.partials.mobile-list')
    @else
        @include('propiedades.partials.empty-state')
    @endif
</div>

<!-- Modal para mostrar el mapa (compartido entre desktop y mobile) -->
<div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Ubicación de la Propiedad</h3>
            <button onclick="closeLocationModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="modalMap" class="w-full h-96 rounded border"></div>
        <p class="mt-2 text-sm text-gray-600">
            Coordenadas: <span id="modalCoordinates" class="font-semibold"></span>
        </p>
    </div>
</div>
@endsection

<!-- Leaflet CSS y JS -->
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let modalMap = null;
let modalMarker = null;

function showLocationModal(lat, lng) {
    // Mostrar el modal
    const modal = document.getElementById('mapModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Inicializar el mapa si no existe
    if (!modalMap) {
        modalMap = L.map('modalMap');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(modalMap);
    }

    // Centrar el mapa en las coordenadas
    modalMap.setView([lat, lng], 15);

    // Actualizar o crear el marcador
    if (modalMarker) {
        modalMarker.setLatLng([lat, lng]);
    } else {
        modalMarker = L.marker([lat, lng]).addTo(modalMap);
    }

    // Actualizar el texto de las coordenadas
    document.getElementById('modalCoordinates').textContent = 
        lat.toFixed(7) + ', ' + lng.toFixed(7);

    // Forzar actualización del mapa
    setTimeout(() => {
        modalMap.invalidateSize();
    }, 100);
}

function closeLocationModal() {
    const modal = document.getElementById('mapModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Cerrar el modal con la tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLocationModal();
    }
});

// Cerrar el modal al hacer clic fuera del contenido
document.getElementById('mapModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLocationModal();
    }
});

// Script: paginación cliente para propiedades (5 filas por página)
document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('#propiedades-tbody tr'));
    if (rows.length === 0) return; // No hay filas en mobile
    
    const perPage = 2;
    let currentPage = 1;
    const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

    const prevBtn = document.getElementById('prop-prev');
    const nextBtn = document.getElementById('prop-next');
    const info = document.getElementById('prop-page-info');
    
    if (!prevBtn || !nextBtn || !info) return; // Elementos no existen en mobile

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

    // Inicializar
    renderPage(1);
});
</script>
@endpush
