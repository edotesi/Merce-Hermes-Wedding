@extends('layouts.app')

@section('content')
    <div class="hero-section">
        <div class="hero-left">
            <img src="{{ asset('images/home_background.jpg') }}" class="hero-image" alt="Mercè & Hermes">
            <div class="hero-left-content">
                <h1 class="hero-title">Mercè & Hermes</h1>
                <p class="hero-subtitle">¡Nos casamos!</p>
            </div>
        </div>

        <div class="hero-right">
            <div class="location-content">
                <h2>La Farinera Sant Lluis</h2>
                <h3>14 junio 2025</h3>
                <h3>15:30</h3>

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

    <!-- En el modal -->
    <div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selecciona tu calendario</h5>
                </div>
                <div class="modal-body">
                    <button class="button" id="googleCalendarBtn">
                        GOOGLE CALENDAR
                    </button>
                    <button class="button" id="appleCalendarBtn" style="display: none;">
                        CALENDARIO DE APPLE
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
                const weddingDate = new Date('2025-06-14T15:30:00');
                let globalGoogleCalendarUrl;

                // Detectar Apple devices (iOS y macOS)
                const isAppleDevice = /iPad|iPhone|iPod|Macintosh|MacIntel/.test(navigator.userAgent) ||
                    (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

                // Mostrar/ocultar botón de Apple Calendar según el dispositivo
                const appleCalendarBtn = document.getElementById('appleCalendarBtn');
                if (appleCalendarBtn && isAppleDevice) {
                    appleCalendarBtn.style.display = 'block';
                }

                function generateGoogleCalendarUrl() {
                    const googleUrl = new URL('https://calendar.google.com/calendar/render');
                    googleUrl.searchParams.append('action', 'TEMPLATE');
                    googleUrl.searchParams.append('text', 'Boda Mercè & Hermes');
                    googleUrl.searchParams.append('details', 'Celebración de la boda de Mercè y Hermes');
                    googleUrl.searchParams.append('location', 'Plaça Mossèn Cinto Verdaguer, 1 Castelló d’Empúries');
                    googleUrl.searchParams.append('dates', '20250614T133000Z/20250614T230000Z');

                    globalGoogleCalendarUrl = googleUrl.toString();
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

                // Handlers de calendario
                document.getElementById('appleCalendarBtn')?.addEventListener('click', function() {
                    const webcalUrl = 'webcal://' + window.location.host + '/calendar.ics';
                    window.location.href = webcalUrl;

                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('calendarModal')).hide();
                    }, 500);
                });

                document.getElementById('googleCalendarBtn')?.addEventListener('click', function() {
                    window.open(globalGoogleCalendarUrl, '_blank');
                });

                window.downloadICS = function() {
                    window.location.href = '/calendar.ics';
                    bootstrap.Modal.getInstance(document.getElementById('calendarModal')).hide();
                };

                // Handler para añadir al calendario
                const addToCalendarBtn = document.getElementById('addToCalendar');
                if (addToCalendarBtn) {
                    addToCalendarBtn.addEventListener('click', async function() {
                        generateGoogleCalendarUrl();

                        if (navigator.share) {
                            try {
                                const response = await fetch('/calendar.ics');
                                const blob = await response.blob();
                                const file = new File([blob], 'boda-merce-hermes.ics', {
                                    type: 'text/calendar'
                                });

                                await navigator.share({
                                    files: [file],
                                    title: 'Añadir al Calendario - Boda Mercè & Hermes',
                                    text: '¡Guarda la fecha de nuestra boda!'
                                });
                            } catch {
                                const calendarModal = new bootstrap.Modal(document.getElementById(
                                    'calendarModal'));
                                calendarModal.show();
                            }
                        } else {
                            const calendarModal = new bootstrap.Modal(document.getElementById(
                                'calendarModal'));
                            calendarModal.show();
                        }
                    });
                }

                // Inicializaciones
                if (document.getElementById('days')) {
                    setInterval(updateCountdown, 1000);
                    updateCountdown();
                }
            });
        </script>
    @endpush
@endsection
