<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #6a735b;
        }
        .content {
            padding: 20px 0;
        }
        .code-box {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-size: 24px;
            font-family: monospace;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6a735b;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Boda Mercè & Hermes</h1>
    </div>

    <div class="content">
        @if(!isset($store))
            <h2>¡{{ $purchaserName }}, gracias por tu reserva!</h2>

            <p>Has reservado el regalo: <strong>{{ $giftName }}</strong></p>

            <p>Tu código de reserva es:</p>
            <div class="code-box">
                {{ $uniqueCode }}
            </div>

            <p><strong>¡Importante!</strong></p>
            <ul>
                <li>Esta reserva es válida durante 48 horas</li>
                <li>Para confirmar tu compra, necesitarás usar este código</li>
            </ul>

            <div style="text-align: center; margin: 30px 0;">
                <p>Después de realizar la compra, confirma tu regalo aquí:</p>
                <a href="{{ $confirmUrl }}" class="button" style="background-color: #6a735b; margin-right: 10px;">
                    Confirmar Compra
                </a>

                <p style="margin-top: 20px;">Si has cambiado de opinión, puedes cancelar la reserva:</p>
                <a href="{{ $cancelUrl }}" class="button" style="background-color: #a79f7d;">
                    Cancelar Reserva
                </a>
            </div>

            <p>Puedes comprar el regalo aquí:</p>
            <a href="{{ $productUrl }}" class="button">Ir a la tienda</a>
        @elseif(isset($store))
            <h2>¡{{ $purchaserName }}, gracias por tu compra!</h2>

            <p>Has confirmado la compra del regalo: <strong>{{ $giftName }}</strong></p>

            <p>Detalles de la compra:</p>
            <ul>
                <li><strong>Tienda:</strong> {{ $store }}</li>
                @if($orderNumber)
                    <li><strong>Número de pedido:</strong> {{ $orderNumber }}</li>
                @endif
            </ul>

            <p>Tu código de confirmación es:</p>
            <div class="code-box">
                {{ $uniqueCode }}
            </div>

            <p>Guarda este código por si necesitas hacer cualquier gestión relacionada con el regalo.</p>
        @endif
    </div>

    <div class="footer">
        <p>Este email ha sido enviado automáticamente. Por favor, no respondas a este mensaje.</p>
        <p>Si tienes alguna duda, puedes contactar con los novios.</p>
    </div>
</body>
</html>
