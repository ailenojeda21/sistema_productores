{{-- Comprobante PDF: oculto en pantalla, visible al imprimir. --}}
<div id="pdf-certificate" class="pdf-container">
    <div class="pdf-header">

        <div class="pdf-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="pdf-logo-img">
        </div>

        <div class="pdf-logo-text">
            <div class="pdf-logo-title">SISTEMA RUPAL</div>
            <div class="pdf-logo-subtitle">
                Registro Único de Productores Agropecuarios
            </div>
        </div>

        <div class="pdf-fecha">
            Fecha de emisión: {{ now()->format('d/m/Y') }}
        </div>

    </div>

    <div class="pdf-title-section">
        <h1 class="pdf-main-title">COMPROBANTE DE REGISTRO</h1>
        <div class="pdf-subtitle">Folio: CERT-{{ str_pad((string) $user->id, 6, '0', STR_PAD_LEFT) }}-{{ now()->format('Ymd') }}</div>
    </div>

    <div class="pdf-section">
        <h2 class="pdf-section-title">1. Datos del Productor</h2>
        <table class="pdf-data-table">
            <tbody>
                <tr>
                    <td class="pdf-field-label">Nombre completo:</td>
                    <td class="pdf-field-value">{{ $user->name }}</td>
                    <td class="pdf-field-label">DNI:</td>
                    <td class="pdf-field-value">{{ $user->dni ?? 'No registrado' }}</td>
                </tr>
                <tr>
                    <td class="pdf-field-label">Correo electrónico:</td>
                    <td class="pdf-field-value">{{ $user->email }}</td>
                    <td class="pdf-field-label">Teléfono:</td>
                    <td class="pdf-field-value">{{ $user->telefono ?? 'No registrado' }}</td>
                </tr>
                <tr>
                    <td class="pdf-field-label">Dirección:</td>
                    <td class="pdf-field-value" colspan="3">{{ $user->direccion ?? 'No registrada' }}</td>
                </tr>
                <tr>
                    <td class="pdf-field-label">Fecha de registro:</td>
                    <td class="pdf-field-value" colspan="3">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pdf-section">
        <h2 class="pdf-section-title">2. Resumen General</h2>
        <table class="pdf-summary-table">
            <thead>
                <tr>
                    <th>Total Propiedades</th>
                    <th>Total Cultivos</th>
                    <th>Total Maquinarias</th>
                    <th>Comercialización</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pdf-stat-value">{{ $stats['propiedades'] }}</td>
                    <td class="pdf-stat-value">{{ $stats['cultivos'] }}</td>
                    <td class="pdf-stat-value">{{ $stats['maquinarias'] }}</td>
                    <td class="pdf-stat-value">{{ $stats['comercializacion'] > 0 ? 'Sí' : 'No' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($propiedades->isNotEmpty())
        <div class="pdf-section">
            <h2 class="pdf-section-title">3. Propiedades Registradas</h2>
            @foreach($propiedades as $index => $prop)
                <div class="pdf-property-block">
                    <h3 class="pdf-property-title">3.{{ $index + 1 }} {{ $prop->direccion_completa }}</h3>
                    <table class="pdf-property-table">
                        <tbody>
                            <tr>
                                <td class="pdf-field-label">Hectáreas Totales:</td>
                                <td class="pdf-field-value">{{ $prop->hectareas }} ha</td>
                                <td class="pdf-field-label">Tipo de Tenencia:</td>
                                <td class="pdf-field-value">{{ $prop->tipo_tenencia === 'otros' && $prop->especificar_tenencia ? 'Otros - ' . $prop->especificar_tenencia : ($prop->tipo_tenencia ?? 'No') }}</td>
                            </tr>
                            <tr>
                                <td class="pdf-field-label">Derecho de Riego:</td>
                                <td class="pdf-field-value">{{ $prop->derecho_riego ? 'Sí' : 'No' }}</td>
                                <td class="pdf-field-label">Tipo de Derecho:</td>
                                <td class="pdf-field-value">{{ $prop->derecho_riego && $prop->tipo_derecho_riego ? $prop->tipo_derecho_riego : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="pdf-field-label">Malla:</td>
                                <td class="pdf-field-value">{{ $prop->malla ? 'Sí' : 'No' }}</td>
                                <td class="pdf-field-label">Hectáreas Malla:</td>
                                <td class="pdf-field-value">{{ $prop->malla && $prop->hectareas_malla ? $prop->hectareas_malla . ' ha' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="pdf-field-label">Cierre Perimetral:</td>
                                <td class="pdf-field-value">{{ $prop->cierre_perimetral ? 'Sí' : 'No' }}</td>
                                <td class="pdf-field-label">RUT:</td>
                                <td class="pdf-field-value">{{ $prop->rut ? 'Sí' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="pdf-field-label">N° RUT:</td>
                                <td class="pdf-field-value">{{ $prop->rut && $prop->rut_valor ? floor((float) $prop->rut_valor) : 'No' }}</td>
                                <td class="pdf-field-label">Archivo RUT:</td>
                                <td class="pdf-field-value">{{ !empty($prop->rut_archivo) ? 'Sí' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="pdf-field-label">Coordenadas:</td>
                                <td class="pdf-field-value pdf-coords" colspan="3">{{ $prop->lat && $prop->lng ? $prop->lat . ', ' . $prop->lng : 'No registradas' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    @if($cultivos->isNotEmpty())
        <div class="pdf-section">
            <h2 class="pdf-section-title">4. Cultivos Registrados</h2>
            <table class="pdf-full-table">
                <thead>
                    <tr>
                        <th style="width: 5%">N°</th>
                        <th style="width: 25%">Propiedad</th>
                        <th style="width: 20%">Nombre</th>
                        <th style="width: 15%">Tipo</th>
                        <th style="width: 15%">Hectáreas</th>
                        <th style="width: 20%">Riego</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cultivos as $idx => $cult)
                        <tr>
                            <td class="pdf-cell-center">{{ $idx + 1 }}</td>
                            <td>{{ $cult->propiedad?->direccion_completa ?? 'No especificada' }}</td>
                            <td>{{ $cult->variedad }}</td>
                            <td>{{ $cult->tipo }}</td>
                            <td class="pdf-cell-center">{{ $cult->hectareas }} ha</td>
                            <td>{{ $cult->tecnologia_riego ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($maquinarias->isNotEmpty())
        <div class="pdf-section">
            <h2 class="pdf-section-title">5. Maquinarias por Propiedad</h2>
            @foreach($maquinarias as $midx => $maq)
                <div class="pdf-machinery-block">
                    <h3 class="pdf-machinery-title">5.{{ $midx + 1 }} {{ $maq->propiedad?->direccion_completa ?? 'Propiedad no especificada' }}</h3>
                    <table class="pdf-machinery-info-table">
                        <tbody>
                            <tr>
                                <td class="pdf-field-label">Tractor:</td>
                                <td class="pdf-field-value">{{ $maq->tractor ? 'Sí' : 'No' }}</td>
                                @if($maq->tractor && $maq->modelo_tractor)
                                    <td class="pdf-field-label">Modelo:</td>
                                    <td class="pdf-field-value">{{ $maq->modelo_tractor }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    @php $activos = $maq->implementos_activos; @endphp
                    @if(!empty($activos))
                        <h4 class="pdf-implementos-title">Implementos disponibles:</h4>
                        <ul class="pdf-list">
                            @foreach($activos as $implemento)
                                <li>{{ $implemento }}</li>
                            @endforeach
                        </ul>
                    @else
                        <h4 class="pdf-implementos-title">Implementos disponibles:</h4>
                        <p class="pdf-no-data">Sin implementos cargados</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if($comercio)
        <div class="pdf-section">
            <h2 class="pdf-section-title">6. Comercialización</h2>
            <table class="pdf-data-table">
                <tbody>
                    <tr>
                        <td class="pdf-field-label">Infraestructura de empaque:</td>
                        <td class="pdf-field-value">{{ $comercio->infraestructura_empaque ? 'Sí' : 'No' }}</td>
                        <td class="pdf-field-label">Vende en finca:</td>
                        <td class="pdf-field-value">{{ $comercio->vende_en_finca ? 'Sí' : 'No' }}</td>
                    </tr>
                    @if(!empty($comercio->mercados))
                        <tr>
                            <td class="pdf-field-label">Mercados donde comercializa:</td>
                            <td class="pdf-field-value pdf-list-cell" colspan="3">
                                <ul class="pdf-table-list">
                                    @foreach($comercio->mercados as $mercado)
                                        <li>{{ $mercado }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                    @if(!empty($comercio->cooperativas))
                        <tr>
                            <td class="pdf-field-label">Cooperativas de comercialización:</td>
                            <td class="pdf-field-value pdf-list-cell" colspan="3">
                                <ul class="pdf-table-list">
                                    @foreach($comercio->cooperativas as $coop)
                                        <li>{{ $coop }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    @if($propiedades->isEmpty() && $cultivos->isEmpty() && $maquinarias->isEmpty() && !$comercio)
        <div class="pdf-section">
            <p class="pdf-no-data">Este productor no tiene datos registrados en los módulos del sistema.</p>
        </div>
    @endif

    <div class="pdf-footer">
        <div class="pdf-footer-content">
            <p class="pdf-footer-text">Este documento fue generado por el Sistema RUPAL - Registro Único de Productores Agropecuarios</p>
            <p class="pdf-footer-text">Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="pdf-signature-section">
            <div class="pdf-signature-box">
                <div class="pdf-signature-line-text"></div>
                <p class="pdf-signature-label">Firma y Sello</p>
                <p class="pdf-signature-org">Organismo Certificante</p>
            </div>
        </div>
    </div>
</div>

<script>
    function printCertificate() {
        const originalTitle = document.title;
        const dateStr = new Date().toISOString().slice(0, 10);
        const safeName = @json($user->name).replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '_');
        document.title = 'Comprobante_Registro_' + safeName + '_' + dateStr;
        window.print();
        setTimeout(() => { document.title = originalTitle }, 100);
    }
</script>

@push('styles')
<style>
#pdf-certificate {
    display: none;
}

.tooltip-download {
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%);
}

@media (min-width: 640px) {
    .tooltip-download {
        opacity: 0;
        pointer-events: none;
    }
    .group\/dl:hover .tooltip-download {
        opacity: 1;
    }
}

@media (max-width: 639px) {
    .tooltip-download {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
    .tooltip-download.mobile-show {
        opacity: 1;
        pointer-events: auto;
    }
}

@media print {
    aside, header, .fixed, nav, .tooltip-download {
        display: none !important;
    }

    .dashboard-view {
        display: none !important;
    }

    html, body {
        height: auto !important;
        overflow: visible !important;
    }
    .min-h-screen, .min-h-dvh, .h-screen, .overflow-hidden, .overflow-y-auto, .overflow-auto {
        height: auto !important;
        overflow: visible !important;
        background: white !important;
    }

    .w-full.max-w-5xl.mx-auto > :not(.bg-white) {
        display: none !important;
    }

    .bg-white > :not(#pdf-certificate) {
        display: none !important;
    }

    .bg-white.rounded-lg {
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    #pdf-certificate {
        display: block !important;
        width: auto !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        box-sizing: border-box !important;
    }

    @page {
        size: A4;
        margin: 15mm 20mm;
    }

    body {
        background: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .pdf-header {
        display: grid;
        grid-template-columns: 120px 1fr 180px;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 3px solid #F39200;
        margin-bottom: 20px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-logo {
        display: flex;
        align-items: flex-start;
        align-self: start;
    }

    .pdf-logo-img {
        height: 50px;
        width: auto;
        margin-top: -6px;
    }
    .pdf-logo-text {
        text-align: center;
        justify-self: center;
    }

    .pdf-logo-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #F39200;
        letter-spacing: .5px;
    }

    .pdf-logo-subtitle {
        margin-top: 4px;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .pdf-fecha {
        justify-self: end;
        align-self: start;
        text-align: right;
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .pdf-title-section {
        text-align: center;
        margin-bottom: 25px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-main-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pdf-subtitle {
        font-size: 12px;
        color: #F39200;
        font-weight: 600;
    }

    .pdf-section {
        margin-bottom: 25px;
        page-break-inside: auto;
        break-inside: auto;
    }

    .pdf-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #F39200;
        margin: 0 0 12px 0;
        padding-bottom: 6px;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        page-break-after: avoid;
        break-after: avoid;
    }

    .pdf-data-table,
    .pdf-summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-bottom: 15px;
        page-break-inside: auto;
        break-inside: auto;
    }

    .pdf-data-table th,
    .pdf-data-table td,
    .pdf-summary-table th,
    .pdf-summary-table td {
        border: 1px solid #cbd5e1;
        padding: 8px 10px;
        vertical-align: middle;
    }

    .pdf-summary-table thead {
        background-color: #F39200;
        color: white;
    }

    .pdf-summary-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 9px;
        letter-spacing: 0.3px;
    }

    .pdf-data-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    thead { display: table-header-group; }
    tfoot { display: table-footer-group; }

    .pdf-field-label {
        background-color: #f1f5f9;
        font-weight: 600;
        color: #475569;
        width: 25%;
        font-size: 9px;
    }

    .pdf-field-value {
        color: #0f172a;
        font-weight: 500;
    }

    .pdf-summary-table {
        margin-bottom: 20px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-summary-table td {
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #F39200;
        padding: 15px;
    }

    .pdf-stat-value {
        color: #F39200;
    }

    .pdf-coords {
        font-family: "Courier New", monospace;
        font-size: 9px;
        color: #64748b;
    }

    .pdf-property-block {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        background-color: #fafafa;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-property-title {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 1px solid #cbd5e1;
    }

    .pdf-property-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-bottom: 0;
        background-color: white;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-property-table th,
    .pdf-property-table td {
        border: 1px solid #cbd5e1;
        padding: 8px 10px;
        vertical-align: middle;
    }

    .pdf-full-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        margin-bottom: 15px;
        page-break-inside: auto;
        break-inside: auto;
    }

    .pdf-full-table th,
    .pdf-full-table td {
        border: 1px solid #cbd5e1;
        padding: 8px 10px;
        vertical-align: middle;
    }

    .pdf-full-table thead {
        background-color: #F39200;
        color: white;
    }

    .pdf-full-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 9px;
        letter-spacing: 0.3px;
        padding: 10px 8px;
    }

    .pdf-full-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .pdf-cell-center {
        text-align: center;
    }

    .pdf-machinery-block {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        background-color: #fafafa;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-machinery-title {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 1px solid #cbd5e1;
    }

    .pdf-machinery-info-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        background-color: white;
        margin-bottom: 12px;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-machinery-info-table th,
    .pdf-machinery-info-table td {
        border: 1px solid #cbd5e1;
        padding: 8px 10px;
        vertical-align: middle;
    }

    .pdf-implementos-title {
        font-size: 10px;
        font-weight: 600;
        color: #475569;
        margin: 0 0 8px 0;
        text-transform: uppercase;
    }

    .pdf-list {
        list-style: disc;
        margin: 0;
        padding-left: 20px;
    }

    .pdf-list li {
        font-size: 10px;
        color: #334155;
        margin-bottom: 3px;
    }

    .pdf-list-cell {
        padding: 10px !important;
    }

    .pdf-table-list {
        list-style: disc;
        margin: 0;
        padding-left: 18px;
        font-size: 9px;
        line-height: 1.5;
    }

    .pdf-table-list li {
        margin-bottom: 2px;
        color: #0f172a;
    }

    .pdf-no-data {
        text-align: center;
        font-size: 11px;
        color: #64748b;
        font-style: italic;
        padding: 20px;
        border: 1px dashed #cbd5e1;
        background-color: #f8fafc;
    }

    .pdf-numbered-list {
        list-style: decimal;
        margin: 0;
        padding-left: 25px;
    }

    .pdf-numbered-list li {
        font-size: 10px;
        color: #334155;
        margin-bottom: 4px;
    }

    .pdf-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #e2e8f0;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .pdf-footer-content {
        text-align: center;
        margin-bottom: 30px;
    }

    .pdf-footer-text {
        font-size: 9px;
        color: #64748b;
        margin: 2px 0;
    }

    .pdf-signature-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .pdf-signature-box {
        width: 200px;
        text-align: center;
    }

    .pdf-signature-line-text {
        border-bottom: 1px solid #334155;
        height: 40px;
        margin-bottom: 8px;
    }

    .pdf-signature-label {
        font-size: 10px;
        font-weight: 600;
        color: #334155;
        margin: 0 0 2px 0;
        text-transform: uppercase;
    }

    .pdf-signature-org {
        font-size: 8px;
        color: #64748b;
        margin: 0;
    }
}
</style>
@endpush