@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Vista Toggle -->
    <div class="flex justify-end mb-6">
        <div class="inline-flex rounded-lg border border-[#6a735b]">
            <button id="gridView" class="px-4 py-2 rounded-l-lg" aria-label="Vista Grid">
                <i class="fas fa-grid-2"></i>
            </button>
            <button id="listView" class="px-4 py-2 rounded-r-lg" aria-label="Vista Lista">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Grid/List Container -->
    <div id="giftsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gifts as $gift)
            <div class="gift-card bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="relative">
                    <img src="{{ $gift->image_url }}" alt="{{ $gift->name }}" class="w-full h-64 object-cover">
                    @if($gift->status !== 'available')
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <span class="text-white text-lg font-medium uppercase">
                                {{ $gift->status === 'reserved' ? 'Reservado' : 'Comprado' }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-medium mb-2">{{ $gift->name }}</h3>
                    <p class="text-2xl text-[#6a735b] mb-4">{{ number_format($gift->price, 2) }}€</p>

                    @if($gift->status === 'available')
                        <button
                            class="w-full bg-[#6a735b] text-white py-2 px-4 rounded hover:opacity-90 reserve-btn"
                            data-gift-id="{{ $gift->id }}"
                        >
                            Reservar Regalo
                        </button>
                    @endif

                    <div class="text-sm text-[#a79f7d] mt-3 text-center">
                        Solicitados: {{ $gift->stock }} • Disponibles: {{ $gift->stock - $gift->reserved_stock }}
                    </div>
                </div>
            </div>
        @endforeach

        <!-- IBAN Card -->
        <div class="col-span-full bg-[#6a735b] text-white p-8 rounded-lg mt-6">
            <div class="text-center">
                <h3 class="text-2xl font-light mb-4">Información Bancaria</h3>
                <p class="font-mono text-xl mb-4" id="ibanText">ES91 2100 0418 4502 0005 1332</p>
                <button
                    onclick="copyIban()"
                    class="bg-white text-[#6a735b] px-6 py-2 rounded hover:bg-opacity-90"
                    id="copyButton"
                >
                    Copiar IBAN
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Reserva -->
    <div class="modal fade" id="reserveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservar Regalo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmReserve">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentGiftId = null;
    const modal = new bootstrap.Modal(document.getElementById('reserveModal'));

    // Grid/List View Toggle
    const container = document.getElementById('giftsContainer');
    document.getElementById('gridView').addEventListener('click', function() {
        container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        this.classList.add('bg-[#6a735b]', 'text-white');
        document.getElementById('listView').classList.remove('bg-[#6a735b]', 'text-white');
    });

    document.getElementById('listView').addEventListener('click', function() {
        container.className = 'flex flex-col gap-6';
        this.classList.add('bg-[#6a735b]', 'text-white');
        document.getElementById('gridView').classList.remove('bg-[#6a735b]', 'text-white');
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

    // Reserva
    document.querySelectorAll('.reserve-btn').forEach(button => {
        button.addEventListener('click', () => {
            currentGiftId = button.dataset.giftId;
            modal.show();
        });
    });

    document.getElementById('confirmReserve').addEventListener('click', async () => {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();

            if (response.ok) {
                modal.hide();
                alert(`Regalo reservado correctamente. Tu código es: ${data.unique_code}`);
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
