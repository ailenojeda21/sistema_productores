<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de documento - Sistema RUPAL</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #0f172a;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            max-width: 420px;
            width: 100%;
            padding: 40px 32px;
            text-align: center;
        }
        .badge {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 20px;
        }
        .badge-ok { background: #dcfce7; color: #15803d; }
        .badge-err { background: #fee2e2; color: #b91c1c; }
        h1 { font-size: 20px; margin-bottom: 6px; }
        .estado { font-size: 14px; margin-bottom: 24px; }
        .estado.ok { color: #15803d; }
        .estado.err { color: #b91c1c; }
        dl { text-align: left; border-top: 1px solid #e2e8f0; padding-top: 18px; }
        dt { font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #64748b; margin-bottom: 2px; }
        dd { font-size: 15px; font-weight: 600; margin-bottom: 14px; word-break: break-word; }
        footer { margin-top: 26px; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; }
    </style>
</head>
<body>
    <main class="card">
        @if($valido)
            <div class="badge badge-ok">&#10003;</div>
            <h1>Verificaci&oacute;n de documento</h1>
            <p class="estado ok">Documento v&aacute;lido, emitido por el Sistema RUPAL</p>
            <dl>
                <dt>Productor</dt>
                <dd>{{ $nombre }}</dd>

                <dt>Tipo de documento</dt>
                <dd>{{ $tipoDocumento }}</dd>

                <dt>Verificado el</dt>
                <dd>{{ $verificadoEl }}</dd>
            </dl>
        @else
            <div class="badge badge-err">&#10007;</div>
            <h1>Verificaci&oacute;n de documento</h1>
            <p class="estado err">No se pudo verificar este documento</p>
        @endif
        <footer>Sistema RUPAL &mdash; Registro &Uacute;nico de Productores Agropecuarios<br>Municipalidad de Lavalle</footer>
    </main>
</body>
</html>
