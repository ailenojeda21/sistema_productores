<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bienvenido a RUPAL</title>
    <style>
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;background-color:#f4f4f5;" bgcolor="#f4f4f5">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);" bgcolor="#ffffff">

                    {{-- Header con color azul marino --}}
                    <tr>
                        <td align="center" style="background-color:#0a1a3e;padding:40px 24px 32px;" bgcolor="#0a1a3e">
                            <img src="{{ asset('images/logo.png') }}" alt="RUPAL" style="width:140px;height:auto;display:block;margin:0 auto;" width="140">
                        </td>
                    </tr>

                    {{-- Cuerpo del mensaje --}}
                    <tr>
                        <td style="padding:40px 32px 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td style="font-size:24px;font-weight:700;color:#0a1a3e;padding-bottom:20px;">
                                        Hola {{ $user->name }},
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;line-height:1.6;color:#475569;padding-bottom:16px;">
                                        ¡Bienvenido a <strong style="color:#0a1a3e;">RUPAL</strong>! <span style="font-size:18px;">🌱</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;line-height:1.6;color:#475569;padding-bottom:16px;">
                                        Nos alegra que formes parte de nuestra plataforma de gestión para productores agropecuarios.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;line-height:1.6;color:#475569;padding-bottom:16px;">
                                        Con RUPAL podrás administrar de manera simple y segura:
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 0 20px 20px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr><td style="font-size:14px;line-height:1.8;color:#475569;">✓ tus propiedades</td></tr>
                                            <tr><td style="font-size:14px;line-height:1.8;color:#475569;">✓ cultivos</td></tr>
                                            <tr><td style="font-size:14px;line-height:1.8;color:#475569;">✓ maquinaria</td></tr>
                                            <tr><td style="font-size:14px;line-height:1.8;color:#475569;">✓ comercialización</td></tr>
                                            <tr><td style="font-size:14px;line-height:1.8;color:#475569;">✓ y toda la información productiva en un solo lugar.</td></tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;line-height:1.6;color:#475569;padding-bottom:24px;">
                                        Para comenzar a utilizar tu cuenta, necesitamos verificar tu correo electrónico.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;line-height:1.6;color:#475569;padding-bottom:28px;">
                                        Haz clic en el botón <strong>"Verificar correo"</strong> que encontrarás debajo de este mensaje y activa tu cuenta de forma segura.
                                    </td>
                                </tr>

                                {{-- Botón de verificación --}}
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" style="background-color:#d4a017;border-radius:8px;box-shadow:0 2px 8px rgba(212,160,23,0.3);" bgcolor="#d4a017">
                                                    <a href="{{ $verificationUrl }}"
                                                       style="display:inline-block;padding:14px 40px;font-size:16px;font-weight:700;color:#0a1a3e;text-decoration:none;letter-spacing:0.5px;">
                                                        Verificar correo
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Mensaje de seguridad --}}
                                <tr>
                                    <td style="font-size:13px;line-height:1.5;color:#94a3b8;padding-bottom:24px;border-top:1px solid #e2e8f0;padding-top:20px;">
                                        Si no creaste esta cuenta, puedes ignorar este mensaje.<br>
                                        Por motivos de seguridad, este enlace expirará en 60 minutos.
                                    </td>
                                </tr>

                                {{-- Enlace fallback --}}
                                <tr>
                                    <td style="font-size:13px;line-height:1.5;color:#94a3b8;padding-bottom:8px;">
                                        Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;line-height:1.5;color:#64748b;word-break:break-all;padding:8px 12px;background-color:#f8fafc;border-radius:6px;border:1px solid #e2e8f0;">
                                        {{ $verificationUrl }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f8fafc;padding:24px 32px;border-top:1px solid #e2e8f0;" bgcolor="#f8fafc">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td align="center" style="font-size:13px;color:#94a3b8;line-height:1.5;">
                                        Gracias por confiar en <strong style="color:#0a1a3e;">RUPAL</strong>.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size:13px;color:#94a3b8;line-height:1.5;padding-top:4px;">
                                        Equipo RUPAL
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
