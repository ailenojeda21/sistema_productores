<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña - RUPAL</title>
</head>
<body style="margin:0;padding:0;background-color:#eff6ff;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#eff6ff;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="520" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                       {{-- Header --}}
                    <tr>
                        <td align="center" style="background-color:#223362;padding:40px 24px 32px;" bgcolor="#223362">
                            <img src="{{ asset('images/logo2.png') }}" alt="RUPAL" style="width:140px;height:auto;display:block;margin:0 auto;" width="140">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px;">
                            <h1 style="font-size:20px;font-weight:700;color:#1e293b;margin:0 0 12px;">
                                Restablece tu contraseña
                            </h1>
                            <p style="font-size:14px;color:#64748b;line-height:1.6;margin:0 0 24px;">
                                Has solicitado restablecer tu contraseña de usuario del sistema
                                <strong style="color:#223362;">RUPAL</strong>.
                                Haz clic en el botón de abajo para continuar.
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td align="center" style="background-color:#223362;border-radius:8px;padding:12px 32px;">
                                        <a href="{{ $url }}" style="color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;display:inline-block;">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size:12px;color:#94a3b8;line-height:1.5;margin:0 0 16px;">
                                Este enlace expirará en {{ $count }} minutos. Si no solicitaste este cambio, podés ignorar este mensaje.
                            </p>
                            <p style="font-size:12px;color:#94a3b8;line-height:1.5;margin:0;">
                                Si el botón no funciona, copiá y pegá este enlace en tu navegador:<br>
                                <a href="{{ $url }}" style="color:#2563eb;word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f8fafc;padding:16px 40px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="font-size:11px;color:#94a3b8;margin:0;">
                                &copy; {{ date('Y') }} RUPAL — Registro Único de Productores Agrícolas de Lavalle.
                                Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
