document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('reserveModal'));
    let currentGiftId = null;

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
    document.getElementById('confirmReserve')?.addEventListener('click', async function() {
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
