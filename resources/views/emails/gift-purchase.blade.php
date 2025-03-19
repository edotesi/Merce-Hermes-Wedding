<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boda Mercè & Hermes - {{ $emailType == 'confirmation' ? 'Confirmación de Compra' : ($emailType == 'cancellation' ? 'Cancelación de Reserva' : ($emailType == 'reminder' ? 'Recordatorio de Reserva' : 'Reserva de Regalo')) }}</title>
    <style type="text/css">
        /* Outlook y Gmail friendly reset */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Prevenir iOS automatic link styling */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Estilos adicionales para botones */
        .button {
            display: inline-block;
            background-color: #a79f7d;
            color: #6a735b;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            border: none;
            border-radius: 4px;
            mso-padding-alt: 12px 24px;
        }

        .button-outline {
            display: inline-block;
            background-color: transparent;
            border: 1px solid #a79f7d;
            color: #a79f7d;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-radius: 4px;
            mso-padding-alt: 12px 24px;
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Playfair Display', Arial, sans-serif; line-height: 1.6; background-color: #6a735b; color: #c5c49e;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="width: 100%; background-color: #6a735b;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="width: 600px; max-width: 600px; background-color: #6a735b;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 20px 0; border-bottom: 2px solid #a79f7d;">
                            <h1
                                style="margin: 0; font-size: 5rem; font-weight: 300; color: #c5c49e; letter-spacing: 2px;">
                                Boda Mercè & Hermes</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 20px; color: #a79f7d;">
                            @if ($emailType == 'reservation')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e; text-align: left;">
                                    ¡{{ $purchaserName }}, gracias por tu reserva!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Has reservado el regalo:
                                    <strong>{{ $giftName }}</strong></p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Tu código de reserva es:</p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1); border: 1px solid #a79f7d; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Puedes comprar el regalo aquí:</p>
                                <div style="text-align: center; margin: 20px 0;">
                                    <!-- Botón de tienda usando estructura table para mejor compatibilidad -->
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                        <tr>
                                            <td align="center" style="padding: 12px 24px; background-color: #a79f7d; border-radius: 4px;">
                                                <a href="{{ $productUrl ?: '#' }}" target="_blank"
                                                   style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b; text-decoration: none; display: inline-block;">
                                                   Ir a la tienda
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <p style="margin: 0 0 10px 0; color: #c5c49e;"><strong>¡Importante!</strong></p>
                                <ul style="margin: 0 0 20px 20px; padding: 0; color: #c5c49e;">
                                    <li style="margin-bottom: 5px;">Esta reserva es válida durante 48 horas</li>
                                    <li>Para confirmar tu compra, necesitarás usar este código</li>
                                </ul>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%" align="center" style="padding-right: 10px;">
                                                <!-- Botón de cancelar -->
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="center" style="border: 1px solid #a79f7d; border-radius: 4px;">
                                                            <a href="{{ $cancelUrl ?: '#' }}" target="_blank"
                                                               style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #a79f7d; text-decoration: none; display: inline-block; padding: 12px 24px;">
                                                               Cancelar Reserva
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="50%" align="center" style="padding-left: 10px;">
                                                <!-- Botón de confirmar -->
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="center" style="padding: 12px 24px; background-color: #a79f7d; border-radius: 4px;">
                                                            <a href="{{ $confirmUrl ?: '#' }}" target="_blank"
                                                               style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b; text-decoration: none; display: inline-block;">
                                                               Confirmar Compra
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @elseif ($emailType == 'reminder')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e; text-align: left;">
                                    ¡{{ $purchaserName }}, tu reserva expirará pronto!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Te recordamos que has reservado el regalo:
                                    <strong>{{ $giftName }}</strong></p>

                                <div style="background-color: rgba(255,255,255,0.1); border: 1px solid #a79f7d; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e; border-radius: 4px;">
                                    <p style="margin: 0; font-size: 18px;"><strong>Tu reserva expirará en:</strong></p>
                                    <p style="margin: 10px 0 0 0; font-size: 24px; color: #d8d7b6;">{{ $hoursRemaining }} horas</p>
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Tu código de reserva es:</p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1); border: 1px solid #a79f7d; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Puedes comprar el regalo aquí:</p>
                                <div style="text-align: center; margin: 20px 0;">
                                    <!-- Botón de tienda usando estructura table para mejor compatibilidad -->
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                        <tr>
                                            <td align="center" style="padding: 12px 24px; background-color: #a79f7d; border-radius: 4px;">
                                                <a href="{{ $productUrl ?: '#' }}" target="_blank"
                                                   style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b; text-decoration: none; display: inline-block;">
                                                   Ir a la tienda
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <p style="margin: 20px 0 10px 0; color: #c5c49e; font-size: 18px;"><strong>¡No pierdas tu reserva!</strong></p>
                                <p style="margin: 0 0 20px 0; color: #c5c49e;">Para mantener tu reserva, necesitas confirmar la compra haciendo clic en el botón de abajo. Si prefieres no completar la compra, puedes cancelar tu reserva para que otros invitados puedan acceder al regalo.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%" align="center" style="padding-right: 10px;">
                                                <!-- Botón de cancelar -->
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="center" style="border: 1px solid #a79f7d; border-radius: 4px;">
                                                            <a href="{{ $cancelUrl ?: '#' }}" target="_blank"
                                                               style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #a79f7d; text-decoration: none; display: inline-block; padding: 12px 24px;">
                                                               Cancelar Reserva
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="50%" align="center" style="padding-left: 10px;">
                                                <!-- Botón de confirmar -->
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="center" style="padding: 12px 24px; background-color: #a79f7d; border-radius: 4px;">
                                                            <a href="{{ $confirmUrl ?: '#' }}" target="_blank"
                                                               style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b; text-decoration: none; display: inline-block;">
                                                               Confirmar Compra
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @elseif ($emailType == 'confirmation')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e; text-align: left;">
                                    ¡{{ $purchaserName }}, gracias por tu compra!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Has confirmado la compra del regalo:
                                    <strong>{{ $giftName }}</strong></p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Tu código de confirmación es:</p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1); border: 1px solid #a79f7d; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Guarda este código por si necesitas hacer
                                    cualquier gestión relacionada con el regalo.</p>
                            @else
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e; text-align: left;">
                                    {{ $purchaserName }}, tu reserva ha sido cancelada</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Has cancelado la reserva del regalo:
                                    <strong>{{ $giftName }}</strong></p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">El código <strong>{{ $uniqueCode }}</strong> ya no es válido.</p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e;">Si cambias de opinión, siempre puedes volver a la lista de regalos y realizar una nueva reserva.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                        <tr>
                                            <td align="center" style="padding: 12px 24px; background-color: #a79f7d; border-radius: 4px;">
                                                <a href="{{ url('/gifts') }}" target="_blank"
                                                   style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b; text-decoration: none; display: inline-block;">
                                                   Ver lista de regalos
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding: 20px; text-align: center; border-top: 1px solid #a79f7d; font-size: 12px; color: #a79f7d;">
                            <p style="margin: 0;">Este email ha sido enviado automáticamente. Por favor, no respondas a
                                este mensaje.</p>
                            <p style="margin: 0;">Si tienes alguna duda, puedes contactar con los novios.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
