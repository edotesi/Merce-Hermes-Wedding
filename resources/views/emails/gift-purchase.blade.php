<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { text-align: center; font-size: 0.9em; color: #666; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Regalo</h1>
            <h2>Boda Mercè & Hermes</h2>
        </div>

        <div class="content">
            <p>Hola {{ $purchaserName ?: 'Invitado/a' }},</p>

            <p>Gracias por seleccionar un regalo para nuestra boda. Los detalles de tu selección son:</p>

            <ul>
                <li>Regalo: {{ $giftName }}</li>
                @if($store)
                <li>Tienda: {{ $store }}</li>
                @endif
                @if($orderNumber)
                <li>Número de pedido: {{ $orderNumber }}</li>
                @endif
            </ul>

            <p>Tu código único es: <div class="code">{{ $uniqueCode }}</div></p>
            <p>Conserva este código por si necesitas modificar tu selección más adelante.</p>
        </div>

        <div class="footer">
            <p>Con cariño,<br>Mercè & Hermes</p>
        </div>
    </div>
</body>
</html>
