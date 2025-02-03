@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="container py-5">
            <!-- Vista Toggle -->
            <div class="d-flex justify-content-end mb-4">
                <div class="view-toggle">
                    <button type="button" id="gridView" class="active">Grid</button>
                    <button type="button" id="listView">Lista</button>
                </div>
            </div>

            <!-- Grid/List Container -->
            <div id="giftsContainer" class="gift-grid">
                @foreach ($gifts as $gift)
                    <!-- Gift Card Component -->
                    <div class="gift-card">
                        <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="w-full h-64 object-cover">
                        <div class="gift-card-content">
                            <div class="product-info">
                                <h3 class="text-[#a79f7d]">{{ $gift->name }}</h3>
                                <p class="text-2xl text-[#6a735b] mb-4">{{ number_format($gift->price, 2) }}€</p>
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
                                        RESERVAR REGALO
                                    </button>
                                @endif

                                <div class="text-sm text-[#a79f7d] mt-3 text-center">
                                    Solicitados: {{ $gift->stock }} • Disponibles: {{ $availableStock }}
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
    </div>

    <!-- Modal de Reserva -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservar Regalo</h5>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('reserveModal'));
                let currentGiftId = null;

                // Grid/List View Toggle
                const container = document.getElementById('giftsContainer');
                const gridBtn = document.getElementById('gridView');
                const listBtn = document.getElementById('listView');

                gridBtn.addEventListener('click', function() {
                    container.className = 'gift-grid';
                    gridBtn.classList.add('active');
                    listBtn.classList.remove('active');
                });

                listBtn.addEventListener('click', function() {
                    container.className = 'gift-list';
                    listBtn.classList.add('active');
                    gridBtn.classList.remove('active');
                });

                // IBAN Copy
                window.copyIban = function() {
                    const iban = document.getElementById('ibanText').textContent;
                    navigator.clipboard.writeText(iban).then(() => {
                        const button = document.getElementById('copyButton');
                        button.textContent = '¡Copiado!';
                        setTimeout(() => {
                            button.textContent = 'Copiar IBAN';
                        }, 2000);
                    });
                };

                // Gift Reservation
                document.querySelectorAll('.reserve-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        currentGiftId = this.dataset.giftId;
                        modal.show();
                    });
                });

                // Confirmation
                document.getElementById('confirmReserve').addEventListener('click', async function() {
                    const form = document.getElementById('reserveForm');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(`/gifts/${currentGiftId}/reserve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(Object.fromEntries(formData))
                        });

                        const data = await response.json();

                        if (response.ok) {
                            modal.hide();
                            alert(
                                `Regalo reservado correctamente. Te hemos enviado un email con las instrucciones y tu código: ${data.unique_code}`
                            );
                            location.reload();
                        } else {
                            alert(data.error || 'Error al procesar la reserva');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error de conexión');
                    }
                });
            });
        </script>
    @endpush
