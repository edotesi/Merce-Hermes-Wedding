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

    @push('scripts')
        <script>
            const weddingDate = new Date('2025-06-14T17:00:00');

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
                const event = {
                    begin: new Date('2025-06-14T17:00:00'),
                    end: new Date('2025-06-14T23:00:00'),
                    title: 'Boda Mercè & Hermes',
                    description: 'Celebración de la boda de Mercè y Hermes',
                    location: 'Barcelona, Spain',
                    url: window.location.href
                };

                const icsContent =
                    `BEGIN:VCALENDAR
                        VERSION:2.0
                        BEGIN:VEVENT
                        DTSTART:${event.begin.toISOString().replace(/[-:]/g, '').split('.')[0]}Z
                        DTEND:${event.end.toISOString().replace(/[-:]/g, '').split('.')[0]}Z
                        SUMMARY:${event.title}
                        DESCRIPTION:${event.description}
                        LOCATION:${event.location}
                        URL:${event.url}
                        END:VEVENT
                        END:VCALENDAR`;

                const blob = new Blob([icsContent], {
                    type: 'text/calendar;charset=utf-8'
                });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'boda-merce-hermes.ics';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            document.getElementById('addToCalendar').addEventListener('click', generateICS);
            setInterval(updateCountdown, 1000);
            updateCountdown();
        </script>
    @endpush
@endsection
