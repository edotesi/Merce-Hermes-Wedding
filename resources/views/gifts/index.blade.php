@extends('layouts.app')

@section('content')
    <!-- Eliminado el div.main-content redundante -->
    <div class="container gifts-container">
        <!-- Grid/List Container -->
        <div id="giftsContainer" class="gift-grid">
            @foreach ($gifts as $gift)
                <!-- Gift Card Component -->
                <div class="gift-card">
                    <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="w-full h-64 object-cover">
                    <div class="gift-card-content">
                        <div class="product-info">
                            <h3 class="distance">{{ $gift->name }}</h3>
                            <p class="distance">{{ number_format($gift->price, 2) }}€</p>
                        </div>

                        <div class="action-container">
                            @php
                                $availableStock = $gift->stock - ($gift->reserved_stock ?? 0);
                                $isFullyReserved = $gift->status === 'reserved' && $availableStock <= 0;
                                $isFullyPurchased = $gift->status === 'purchased';
                            @endphp

                            @if ($isFullyPurchased)
                                <div class="button w-full status-button purchased">
                                    COMPRADO
                                </div>
                            @elseif($isFullyReserved)
                                <div class="button w-full status-button reserved">
                                    RESERVADO
                                </div>
                            @else
                                <button class="button w-full reserve-btn" data-gift-id="{{ $gift->id }}">
                                    RESERVAR
                                </button>
                            @endif

                            <div class="text-sm text-[#a79f7d] mt-3 text-center">
                                Disponibles: {{ $availableStock }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- IBAN Section -->
        <div class="iban-section">
            <h3>Información Bancaria</h3>
            <p id="ibanText">ES36 0186 3001 25 0525942353</p>
            <button onclick="copyIban()" id="copyButton" class="button">
                Copiar IBAN
            </button>
        </div>
    </div>

    <!-- Botón de ayuda flotante (único) -->
    <div class="help-icon" id="giftHelpIcon">
        <i class="fas fa-question-circle"></i>
    </div>

    <!-- Modal de información de funcionamiento del sistema de regalos -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¿Cómo funciona el sistema de reservas?</h5>
                </div>
                <div class="modal-body">
                    <div class="help-step">
                        <div class="step-number">1</div>
                        <p>Reserva un artículo haciendo clic en el botón "RESERVAR".</p>
                    </div>
                    <div class="help-step">
                        <div class="step-number">2</div>
                        <p>Completa el formulario con tu nombre y email para recibir las instrucciones.</p>
                    </div>
                    <div class="help-step">
                        <div class="step-number">3</div>
                        <p>Recibirás un email con un código único y un enlace a la tienda para comprar el artículo.</p>
                    </div>
                    <div class="help-step">
                        <div class="step-number">4</div>
                        <p>Una vez comprado, confirma tu compra usando el código recibido.</p>
                    </div>

                    <div class="help-note">
                        <p>Las reservas son válidas durante <strong>48 horas</strong>. Si la compra no se confirma dentro de este plazo, la reserva se cancelará automáticamente para que otros invitados puedan adquirir el artículo.</p>
                    </div>
                      <div class="help-note">
                        <p>El contador de "Disponibles" se actualiza en tiempo real, mostrando siempre el estado actual de cada artículo.</p>
                    </div>

                    <button type="button" class="button" data-bs-dismiss="modal">
                        ENTENDIDO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reserva -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservar Producto</h5>
                </div>
                <div class="modal-body">
                    <form id="reserveForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre y Apellidos *</label>
                            <input type="text" class="form-control" name="purchaser_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="purchaser_email" required>
                        </div>
                    </form>
                    <button type="button" class="button" data-bs-dismiss="modal">
                        CANCELAR
                    </button>
                    <button type="button" class="button primary" id="confirmReserve">
                        CONFIRMAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/gifts.js') }}"></script>
    @endpush
@endsection