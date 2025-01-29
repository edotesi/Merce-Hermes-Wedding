@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="hero-left">
        <img src="{{ asset('images/homebackground.jpg') }}" class="hero-image" alt="Mercè & Hermes">
        <div class="hero-left-content">
            <h1 class="hero-title">Mercè & Hermes</h1>
            <p class="hero-subtitle">¡Nos casamos!</p>
        </div>
    </div>

    <div class="hero-right">
        <div class="location-content">
            <h2>Barcelona, Spain</h2>
            <h3>14 de Junio, 2025</h3>

            <div class="countdown">
                <div class="countdown-item">
                    <span class="countdown-number" id="days">00</span>
                    <span class="countdown-label">DÍAS</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="hours">00</span>
                    <span class="countdown-label">HORAS</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="minutes">00</span>
                    <span class="countdown-label">MINUTOS</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="seconds">00</span>
                    <span class="countdown-label">SEGUNDOS</span>
                </div>
            </div>

            <button class="button" id="addToCalendar">
                AÑADIR AL CALENDARIO
            </button>
        </div>
    </div>
</div>

<!-- Modal Calendario -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecciona tu calendario</h5>
            </div>
            <div class="modal-body">
                <button class="button" onclick="window.open(globalGoogleCalendarUrl.toString(), '_blank')">
                    GOOGLE CALENDAR
                </button>
                <button class="button" onclick="downloadICS()">
                    DESCARGAR PARA OUTLOOK/OTRO
                </button>
                <button class="button" data-bs-dismiss="modal">
                    CERRAR
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weddingDate = new Date('2025-06-14T17:00:00');
    let globalGoogleCalendarUrl;
    let globalIcsContent;
    const eventData = {
        begin: new Date('2025-06-14T17:00:00'),
        end: new Date('2025-06-14T23:00:00'),
        title: 'Boda Mercè & Hermes',
        description: 'Celebración de la boda de Mercè y Hermes',
        location: 'Barcelona, Spain',
        url: window.location.href
    };

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

    function generateICS() {
        // Crear el contenido ICS
        globalIcsContent = `BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
DTSTART:${eventData.begin.toISOString().replace(/[-:]/g, '').split('.')[0]}Z
DTEND:${eventData.end.toISOString().replace(/[-:]/g, '').split('.')[0]}Z
SUMMARY:${eventData.title}
DESCRIPTION:${eventData.description}
LOCATION:${eventData.location}
URL:${eventData.url}
END:VEVENT
END:VCALENDAR`;

        // URL para Calendario de Google
        globalGoogleCalendarUrl = new URL('https://calendar.google.com/calendar/render');
        globalGoogleCalendarUrl.searchParams.append('action', 'TEMPLATE');
        globalGoogleCalendarUrl.searchParams.append('text', eventData.title);
        globalGoogleCalendarUrl.searchParams.append('details', eventData.description);
        globalGoogleCalendarUrl.searchParams.append('location', eventData.location);
        globalGoogleCalendarUrl.searchParams.append('dates',
            eventData.begin.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z' + '/' +
            eventData.end.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z'
        );

        if (navigator.share) {
            const blob = new Blob([globalIcsContent], { type: 'text/calendar;charset=utf-8' });
            const file = new File([blob], 'boda-merce-hermes.ics', { type: 'text/calendar' });

            navigator.share({
                files: [file],
                title: 'Añadir al Calendario - Boda Mercè & Hermes',
                text: '¡Guarda la fecha de nuestra boda!'
            }).catch(() => {
                const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
                calendarModal.show();
            });
        } else {
            const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
            calendarModal.show();
        }
    }

    window.downloadICS = function() {
        const blob = new Blob([globalIcsContent], { type: 'text/calendar;charset=utf-8' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'boda-merce-hermes.ics';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        bootstrap.Modal.getInstance(document.getElementById('calendarModal')).hide();
    };

    // Inicializaciones
    document.getElementById('addToCalendar').addEventListener('click', generateICS);
    setInterval(updateCountdown, 1000);
    updateCountdown();
});
</script>
@endpush
@endsection
