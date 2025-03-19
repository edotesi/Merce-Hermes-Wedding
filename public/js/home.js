document.addEventListener('DOMContentLoaded', function() {
    const weddingDate = new Date('2025-06-14T15:30:00');

    // Detectar Apple devices (iOS y macOS)
    const isAppleDevice = /iPad|iPhone|iPod|Macintosh|MacIntel/.test(navigator.userAgent) ||
        (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

    // Mostrar/ocultar botón de Apple Calendar según el dispositivo
    const appleCalendarBtn = document.getElementById('appleCalendarBtn');
    if (appleCalendarBtn && isAppleDevice) {
        appleCalendarBtn.style.display = 'block';
    }

    function updateCountdown() {
        const now = new Date();
        const diff = weddingDate - now;

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    // Función para generar la URL de Google Calendar
    function getGoogleCalendarUrl() {
        const googleUrl = new URL('https://calendar.google.com/calendar/render');
        googleUrl.searchParams.append('action', 'TEMPLATE');
        googleUrl.searchParams.append('text', 'Boda Mercè & Hermes');
        googleUrl.searchParams.append('details', 'Celebración de la boda de Mercè y Hermes');
        googleUrl.searchParams.append('location', 'Plaça Mossèn Cinto Verdaguer, 1 Castelló d`Empúries');
        googleUrl.searchParams.append('dates', '20250614T133000Z/20250614T230000Z');

        return googleUrl.toString();
    }

    // Función para generar la URL de Apple Calendar (iCal)
    function getAppleCalendarUrl() {
        return 'webcal://' + window.location.host + '/calendar.ics';
    }

    // Handlers de calendario
    document.getElementById('appleCalendarBtn')?.addEventListener('click', function() {
        window.location.href = getAppleCalendarUrl();

        setTimeout(() => {
            const modalElement = document.getElementById('calendarModal');
            if (modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();
            }
        }, 500);
    });

    document.getElementById('googleCalendarBtn')?.addEventListener('click', function() {
        window.open(getGoogleCalendarUrl(), '_blank');

        const modalElement = document.getElementById('calendarModal');
        if (modalElement) {
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) modalInstance.hide();
        }
    });

    // Función para descargar el ICS para Outlook/otros
    window.downloadICS = function() {
        window.location.href = '/calendar.ics';

        const modalElement = document.getElementById('calendarModal');
        if (modalElement) {
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) modalInstance.hide();
        }
    };

    // Handler para añadir al calendario
    const addToCalendarBtn = document.getElementById('addToCalendar');
    if (addToCalendarBtn) {
        addToCalendarBtn.addEventListener('click', function() {
            // Siempre mostrar el modal
            const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
            calendarModal.show();
        });
    }

    // Inicializaciones del contador
    if (document.getElementById('days')) {
        setInterval(updateCountdown, 1000);
        updateCountdown();
    }
});