
@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-5xl mx-auto">
    <x-breadcrumb :items="[['label' => 'Propiedades']]" />
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-azul-marino">Propiedades</h1>
        <a href="{{ route('propiedades.create') }}" class="px-4 py-2 bg-naranja-oscuro text-white rounded hover:bg-amarillo-claro font-semibold shadow">Nueva Propiedad</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Hectáreas</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($propiedades as $propiedad)
                    <tr>
                        <td class="px-4 py-3 text-base text-gray-700">{{ $propiedad->direccion }}</td>
                        <td class="px-4 py-3 text-base text-gray-700">
                            @if($propiedad->lat && $propiedad->lng)
                                <button onclick="showLocationModal({{ $propiedad->lat }}, {{ $propiedad->lng }})"
                                        class="text-blue-600 hover:text-blue-800 underline">Ver Mapa</button>
                            @else
                                Sin ubicación
                            @endif
                        </td>
                        <td class="px-4 py-3 text-base text-gray-700">{{ number_format($propiedad->hectareas, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('propiedades.edit', $propiedad) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow text-center text-sm">Editar</a>
                                <button onclick="showDetails({{ $loop->index }})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold shadow text-sm">Ver más</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (shown on mobile) -->
        <div class="md:hidden space-y-4">
            @foreach($propiedades as $index => $propiedad)
            <div class="border rounded-lg p-4 shadow-sm">
                <div class="space-y-2">
                    <div>
                        <span class="font-medium">Dirección:</span>
                        <span class="text-gray-700">{{ $propiedad->direccion }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Ubicación:</span>
                        @if($propiedad->lat && $propiedad->lng)
                            <button onclick="showLocationModal({{ $propiedad->lat }}, {{ $propiedad->lng }})"
                                    class="text-blue-600 hover:text-blue-800 underline">Ver Mapa</button>
                        @else
                            <span class="text-gray-700">Sin ubicación</span>
                        @endif
                    </div>
                    <div>
                        <span class="font-medium">Hectáreas:</span>
                        <span class="text-gray-700">{{ number_format($propiedad->hectareas, 2, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 flex space-x-2">
                        <a href="{{ route('propiedades.edit', $propiedad) }}" class="flex-1 text-center px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold shadow text-sm">Editar</a>
                        <button onclick="showDetails({{ $index }})" class="flex-1 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold shadow text-sm">Ver más</button>
                    </div>
                </div>

                <!-- Hidden details section -->
                <div id="details-{{ $index }}" class="mt-3 pt-3 border-t border-gray-200 hidden">
                    <div class="space-y-2">
                        <div>
                            <span class="font-medium">Propietario:</span>
                            <span class="text-gray-700">{{ $propiedad->es_propietario ? 'Sí' : 'No' }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Derecho de riego:</span>
                            <span class="text-gray-700">{{ $propiedad->derecho_riego ? 'Sí' : 'No' }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Tipo derecho de riego:</span>
                            <span class="text-gray-700">{{ $propiedad->tipo_derecho_riego ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="font-medium">RUT:</span>
                            <span class="text-gray-700">{{ $propiedad->rut ? 'Sí' : 'No' }}</span>
                        </div>
                        @if($propiedad->rut_valor)
                        <div>
                            <span class="font-medium">Nº RUT:</span>
                            <span class="text-gray-700">{{ number_format($propiedad->rut_valor, 0, '', '') }}</span>
                        </div>
                        @endif
                        @if($propiedad->rut_archivo)
                        <div>
                            <span class="font-medium">Adjunto RUT:</span>
                            <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Ver archivo</a>
                        </div>
                        @endif
                        <div>
                            <span class="font-medium">Malla antigranizo:</span>
                            <span class="text-gray-700">{{ $propiedad->malla ? 'Sí' : 'No' }}</span>
                        </div>
                        @if($propiedad->hectareas_malla)
                        <div>
                            <span class="font-medium">Hectáreas con malla:</span>
                            <span class="text-gray-700">{{ number_format($propiedad->hectareas_malla, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="font-medium">Cierre perimetral:</span>
                            <span class="text-gray-700">{{ $propiedad->cierre_perimetral ? 'Sí' : 'No' }}</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta propiedad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 font-semibold shadow text-sm">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Controles de paginación (cliente) -->
    <div class="px-4 py-3 flex items-center justify-center space-x-4" role="navigation" aria-label="Paginación tabla">
        <button id="prop-prev" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Página anterior">◀</button>
        <span id="prop-page-info" class="text-sm text-gray-700">Página 1</span>
        <button id="prop-next" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50" aria-label="Siguiente página">▶</button>
    </div>

</div>

<!-- Modal para mostrar el mapa -->
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

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
    document.getElementById('mapModal').classList.add('hidden');
    document.getElementById('mapModal').classList.remove('flex');
}

// Toggle property details
function showDetails(index) {
    const details = document.getElementById(`details-${index}`);
    const button = document.querySelector(`button[onclick="showDetails(${index})"]`);
    
    if (details && button) {
        // Toggle the visibility of the details
        details.classList.toggle('hidden');
        
        // Update button text based on current state
        if (details.classList.contains('hidden')) {
            button.textContent = 'Ver más';
            button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        } else {
            button.textContent = 'Ver menos';
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
        }
    }
}

// Close all other details when one is opened
document.addEventListener('click', function(e) {
    if (e.target.matches('button[onclick^="showDetails("]')) {
        const index = e.target.getAttribute('onclick').match(/\d+/)[0];
        const allDetails = document.querySelectorAll('[id^="details-"]');
        const allButtons = document.querySelectorAll('button[onclick^="showDetails("]');
        
        allDetails.forEach((detail, i) => {
            if (detail.id !== `details-${index}`) {
                detail.classList.add('hidden');
                allButtons[i].textContent = 'Ver más';
                allButtons[i].classList.remove('bg-gray-500', 'hover:bg-gray-600');
                allButtons[i].classList.add('bg-blue-500', 'hover:bg-blue-600');
            }
        });
    }
});

// Close all details when pressing Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const allDetails = document.querySelectorAll('[id^="details-"]');
        const allButtons = document.querySelectorAll('button[onclick^="showDetails("]');
        
        allDetails.forEach((detail, i) => {
            detail.classList.add('hidden');
            if (allButtons[i]) {
                allButtons[i].textContent = 'Ver más';
                allButtons[i].classList.remove('bg-gray-500', 'hover:bg-gray-600');
                allButtons[i].classList.add('bg-blue-500', 'hover:bg-blue-600');
            }
        });
    }
});

// Close modal when clicking outside
const mapModal = document.getElementById('mapModal');
if (mapModal) {
    mapModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLocationModal();
        }
    });
}
</script>

<!-- Script: paginación cliente para propiedades (5 filas por página) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('#propiedades-tbody tr'));
    const perPage = 4;
    let currentPage = 1;
    const totalPages = Math.max(1, Math.ceil(rows.length / perPage));

    const prevBtn = document.getElementById('prop-prev');
    const nextBtn = document.getElementById('prop-next');
    const info = document.getElementById('prop-page-info');

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
@endsection
