@extends('layouts.app')

@section('content')
<div class="event-wrapper">
    <div class="event-content">
        <div class="event-info">
            <p class="subtitle">
                Estamos muy emocionados de compartir este día tan especial con vosotros.
            </p>
            <p class="subtitle">
                Para que todos disfrutemos de la celebración con estilo, hemos preparado un código de vestimenta que refleje
                el espíritu de nuestra boda:
            </p>
            <div class="dress-code-info">
                <div class="dress-code-item">
                    <div class="dress-code-text">
                        <h3>Damas:</h3>
                        <p>Se invita a todas las damas a vestir de largo o en elegante estilo cóctel,
                        evitando el color blanco y tonos similares.</p>
                    </div>
                    <div class="dress-code-image">
                        <img src="{{ asset('images/dress-code-women.png') }}" alt="Dress Code Damas">
                    </div>
                </div>
                <div class="dress-code-item">
                    <div class="dress-code-text">
                        <h3>Caballeros:</h3>
                        <p>Se invita a todos los caballeros a vestir con un traje formal o de etiqueta y,
                        en la medida de lo posible, con pajarita.</p>
                    </div>
                    <div class="dress-code-image">
                        <img src="{{ asset('images/dress-code-men.png') }}" alt="Dress Code Caballeros">
                    </div>
                </div>
            </div>
            <p class="footer-note">Esperamos veros listos para disfrutar de una noche especial.</p>
        </div>
    </div>
</div>
@endsection
