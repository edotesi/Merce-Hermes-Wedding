<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>Boda Mercè & Hermes -
        {{ $emailType == 'confirmation' ? 'Confirmación de Compra' : ($emailType == 'cancellation' ? 'Cancelación de Reserva' : ($emailType == 'reminder' ? 'Recordatorio de Reserva' : ($emailType == 'expiration' ? 'Reserva Cancelada Automáticamente' : 'Reserva de Regalo'))) }}
    </title>
    <style type="text/css">
        /* Forzar modo claro */
        :root {
            color-scheme: light only;
            supported-color-schemes: light only;
        }

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
    style="margin: 0; padding: 0; font-family: 'Playfair Display', Arial, sans-serif; line-height: 1.6; background-color: #6a735b !important; color: #c5c49e !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="width: 100%; background-color: #6a735b !important;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="width: 600px; max-width: 600px; background-color: #6a735b !important;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 20px 0; border-bottom: 2px solid #a79f7d;">
                            <img src="{{ asset('images/logo.png') }}" alt="Mercè & Hermes" width="250"
                                style="width: 250px; max-width: 80%; height: auto; display: block; margin: 0 auto;
                                        image-rendering: -webkit-optimize-contrast;
                                        image-rendering: crisp-edges;
                                        -ms-interpolation-mode: nearest-neighbor;
                                        object-fit: contain;">
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 20px; color: #a79f7d;">
                            @if ($emailType == 'reservation')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    ¡{{ $purchaserName }}, gracias por tu reserva!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Has reservado el regalo:
                                    <strong>{{ $giftName }}</strong>
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Tu código de reserva es:</p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1) !important; border: 1px solid #a79f7d !important; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e !important; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Puedes comprar el regalo aquí:
                                </p>
                                <div style="text-align: center; margin: 20px 0;">
                                    <!-- Botón de tienda usando estructura table para mejor compatibilidad -->
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                        style="margin: 0 auto;">
                                        <tr>
                                            <td align="center"
                                                style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                <a href="{{ $productUrl ?: '#' }}" target="_blank"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
                                                    Ir a la tienda
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <p style="margin: 0 0 10px 0; color: #c5c49e !important;"><strong>¡Importante!</strong>
                                </p>
                                <ul style="margin: 0 0 20px 20px; padding: 0; color: #c5c49e !important;">
                                    <li style="margin-bottom: 5px;">Esta reserva es válida durante 48 horas</li>
                                    <li style="margin-bottom: 5px;">Para confirmar tu compra, necesitarás usar este código</li>
                                </ul>

                                <div style="background-color: rgba(255,255,255,0.05) !important; border-left: 3px solid #d8d7b6 !important; padding: 20px; margin: 5px 0 20px 0; color: #c5c49e !important; border-radius: 0 4px 4px 0; font-size: 16px; line-height: 1.6;">
                                    <p style="margin: 0 0 15px 0; color: #d8d7b6 !important; font-weight: bold;">DIRECCIONES PARA EL ENVÍO DE LOS REGALOS:</p>

                                    <p style="margin: 0 0 10px 0; color: #c5c49e !important;">
                                        Por favor, envía el regalo a una de estas direcciones:
                                    </p>

                                    <p style="margin: 0 0 10px 0; color: #c5c49e !important; padding-left: 10px; border-left: 1px solid rgba(191, 181, 141, 0.3);">
                                        c/ de la Salut 125-127, 08202, Sabadell<br>
                                        <strong>Mercè Martinez Montllor</strong>
                                    </p>

                                    <p style="margin: 0; color: #c5c49e !important; padding-left: 10px; border-left: 1px solid rgba(191, 181, 141, 0.3);">
                                        c/ Tenor Masini 112, 1º2ª, 08028, Barcelona<br>
                                        <strong>Hermes Andres I Sobrevia</strong>
                                    </p>
                                </div>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%" align="center" style="padding-right: 10px;">
                                                <!-- Botón de cancelar -->
                                                <table role="presentation" border="0" cellpadding="0"
                                                    cellspacing="0">
                                                    <tr>
                                                        <td align="center"
                                                            style="border: 1px solid #a79f7d !important; border-radius: 4px;">
                                                            <a href="{{ $cancelUrl ?: '#' }}" target="_blank"
                                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #a79f7d !important; text-decoration: none; display: inline-block; padding: 12px 24px;">
                                                                Cancelar Reserva
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="50%" align="center" style="padding-left: 10px;">
                                                <!-- Botón de confirmar -->
                                                <table role="presentation" border="0" cellpadding="0"
                                                    cellspacing="0">
                                                    <tr>
                                                        <td align="center"
                                                            style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                            <a href="{{ $confirmUrl ?: '#' }}" target="_blank"
                                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
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
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    ¡{{ $purchaserName }}, tu reserva expirará pronto!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Te recordamos que has
                                    reservado el regalo:
                                    <strong>{{ $giftName }}</strong>
                                </p>

                                <div
                                    style="background-color: rgba(255,255,255,0.1) !important; border: 1px solid #a79f7d !important; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e !important; border-radius: 4px;">
                                    <p style="margin: 0; font-size: 18px;"><strong>Tu reserva expirará en:</strong></p>
                                    <p style="margin: 10px 0 0 0; font-size: 24px; color: #d8d7b6 !important;">{{ $hoursRemaining }} horas</p>
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Tu código de reserva es:</p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1) !important; border: 1px solid #a79f7d !important; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e !important; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Puedes comprar el regalo aquí:
                                </p>
                                <div style="text-align: center; margin: 20px 0;">
                                    <!-- Botón de tienda usando estructura table para mejor compatibilidad -->
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                        style="margin: 0 auto;">
                                        <tr>
                                            <td align="center"
                                                style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                <a href="{{ $productUrl ?: '#' }}" target="_blank"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
                                                    Ir a la tienda
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div style="background-color: rgba(255,255,255,0.05) !important; border-left: 3px solid #d8d7b6 !important; padding: 20px; margin: 20px 0; color: #c5c49e !important; border-radius: 0 4px 4px 0; font-size: 16px; line-height: 1.6;">
                                    <p style="margin: 0 0 15px 0; color: #d8d7b6 !important; font-weight: bold;">DIRECCIONES PARA EL ENVÍO DE LOS REGALOS:</p>

                                    <p style="margin: 0 0 10px 0; color: #c5c49e !important;">
                                        Por favor, envía el regalo a una de estas direcciones:
                                    </p>

                                    <p style="margin: 0 0 10px 0; color: #c5c49e !important; padding-left: 10px; border-left: 1px solid rgba(191, 181, 141, 0.3);">
                                        C/ de la Salut 125-127, 08202, Sabadell<br>
                                        <strong>Mercè Martinez Montllor</strong>
                                    </p>

                                    <p style="margin: 0; color: #c5c49e !important; padding-left: 10px; border-left: 1px solid rgba(191, 181, 141, 0.3);">
                                        C/ Tenor Masini 112, 1º2ª, 08028, Barcelona<br>
                                        <strong>Hermes Andres I Sobrevia</strong>
                                    </p>
                                </div>

                                <p style="margin: 20px 0 10px 0; color: #c5c49e !important; font-size: 18px;">
                                    <strong>¡No pierdas tu reserva!</strong>
                                </p>
                                <p style="margin: 0 0 20px 0; color: #c5c49e !important;">Para mantener tu reserva,
                                    necesitas confirmar la compra haciendo clic en el botón de abajo. Si prefieres no
                                    completar la compra, puedes cancelar tu reserva para que otros invitados puedan
                                    acceder al regalo.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%" align="center" style="padding-right: 10px;">
                                                <!-- Botón de cancelar -->
                                                <table role="presentation" border="0" cellpadding="0"
                                                    cellspacing="0">
                                                    <tr>
                                                        <td align="center"
                                                            style="border: 1px solid #a79f7d !important; border-radius: 4px;">
                                                            <a href="{{ $cancelUrl ?: '#' }}" target="_blank"
                                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #a79f7d !important; text-decoration: none; display: inline-block; padding: 12px 24px;">
                                                                Cancelar Reserva
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="50%" align="center" style="padding-left: 10px;">
                                                <!-- Botón de confirmar -->
                                                <table role="presentation" border="0" cellpadding="0"
                                                    cellspacing="0">
                                                    <tr>
                                                        <td align="center"
                                                            style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                            <a href="{{ $confirmUrl ?: '#' }}" target="_blank"
                                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
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
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    ¡{{ $purchaserName }}, gracias por tu compra!</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Has confirmado la compra del
                                    regalo:
                                    <strong>{{ $giftName }}</strong>
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Tu código de confirmación es:
                                </p>
                                <div
                                    style="background-color: rgba(255,255,255,0.1) !important; border: 1px solid #a79f7d !important; padding: 15px; margin: 20px 0; text-align: center; color: #c5c49e !important; border-radius: 4px; font-size: 24px;">
                                    {{ $uniqueCode }}
                                </div>

                                <div style="background-color: rgba(255,255,255,0.05) !important; border-left: 3px solid #a79f7d !important; padding: 20px; margin: 25px 0; color: #c5c49e !important; border-radius: 0 4px 4px 0; font-size: 16px; line-height: 1.8;">
                                    <p style="margin: 0 0 15px 0; color: #d8d7b6 !important;">Queremos agradecerte sinceramente por haber elegido un regalo de nuestra lista de bodas.</p>

                                    <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Tu gesto significa mucho para nosotros y estamos seguros de que tu regalo será algo que disfrutaremos durante muchos años. ¡Gracias por acompañarnos en este nuevo capítulo de nuestras vidas!</p>

                                    <p style="margin: 0; color: #c5c49e !important; font-style: italic;">Con todo nuestro cariño,<br>Mercè y Hermes</p>
                                </div>

                                <p style="margin: 25px 0 15px 0; color: #c5c49e !important;">Guarda este código por si necesitas hacer cualquier gestión relacionada con el regalo.</p>
                            @elseif ($emailType == 'cancellation')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    {{ $purchaserName }}, tu reserva ha sido cancelada</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Has cancelado la reserva del
                                    regalo:
                                    <strong>{{ $giftName }}</strong>
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">El código
                                    <strong>{{ $uniqueCode }}</strong> ya no es válido.
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Si cambias de opinión,
                                    siempre puedes volver a la lista de regalos y realizar una nueva reserva.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                        style="margin: 0 auto;">
                                        <tr>
                                            <td align="center"
                                                style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                <a href="{{ url('/gifts') }}" target="_blank"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
                                                    Ver lista de regalos
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @elseif ($emailType == 'expiration')
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    {{ $purchaserName }}, tu reserva ha expirado</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">La reserva del regalo
                                    <strong>{{ $giftName }}</strong> ha sido cancelada automáticamente porque ha
                                    superado el tiempo máximo de reserva (48 horas).
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">El código
                                    <strong>{{ $uniqueCode }}</strong> ya no es válido.
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Si sigues interesado/a en
                                    este regalo, siempre puedes volver a la lista de regalos y realizar una nueva
                                    reserva.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                        style="margin: 0 auto;">
                                        <tr>
                                            <td align="center"
                                                style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                <a href="{{ url('/gifts') }}" target="_blank"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
                                                    Ver lista de regalos
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @else
                                <h2
                                    style="margin: 0 0 15px 0; font-size: 2.5rem; font-weight: 300; color: #c5c49e !important; text-align: left;">
                                    {{ $purchaserName }}, información sobre tu reserva</h2>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Este email contiene
                                    información relacionada con el regalo:
                                    <strong>{{ $giftName }}</strong>
                                </p>

                                <p style="margin: 0 0 15px 0; color: #c5c49e !important;">Si tienes alguna pregunta,
                                    por favor contacta con los novios.</p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                        style="margin: 0 auto;">
                                        <tr>
                                            <td align="center"
                                                style="padding: 12px 24px; background-color: #a79f7d !important; border-radius: 4px;">
                                                <a href="{{ url('/gifts') }}" target="_blank"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #6a735b !important; text-decoration: none; display: inline-block;">
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
                            style="padding: 20px; text-align: center; border-top: 1px solid #a79f7d !important; font-size: 12px; color: #a79f7d !important;">
                            <p style="margin: 0; color: #a79f7d !important;">Este email ha sido enviado
                                automáticamente. Por favor, no respondas a
                                este mensaje.</p>
                            <p style="margin: 0; color: #a79f7d !important;">Si tienes alguna duda, puedes contactar
                                con los novios.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>