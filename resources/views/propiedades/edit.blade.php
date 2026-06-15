@extends('layouts.dashboard')

@section('dashboard-content')
<div class="w-full max-w-2xl mx-auto">
    <x-breadcrumb :items="[
        ['name' => 'Propiedades', 'route' => 'propiedades.index'],
        ['name' => 'Editar']
    ]" />
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-azul-marino mb-6">Editar Propiedad</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('propiedades.update', $propiedad) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                {{-- Distrito y Calle --}}
                <div>
                    <label class="block font-semibold mb-1" for="distrito">Distrito</label>
                    <select id="distrito" name="distrito" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="">Seleccione un distrito</option>
                        @foreach(\App\Models\Propiedad::getDistritosForForm() as $value => $label)
                            <option value="{{ $value }}" {{ old('distrito', $propiedad->distrito) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1" for="calle">Calle</label>
                    <input name="calle" type="text"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('calle', $propiedad->calle) }}" required>
                </div>

                {{-- Numeración y Hectáreas --}}
                <div>
                    <label class="block font-semibold mb-1" for="numeracion">Numeración</label>
                    <input name="numeracion" type="number"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('numeracion', $propiedad->numeracion) }}" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Hectáreas</label>
                    <input id="hectareas" name="hectareas" type="text" inputmode="decimal"
                        class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('hectareas', $propiedad->hectareas) }}"
                        placeholder="Hectáreas totales" required>
                    <p class="text-xs text-gray-500 mt-1">Recuerda indicar las hectáreas con malla más abajo.</p>
                </div>

                {{-- Mapa (2 columnas) --}}
                @php
                    $selectedLat = old('lat', $propiedad->lat);
                    $selectedLng = old('lng', $propiedad->lng);
                    $hasSelectedLocation = $selectedLat && $selectedLng;
                    $hasCoordinateError = $errors->has('lat') || $errors->has('lng');
                @endphp
                <div class="md:col-span-2 location-map-field scroll-mt-24" data-has-coordinate-error="{{ $hasCoordinateError ? '1' : '0' }}">
                    <label class="block font-semibold mb-1">Ubicación</label>
                    <div class="mb-4 rounded-md border-l-4 border-blue-600 bg-blue-50 p-4 text-blue-950 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                            <span class="text-2xl leading-none" aria-hidden="true">📍</span>
                            <div>
                                <h3 class="text-base font-bold">Seleccione la ubicación exacta de la propiedad</h3>
                                <p class="mt-1 text-sm font-medium text-blue-900">
                                    Haga clic sobre el mapa para colocar el marcador. También puede arrastrarlo para ajustar la posición.
                                </p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="lat" class="lat-input" value="{{ $selectedLat }}">
                    <input type="hidden" name="lng" class="lng-input" value="{{ $selectedLng }}">
                    <div class="mb-3">
                        <span class="location-status-badge inline-flex rounded-full px-3 py-1 text-sm font-bold {{ $hasSelectedLocation ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $hasSelectedLocation ? '✅ Ubicación seleccionada correctamente' : '⚠️ Ubicación pendiente de seleccionar' }}
                        </span>
                    </div>
                    <div class="relative">
                        <div class="map-element w-full h-56 rounded border {{ $hasCoordinateError ? 'border-2 border-red-500' : '' }}" tabindex="-1" aria-label="Mapa para seleccionar la ubicación exacta de la propiedad"></div>
                        <div class="location-map-help {{ $hasSelectedLocation ? 'hidden' : '' }} pointer-events-none absolute left-3 right-3 top-3 z-[1000] rounded-lg bg-yellow-200 px-4 py-3 text-sm font-bold text-yellow-950 shadow-lg sm:left-4 sm:right-auto">
                            📍 Haga clic en el mapa para marcar la ubicación
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Coordenadas:
                        <span class="coordenadas-display font-semibold">
                            {{ $hasSelectedLocation ? $selectedLat.', '.$selectedLng : 'No seleccionada' }}
                        </span>
                        <span id="map-error" class="text-red-600 font-semibold {{ $hasCoordinateError ? '' : 'hidden' }}">
                            {{ $errors->first('lat') ?: $errors->first('lng') ?: '(Obligatorio)' }}
                        </span>
                    </p>
                </div>

                {{-- Derecho de riego (junto a hectáreas) --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="derecho_riego" name="derecho_riego"
                            class="mr-2 custom-checkbox"
                            {{ old('derecho_riego', $propiedad->derecho_riego) ? 'checked' : '' }}>
                        <label>¿Tiene derecho de riego?</label>
                    </div>
                    <div id="tipoDerechoRiegoDiv" class="hidden mt-2">
                        <select name="tipo_derecho_riego" class="w-full p-2 border border-gray-300 rounded">
                            <option value="">Seleccione...</option>
                            @foreach(\App\Models\Propiedad::getTipoDerechoRiegoForForm() as $value => $label)
                                <option value="{{ $value }}" {{ $propiedad->tipo_derecho_riego == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tenencia --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-3">
                        Tipo de tenencia
                    </label>

                    <div class="space-y-3">
                        <!-- Propietario -->
                        <label class="flex items-center cursor-pointer">
                            <input type="radio"
                                   name="tipo_tenencia"
                                   value="propietario"
                                   class="mr-3 tenencia-radio"
                                   required
                                   {{ old('tipo_tenencia', $propiedad->tipo_tenencia) === 'propietario' ? 'checked' : '' }}>
                            <span>Propietario</span>
                        </label>

                        <!-- Arrendatario -->
                        <label class="flex items-center cursor-pointer">
                            <input type="radio"
                                   name="tipo_tenencia"
                                   value="arrendatario"
                                   class="mr-3 tenencia-radio"
                                   {{ old('tipo_tenencia', $propiedad->tipo_tenencia) === 'arrendatario' ? 'checked' : '' }}>
                            <span>Arrendatario</span>
                        </label>

                        <!-- Otro con input en línea -->
                        <label class="flex items-center gap-4 cursor-pointer">
                            <input type="radio"
                                   name="tipo_tenencia"
                                   value="otros"
                                   class="tenencia-radio"
                                   {{ old('tipo_tenencia', $propiedad->tipo_tenencia) === 'otros' ? 'checked' : '' }}>

                            <span class="whitespace-nowrap">Otro</span>

                            <input type="text"
                                   name="especificar_tenencia"
                                   class="w-72 p-2 border border-gray-300 rounded text-sm
                                          {{ old('tipo_tenencia', $propiedad->tipo_tenencia) === 'otros' ? '' : 'hidden' }}"
                                   placeholder="Especifique la condición"
                                   value="{{ old('especificar_tenencia', $propiedad->especificar_tenencia) }}">
                        </label>
                    </div>

                    @error('tipo_tenencia')
                    <div class="mt-2 text-red-600 text-sm">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="space-y-6">
                {{-- Derecho de riego (removido de aquí) --}}
                {{-- RUT --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="rut" name="rut"
                            class="mr-2 custom-checkbox"
                            {{ old('rut', $propiedad->rut) ? 'checked' : '' }}>
                        <label>¿Posee RUT?</label>
                    </div>

                    <div id="rutFields" class="hidden ml-7 mt-2">
                        <input name="rut_valor" type="number" step="1"
                            class="w-full p-2 border border-gray-300 rounded"
                        value="{{ old('rut_valor', $propiedad->rut_valor ?? '') }}"
>
                    </div>

                    {{-- Adjunto RUT --}}
                    <div id="rutArchivoDiv" class="hidden ml-7 mt-2">
                        <label class="block font-semibold mb-1">Adjunto RUT</label>
                        @if ($propiedad->rut_archivo)
                            <p class="text-sm text-gray-500 mb-2">
                                Archivo actual: 
                                <a href="{{ Storage::url($propiedad->rut_archivo) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                    Ver archivo
                                </a>
                            </p>
                        @endif
                        <input type="file" name="rut_archivo_file" class="w-full p-2 border border-gray-300 rounded" accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">Sube un nuevo archivo si deseas reemplazar el actual (PDF, JPG, PNG - máx 2MB).</p>
                    </div>
                </div>

                {{-- Malla --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="malla" name="malla"
                            class="mr-2 custom-checkbox"
                            {{ old('malla', $propiedad->malla) ? 'checked' : '' }}>
                        <label>¿Tiene malla antigranizo?</label>
                    </div>

                      <div id="mallaFields" class="hidden ml-7 mt-2">
                          <input id="hectareas_malla" name="hectareas_malla" type="text" inputmode="decimal"
                              class="w-full p-2 border border-gray-300 rounded transition-colors"
                              value="{{ old('hectareas_malla', $propiedad->hectareas_malla) }}">
                          <p id="hectareas-malla-hint" class="text-sm text-gray-500 mt-1">Seleccione "tiene malla antigranizo" primero</p>
                      </div>
                </div>

                {{-- Cierre --}}
                <div class="flex items-center">
                    <input type="checkbox" name="cierre_perimetral"
                        class="mr-2 custom-checkbox"
                        {{ old('cierre_perimetral', $propiedad->cierre_perimetral) ? 'checked' : '' }}>
                    <label>¿Tiene cierre perimetral?</label>
                </div>
            </div>

        

            <button type="submit"
                class="mt-8 w-full py-2 bg-azul-marino text-white font-bold rounded hover:bg-amarillo-claro hover:text-azul-marino transition">
                Guardar cambios
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
<style>
    .custom-checkbox {
        width: 1.25rem; height: 1.25rem; border-radius: 0.25rem;
        border: 2px solid #cbd5e1; background: #fff; appearance: none;
        outline: none; transition: border-color 0.2s, box-shadow 0.2s; cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: #2563eb; border-color: #2563eb; box-shadow: 0 0 0 2px #93c5fd;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[required]').forEach(function(el) {
        el.addEventListener('invalid', function() {
            if (this.validity.valueMissing) {
                this.setCustomValidity('Este campo es obligatorio.');
            }
        });
        el.addEventListener('input', function() { this.setCustomValidity(''); });
        el.addEventListener('change', function() { this.setCustomValidity(''); });
    });

    const form = document.querySelector('form');
    const tenenciaRadios = document.querySelectorAll('.tenencia-radio');
    const inputOtro = document.querySelector('input[name="especificar_tenencia"]');
    const riego = document.getElementById('derecho_riego');
    const riegoDiv = document.getElementById('tipoDerechoRiegoDiv');
    const rut = document.getElementById('rut');
    const rutDiv = document.getElementById('rutFields');
    const rutValorInput = document.querySelector('input[name="rut_valor"]');
    const rutArchivoDiv = document.getElementById('rutArchivoDiv');
    const rutArchivo = document.querySelector('input[name="rut_archivo_file"]');
    const mallaChk = document.getElementById('malla');
    const mallaDiv = document.getElementById('mallaFields');
    const totInput = document.getElementById('hectareas');
    const mallaInput = document.getElementById('hectareas_malla');
    const hint = document.getElementById('hectareas-malla-hint');
    const locationField = document.querySelector('.location-map-field');
    const scrollToLocationField = () => {
        if (!locationField) return;

        locationField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        locationField.querySelector('.map-element')?.focus({ preventScroll: true });
    };

    const toggle = (check, div) => div?.classList.toggle('hidden', !check.checked);

    const toggleOtros = () => {
        const sel = document.querySelector('.tenencia-radio:checked');
        if (!sel || sel.value !== 'otros') {
            inputOtro.classList.add('hidden');
            inputOtro.value = '';
        } else {
            inputOtro.classList.remove('hidden');
            inputOtro.focus();
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
            const lat = document.querySelector('.lat-input');
            const lng = document.querySelector('.lng-input');
            const mapError = document.getElementById('map-error');
            const mapEl = document.querySelector('.map-element');
            if (mapError) mapError.classList.add('hidden');
            if (mapEl) mapEl.classList.remove('border-red-500', 'border-2');

            if (!lat?.value || !lng?.value) {
                e.preventDefault();
                if (mapEl) {
                    mapEl.classList.add('border-red-500', 'border-2');
                }
                if (mapError) mapError.classList.remove('hidden');
                scrollToLocationField();
                return false;
            }

            validarMalla();
            if (!esMallaValida()) {
                e.preventDefault();
                mallaInput?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                mallaInput?.focus();
                return false;
            }

            if (rut?.checked && !rutValorInput?.value && (!rutArchivo?.files || rutArchivo.files.length === 0)) {
                rut.checked = false;
                toggle(rut, rutDiv);
                toggle(rut, rutArchivoDiv);
                if (rutValorInput) { rutValorInput.value = ''; rutValorInput.required = false; }
                if (rutArchivo) rutArchivo.value = '';
            }
        });
    }

    // ──────────────────────────────────────────────
    // Event wiring
    // ──────────────────────────────────────────────
    tenenciaRadios.forEach(r => r.addEventListener('change', toggleOtros));
    if (riego && riegoDiv) riego.addEventListener('change', () => toggle(riego, riegoDiv));

    if (rut && rutDiv && rutArchivoDiv && rutValorInput) {
        rut.addEventListener('change', () => {
            toggle(rut, rutDiv);
            toggle(rut, rutArchivoDiv);
            if (!rut.checked) {
                rutValorInput.value = '';
                rutValorInput.required = false;
                if (rutArchivo) rutArchivo.value = '';
            } else {
                if (rutArchivo?.files?.length > 0) {
                    rutValorInput.required = true;
                }
            }
        });
    }

    if (rutArchivo) {
        rutArchivo.addEventListener('change', () => {
            if (rut?.checked && rutArchivo.files.length > 0) {
                if (rutValorInput) rutValorInput.required = true;
            } else if (rut?.checked) {
                if (rutValorInput) rutValorInput.required = false;
            }
        });
    }

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
    if (rut && rutDiv && rutArchivoDiv) {
        toggle(rut, rutDiv);
        toggle(rut, rutArchivoDiv);
    }
    if (rutValorInput) {
        rutValorInput.required = rut?.checked && (rutArchivo?.files?.length > 0);
        rutValorInput.addEventListener('invalid', function() {
            if (this.validity.valueMissing) {
                this.setCustomValidity('Este campo es obligatorio.');
            }
        });
        rutValorInput.addEventListener('input', function() { this.setCustomValidity(''); });
    }
    if (mallaChk && mallaDiv) toggle(mallaChk, mallaDiv);
    if (mallaChk?.checked) validarMalla();

    if (locationField?.dataset.hasCoordinateError === '1') {
        window.setTimeout(scrollToLocationField, 150);
    }
});
</script>


<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapElements = document.querySelectorAll('.map-element');
    
    mapElements.forEach(function(mapElement) {
        let map, marker;
        
        const locationField = mapElement.closest('.location-map-field');
        const latInput = locationField?.querySelector('.lat-input');
        const lngInput = locationField?.querySelector('.lng-input');
        const coordDisplay = locationField?.querySelector('.coordenadas-display');
        const statusBadge = locationField?.querySelector('.location-status-badge');
        const mapHelp = locationField?.querySelector('.location-map-help');
        
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
            attribution: '&copy; Esri, Maxar, Earthstar Geographics'
        }).addTo(map),
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
            latInput.setCustomValidity('');
            lngInput.setCustomValidity('');
            coordDisplay.textContent = latlng.lat.toFixed(7) + ', ' + latlng.lng.toFixed(7);

            if (statusBadge) {
                statusBadge.className = 'location-status-badge inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-800';
                statusBadge.textContent = '✅ Ubicación seleccionada correctamente';
            }
            if (mapHelp) mapHelp.classList.add('hidden');
            const mapError = document.getElementById('map-error');
            if (mapError) mapError.classList.add('hidden');
            const mapEl = mapElement;
            if (mapEl) mapEl.classList.remove('border-red-500', 'border-2');
        }
    });
});
</script>
@endpush
