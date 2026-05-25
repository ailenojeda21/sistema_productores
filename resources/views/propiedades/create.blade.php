@extends('layouts.dashboard')


@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Propiedades', 'route' => 'propiedades.index'],
        ['name' => 'Nueva']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Nueva Propiedad</h2>
        <form method="POST" action="{{ route('propiedades.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="distrito">Distrito</label>
                    <select id="distrito" name="distrito" class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione un distrito</option>
                        @foreach(\App\Models\Propiedad::getDistritosForForm() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="calle">Calle</label>
                    <input id="calle" name="calle" type="text" class="w-full p-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="numeracion">Numeración</label>
                    <input id="numeracion" name="numeracion" type="number" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="hectareas">Hectáreas</label>
                    <input id="hectareas" name="hectareas" type="text" inputmode="decimal" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-1">Ubicación</label>
                    <input type="hidden" name="lat" class="lat-input" value="{{ old('lat', '') }}">
                    <input type="hidden" name="lng" class="lng-input" value="{{ old('lng', '') }}">
                    <div class="map-element w-full h-56 rounded border"></div>
                    <p class="text-sm text-gray-500 mt-2">
                        Coordenadas:
                        <span class="coordenadas-display font-semibold">No seleccionada</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Haga clic en el mapa para marcar la ubicación. También puede arrastrar el pin.</p>
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" name="derecho_riego" id="derecho_riego" class="mr-2 rounded-full custom-checkbox">
                    <label for="derecho_riego">¿Tiene derecho de riego?</label>
                </div>
                <div id="tipoDerechoRiegoDiv" class="hidden mt-2 md:col-span-2">
                    <label for="tipo_derecho_riego" class="block text-gray-700 font-semibold mb-1">Tipo de derecho de riego:</label>
                    <select name="tipo_derecho_riego" id="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        @foreach(\App\Models\Propiedad::getTipoDerechoRiegoForForm() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="rut" id="rut" class="mr-2 rounded-full custom-checkbox">
                    <label for="rut">¿Posee RUT?</label>
                </div>
                <div id="rut-fields" class="hidden md:col-span-2">
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="rut_valor">Nº RUT</label>
                        <input id="rut_valor" name="rut_valor" type="number" step="1" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div class="mt-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="rut_archivo_file">Adjuntar RUT (PDF, opcional)</label>
                        <input id="rut_archivo_file" name="rut_archivo_file" type="file" accept="application/pdf" class="w-full p-2 border border-gray-300 rounded">
                        @error('rut_archivo_file')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="malla" id="malla" class="mr-2 rounded-full custom-checkbox">
                    <label for="malla">¿Tiene malla antigranizo?</label>
                </div>
                  <div id="malla-fields" class="hidden md:col-span-2">
                      <div class="mt-2">
                          <label class="block text-gray-700 font-semibold mb-1" for="hectareas_malla">Hectáreas con malla</label>
                          <input id="hectareas_malla" name="hectareas_malla" type="text" inputmode="decimal" class="w-full p-2 border border-gray-300 rounded transition-colors">
                          <p id="hectareas-malla-hint" class="text-sm text-gray-500 mt-1">Ingrese las hectáreas totales primero</p>
                      </div>
                  </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="cierre_perimetral" id="cierre_perimetral" class="mr-2 rounded-full custom-checkbox">
                    <label for="cierre_perimetral">¿Tiene cierre perimetral?</label>
                </div>
               <!-- Tipo de Tenencia -->
<div class="md:col-span-2 mt-6">
    <label class="block text-gray-700 font-semibold mb-3">
        Tipo de tenencia de la propiedad
    </label>

    <div class="space-y-3">
        <!-- Propietario -->
        <label class="flex items-center cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="propietario"
                   class="mr-3 tenencia-radio"
                   {{ old('tipo_tenencia') === 'propietario' ? 'checked' : '' }}>
            <span class="text-gray-700">Propietario</span>
        </label>

        <!-- Arrendatario -->
        <label class="flex items-center cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="arrendatario"
                   class="mr-3 tenencia-radio"
                   {{ old('tipo_tenencia') === 'arrendatario' ? 'checked' : '' }}>
            <span class="text-gray-700">Arrendatario</span>
        </label>

        <!-- Otro (input en la misma fila) -->
        <label class="flex items-center gap-4 cursor-pointer">
            <input type="radio"
                   name="tipo_tenencia"
                   value="otros"
                   class="tenencia-radio"
                   {{ old('tipo_tenencia') === 'otros' ? 'checked' : '' }}>

            <span class="text-gray-700 whitespace-nowrap">Otro</span>

            <input type="text"
                   name="especificar_tenencia"
                   class="w-72 p-2 border border-gray-300 rounded text-sm
                          {{ old('tipo_tenencia') === 'otros' ? '' : 'hidden' }}"
                   placeholder="Especifique la condición"
                   value="{{ old('especificar_tenencia') }}">
        </label>
    </div>

    @error('tipo_tenencia')
        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
    @enderror

    @error('especificar_tenencia')
        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
    @enderror
</div>


            </div>
            <button type="submit" class="mt-8 w-full py-2 px-4 bg-azul-marino hover:bg-amarillo-claro hover:text-azul-marino text-white font-bold rounded transition">Guardar</button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const tenenciaRadios = document.querySelectorAll('.tenencia-radio');
    const riego = document.getElementById('derecho_riego');
    const riegoDiv = document.getElementById('tipoDerechoRiegoDiv');
    const rut = document.getElementById('rut');
    const rutFields = document.getElementById('rut-fields');
    const mallaChk = document.getElementById('malla');
    const mallaDiv = document.getElementById('malla-fields');
    const totInput = document.getElementById('hectareas');
    const mallaInput = document.getElementById('hectareas_malla');
    const hint = document.getElementById('hectareas-malla-hint');

    const toggle = (check, div) => div?.classList.toggle('hidden', !check.checked);

    const toggleOtros = () => {
        const sel = document.querySelector('.tenencia-radio:checked');
        const inputOtro = document.querySelector('input[name="especificar_tenencia"]');
        if (inputOtro) {
            if (sel && sel.value === 'otros') {
                inputOtro.classList.remove('hidden');
            } else {
                inputOtro.classList.add('hidden');
                inputOtro.value = '';
            }
        }
    };

    // ──────────────────────────────────────────────
    // Validación visual SOLO lectura (input event)
    // ──────────────────────────────────────────────
    const validarMalla = () => {
        if (!mallaInput || !hint) return;

        mallaInput.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');

        if (!mallaChk?.checked) {
            hint.className = 'text-sm text-gray-500 mt-1';
            hint.textContent = 'Seleccione "tiene malla antigranizo" primero';
            return;
        }

        const total = Number(totInput?.value) || 0;
        const malla = Number(mallaInput.value) || 0;

        if (total === 0) {
            hint.className = 'text-sm text-gray-500 mt-1';
            hint.textContent = 'Ingrese las hectáreas totales primero';
            mallaInput.classList.add('border-gray-300');
        } else if (malla > total) {
            mallaInput.classList.add('border-red-500');
            hint.className = 'text-sm text-red-600 mt-1 font-semibold';
            hint.textContent = `Excede el máximo (${malla} ha > ${total} ha)`;
        } else if (malla > 0) {
            mallaInput.classList.add('border-green-500');
            hint.className = 'text-sm text-green-600 mt-1 font-semibold';
            hint.textContent = `Válido: ${malla} ha de ${total} ha`;
        } else {
            hint.className = 'text-sm text-blue-600 mt-1 font-semibold';
            hint.textContent = `Máximo disponible: ${total} ha`;
            mallaInput.classList.add('border-gray-300');
        }
    };

    const esMallaValida = () => !mallaChk?.checked || Number(totInput?.value) === 0 || Number(mallaInput?.value) <= Number(totInput?.value);

    // ──────────────────────────────────────────────
    // Corrección NO intrusiva (blur event)
    // ──────────────────────────────────────────────
    const corregirMalla = () => {
        if (!mallaChk?.checked || !mallaInput || !hint) return;
        const total = Number(totInput?.value) || 0;
        const malla = Number(mallaInput.value) || 0;
        if (total > 0 && malla > total) {
            mallaInput.value = String(total);
            hint.className = 'text-sm text-yellow-600 mt-1 font-semibold';
            hint.textContent = `Valor ajustado a ${total} ha (máximo permitido)`;
            mallaInput.classList.remove('border-red-500');
            mallaInput.classList.add('border-green-500');
        }
    };

    // ──────────────────────────────────────────────
    // Bloqueo de submit
    // ──────────────────────────────────────────────
    if (form) {
        form.addEventListener('submit', (e) => {
            validarMalla();
            if (!esMallaValida()) {
                e.preventDefault();
                mallaInput?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                mallaInput?.focus();
            }
        });
    }

    // ──────────────────────────────────────────────
    // Event wiring
    // ──────────────────────────────────────────────
    tenenciaRadios.forEach(r => r.addEventListener('change', toggleOtros));
    if (riego && riegoDiv) riego.addEventListener('change', () => toggle(riego, riegoDiv));
    if (rut && rutFields) rut.addEventListener('change', () => toggle(rut, rutFields));

    if (mallaChk && mallaDiv) {
        mallaChk.addEventListener('change', () => {
            toggle(mallaChk, mallaDiv);
            validarMalla();
        });
    }

    if (totInput && mallaInput) {
        totInput.addEventListener('input', validarMalla);
        mallaInput.addEventListener('input', validarMalla);
        mallaInput.addEventListener('blur', corregirMalla);
    }

    // ──────────────────────────────────────────────
    // Init
    // ──────────────────────────────────────────────
    toggleOtros();
    if (riego && riegoDiv) toggle(riego, riegoDiv);
    if (rut && rutFields) toggle(rut, rutFields);
    if (mallaChk && mallaDiv) toggle(mallaChk, mallaDiv);
    if (mallaChk?.checked) validarMalla();
});
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapElements = document.querySelectorAll('.map-element');
    
    mapElements.forEach(function(mapElement) {
        let map, marker;
        
        const latInput = mapElement.parentElement.querySelector('.lat-input');
        const lngInput = mapElement.parentElement.querySelector('.lng-input');
        const coordDisplay = mapElement.parentElement.querySelector('.coordenadas-display');
        
        const initialLat = parseFloat(latInput?.value) || -31.5;
        const initialLng = parseFloat(lngInput?.value) || -68.5;

        map = L.map(mapElement).setView([initialLat, initialLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.control.layers({
            'Mapa': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map),
            'Satelital': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: ''
            })
        }).addTo(map);

        if (latInput?.value && lngInput?.value) {
            updateMarker(L.latLng(parseFloat(latInput.value), parseFloat(lngInput.value)));
        }

        if (!latInput?.value && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                map.setView([pos.coords.latitude, pos.coords.longitude], 13);
            });
        }

        map.on('click', function(e) {
            updateMarker(e.latlng);
        });

        setTimeout(() => { map.invalidateSize(); }, 100);

        function updateMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, {draggable: true}).addTo(map);
                marker.on('dragend', function(e) {
                    updateMarker(e.target.getLatLng());
                });
            }
            
            latInput.value = latlng.lat.toFixed(7);
            lngInput.value = latlng.lng.toFixed(7);
            coordDisplay.textContent = latlng.lat.toFixed(7) + ', ' + latlng.lng.toFixed(7);
        }
    });
});
</script>
@endpush
