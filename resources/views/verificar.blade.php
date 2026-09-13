<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de documento - Sistema RUPAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --naranja: #F39200;
            --naranja-hover: #d98200;
            --amarillo: #F5B410;
            --verde: #16a34a;
            --verde-bg: #f0fdf4;
            --rojo: #dc2626;
            --rojo-bg: #fef2f2;
            --texto: #0f172a;
            --texto-sec: #475569;
            --texto-ter: #64748b;
            --texto-muted: #94a3b8;
            --borde: #f1f5f9;
            --fondo: #f8fafc;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--fondo);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: var(--texto);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Card ── */
        .card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .06), 0 8px 24px rgba(15, 23, 42, .04);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
        }

        /* ── Header institucional ── */
        .card-header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid var(--borde);
        }
        .card-logo {
            height: 52px;
            width: auto;
            margin: 0 auto 16px;
            display: block;
        }
        .card-org {
            font-size: 15px;
            font-weight: 700;
            color: var(--texto-ter);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card-org-sub {
            font-size: 11px;
            color: var(--texto-ter);
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        /* ── Body ── */
        .card-body {
            padding: 32px;
            text-align: center;
        }

        /* ── Icono de estado ── */
        .status-icon {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
        .status-icon svg {
            width: 30px;
            height: 30px;
        }
        .status-icon--ok {
            background: var(--verde-bg);
            color: var(--verde);
        }
        .status-icon--err {
            background: var(--rojo-bg);
            color: var(--rojo);
        }

        /* ── Tipografía ── */
        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--texto);
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .card-desc {
            font-size: 14px;
            color: var(--texto-ter);
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .card-desc--ok {
            color: var(--verde);
            font-weight: 500;
        }

        /* ── Datos de verificación ── */
        .verify-data {
            text-align: left;
            border-top: 1px solid var(--borde);
            padding-top: 20px;
            margin-bottom: 24px;
        }
        .verify-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .verify-row:last-child {
            border-bottom: none;
        }
        .verify-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--texto-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .verify-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--texto);
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }
        .verify-value--muted {
            color: var(--texto-ter);
            font-weight: 500;
        }

        /* ── Badge de estado ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-badge--ok {
            background: var(--verde-bg);
            color: var(--verde);
        }
        .status-badge--err {
            background: var(--rojo-bg);
            color: var(--rojo);
        }
        .status-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .status-badge--ok .status-badge-dot { background: var(--verde); }
        .status-badge--err .status-badge-dot { background: var(--rojo); }

        /* ── Botones ── */
        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .btn:focus-visible {
            outline: 2px solid var(--naranja);
            outline-offset: 2px;
        }
        .btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .btn--primary {
            background: var(--naranja);
            color: #ffffff;
        }
        .btn--primary:hover {
            background: var(--naranja-hover);
        }
        .btn--primary:active {
            background: var(--naranja-hover);
            transform: scale(0.98);
        }
        .btn--secondary {
            background: #f1f5f9;
            color: var(--texto-sec);
        }
        .btn--secondary:hover {
            background: #e2e8f0;
            color: var(--texto);
        }
        .btn--secondary:active {
            background: #e2e8f0;
            transform: scale(0.98);
        }

        /* ── Footer ── */
        .card-footer {
            padding: 20px 32px;
            background: var(--fondo);
            border-top: 1px solid var(--borde);
            text-align: center;
        }
        .footer-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--naranja);
            letter-spacing: 0.3px;
        }
        .footer-desc {
            font-size: 11px;
            color: var(--texto-muted);
            margin-top: 2px;
            line-height: 1.4;
        }
        .footer-muni {
            font-size: 10px;
            color: #cbd5e1;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            body { padding: 16px 12px; }
            .card-header { padding: 24px 20px 18px; }
            .card-body { padding: 24px 20px; }
            .card-footer { padding: 16px 20px; }
            .card-title { font-size: 18px; }
            .verify-row { flex-direction: column; gap: 2px; }
            .verify-value { text-align: left; max-width: 100%; }
        }
    </style>
</head>
<body>
    <main class="card" role="main" aria-label="Verificación de documento">

        {{-- Header institucional --}}
        <div class="card-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Sistema RUPAL" class="card-logo">
            <div class="card-org">Sistema RUPAL</div>
            
        </div>

        {{-- Body --}}
        <div class="card-body">
            @if($valido)
                {{-- ── Estado válido ── --}}
                <div class="status-icon status-icon--ok" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="card-title">Documento verificado</h1>
                <p class="card-desc card-desc--ok">Este documento es válido y fue emitido por el Sistema RUPAL.</p>

                <div class="verify-data" role="list" aria-label="Datos de verificación">
                    <div class="verify-row" role="listitem">
                        <span class="verify-label">Productor</span>
                        <span class="verify-value">{{ $nombre }}</span>
                    </div>
                    <div class="verify-row" role="listitem">
                        <span class="verify-label">Tipo de documento</span>
                        <span class="verify-value">{{ $tipoDocumento }}</span>
                    </div>
                    <div class="verify-row" role="listitem">
                        <span class="verify-label">Verificado el</span>
                        <span class="verify-value">{{ $verificadoEl }}</span>
                    </div>
                    <div class="verify-row" role="listitem">
                        <span class="verify-label">Estado</span>
                        <span class="verify-value">
                            <span class="status-badge status-badge--ok">
                                <span class="status-badge-dot"></span>
                                Válido
                            </span>
                        </span>
                    </div>
                </div>

            @else
                {{-- ── Estado de error ── --}}
                <div class="status-icon status-icon--err" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>

                <h1 class="card-title">No pudimos verificar este documento</h1>

                @if($motivo === 'token_invalido')
                    <p class="card-desc">
                        El código del documento no es válido o el enlace está dañado.
                        Revisá el código QR e intentá nuevamente.
                    </p>
                @elseif($motivo === 'productor_no_encontrado')
                    <p class="card-desc">
                        El código es válido, pero no encontramos un productor registrado asociado a este documento.
                    </p>
                    @if(isset($tipoDocumento))
                        <div class="verify-data" role="list" aria-label="Información del documento">
                            <div class="verify-row" role="listitem">
                                <span class="verify-label">Tipo de documento</span>
                                <span class="verify-value verify-value--muted">{{ $tipoDocumento }}</span>
                            </div>
                            <div class="verify-row" role="listitem">
                                <span class="verify-label">Estado</span>
                                <span class="verify-value">
                                    <span class="status-badge status-badge--err">
                                        <span class="status-badge-dot"></span>
                                        No verificado
                                    </span>
                                </span>
                            </div>
                        </div>
                    @endif
                @else
                    <p class="card-desc">
                        No se pudo completar la verificación. Verificá el código e intentá nuevamente.
                    </p>
                @endif

                <div class="card-actions">
                    <a href="{{ url()->current() }}" class="btn btn--primary" role="button">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.992 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                        </svg>
                        Volver a verificar
                    </a>
                    <a href="{{ url('/') }}" class="btn btn--secondary" role="button">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Volver al inicio
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer institucional --}}
        <div class="card-footer">
            <div class="footer-name">Sistema RUPAL</div>
            <div class="footer-desc">Registro Único de Productores Agropecuarios</div>
            <div class="footer-muni">Municipalidad de Lavalle</div>
        </div>

    </main>
</body>
</html>