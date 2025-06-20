document.addEventListener('DOMContentLoaded', function() {
    const weddingDate = new Date('2025-06-14T15:30:00');
    const marriedDate = new Date('2025-06-14T17:00:00'); // Fecha desde cuando empezar a contar como casados
    let easterEggActivated = false;
    let countUpInterval = null;

    // Detectar Apple devices (iOS y macOS)
    const isAppleDevice = /iPad|iPhone|iPod|Macintosh|MacIntel/.test(navigator.userAgent) ||
        (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

    // Mostrar/ocultar botón de Apple Calendar según el dispositivo
    const appleCalendarBtn = document.getElementById('appleCalendarBtn');
    if (appleCalendarBtn && isAppleDevice) {
        appleCalendarBtn.style.display = 'block';
    }

    // Verificar si el easter egg ya fue activado en esta sesión
    if (sessionStorage.getItem('easterEggActivated') === 'true') {
        activateEasterEggBackground();
    }

    function updateCountdown() {
        const now = new Date();
        const diff = weddingDate - now;

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (document.getElementById('days')) {
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }
    }

    function updateCountUp() {
        const now = new Date();
        const diff = now - marriedDate; // Tiempo transcurrido desde la boda

        // Si aún no es la fecha de la boda, mostrar ceros
        if (diff < 0) {
            if (document.getElementById('daysUp')) {
                document.getElementById('daysUp').textContent = '00';
                document.getElementById('hoursUp').textContent = '00';
                document.getElementById('minutesUp').textContent = '00';
                document.getElementById('secondsUp').textContent = '00';
            }
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (document.getElementById('daysUp')) {
            document.getElementById('daysUp').textContent = String(days).padStart(2, '0');
            document.getElementById('hoursUp').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutesUp').textContent = String(minutes).padStart(2, '0');
            document.getElementById('secondsUp').textContent = String(seconds).padStart(2, '0');
        }
    }

    // Función para activar el fondo del easter egg
    function activateEasterEggBackground() {
        const heroImage = document.getElementById('heroImage');
        if (heroImage) {
            heroImage.src = heroImage.src.replace('home_background.jpg', 'marrieds.jpg');
        }
        easterEggActivated = true;
        sessionStorage.setItem('easterEggActivated', 'true');
    }

    // Función para crear fuegos artificiales
    function launchFireworks() {
        // Función para lanzar confetti desde diferentes posiciones
        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        // Primer estallido
        confetti({
            angle: randomInRange(55, 125),
            spread: randomInRange(50, 70),
            particleCount: randomInRange(50, 100),
            origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
        });

        // Segundo estallido
        confetti({
            angle: randomInRange(55, 125),
            spread: randomInRange(50, 70),
            particleCount: randomInRange(50, 100),
            origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
        });

        // Tercer estallido central
        setTimeout(() => {
            confetti({
                angle: 90,
                spread: 45,
                particleCount: 100,
                origin: { x: 0.5, y: 0.6 }
            });
        }, 250);

        // Lluvia de confetti dorado
        setTimeout(() => {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#d8d7b6', '#bfb58d', '#c5c49e', '#a79f7d']
            });
        }, 500);

        // Estallido final
        setTimeout(() => {
            confetti({
                angle: randomInRange(55, 125),
                spread: randomInRange(50, 70),
                particleCount: randomInRange(80, 120),
                origin: { x: 0.5, y: 0.4 }
            });
        }, 750);
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

    // Handler para añadir al calendario (si existe el botón)
    const addToCalendarBtn = document.getElementById('addToCalendar');
    if (addToCalendarBtn) {
        addToCalendarBtn.addEventListener('click', function() {
            const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
            calendarModal.show();
        });
    }

    // 🎉 EASTER EGG: Handler para "¡Viva los novios!"
    const marriedTimeEasterEggBtn = document.getElementById('marriedTimeEasterEgg');
    if (marriedTimeEasterEggBtn) {
        marriedTimeEasterEggBtn.addEventListener('click', function() {
            // Activar el easter egg solo si no está ya activado
            if (!easterEggActivated) {
                activateEasterEggBackground();
            }

            // Lanzar fuegos artificiales
            launchFireworks();

            // Mostrar el modal con el contador
            const marriedModal = new bootstrap.Modal(document.getElementById('marriedTimeModal'));
            marriedModal.show();

            // Iniciar el contador hacia arriba
            if (countUpInterval) {
                clearInterval(countUpInterval);
            }
            updateCountUp(); // Actualizar inmediatamente
            countUpInterval = setInterval(updateCountUp, 1000);
        });
    }

    // Limpiar intervalos cuando se cierre el modal del easter egg
    const marriedTimeModal = document.getElementById('marriedTimeModal');
    if (marriedTimeModal) {
        marriedTimeModal.addEventListener('hidden.bs.modal', function() {
            if (countUpInterval) {
                clearInterval(countUpInterval);
                countUpInterval = null;
            }
        });
    }

    // Inicializaciones del contador normal (si existe)
    if (document.getElementById('days')) {
        setInterval(updateCountdown, 1000);
        updateCountdown();
    }
});
