@extends('layouts.app')

@section('content')
    <!-- Tu contenido actual -->

    <!-- Define eventData como variable global -->
    <script>
        window.eventData = {
            ceremony: {
                title: 'Ceremonia',
                location: '{{ $ceremony->address }}',
                time: '15:30',
                maps_url: '{{ $ceremony->maps_url }}',
                image: 'chronogram_ceremony.jpg'
            },
            reception: {
                title: 'Bienvenida',
                location: '{{ $reception->address }}',
                time: '17:30',
                maps_url: '{{ $reception->maps_url }}',
                image: 'chronogram_welcome.jpg'
            },
            appetizer: {
                title: 'Aperitivo',
                location: '{{ $appetizer->address }}',
                time: '18:00',
                maps_url: '{{ $appetizer->maps_url }}',
                image: 'chronogram_appetizer.jpg'
            },
            feast: {
                title: 'Banquete',
                location: '{{ $feast->address }}',
                time: '20:00',
                maps_url: '{{ $feast->maps_url }}',
                image: 'chronogram_feast.jpg'
            },
            party: {
                title: 'Fiesta',
                location: '{{ $party->address }}',
                time: '23:00',
                maps_url: '{{ $party->maps_url }}',
                image: 'chronogram_party.jpg'
            }
        };
    </script>

    @push('scripts')
        <script src="{{ asset('js/schedule.js') }}"></script>
    @endpush
@endsection
<div class="event-wrapper">
    <div class="event-content">
        <img class="event-image" src="{{ asset('images/chronogram_ceremony.jpg') }}" alt="Ceremonia" id="eventImage">
        <div class="event-info">
            <h2 class="subtitle" id="eventTitle">Ceremonia</h2>
            <div class="location" id="eventLocation">{{ $ceremony->address }}</div>
            <div class="datetime">
                <span class="date">14 de Junio, 2025</span>
                <span class="time" id="eventTime">17:00 PM</span>
            </div>
            <button class="button" id="mapButton" onclick="window.open('{{ $ceremony->maps_url }}', '_blank')">
                VER EN MAPA
            </button>
        </div>
    </div>

    <div class="event-display">
        <div class="timeline">
            <div class="timeline-events">
                <div class="event-marker active" data-event="ceremony">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Ceremonia</h3>
                    </div>
                </div>
                <div class="event-marker" data-event="reception">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Bienvenida</h3>
                    </div>
                </div>
                <div class="event-marker" data-event="appetizer">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Aperitivo</h3>
                    </div>
                </div>
                <div class="event-marker" data-event="feast">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Banquete</h3>
                    </div>
                </div>
                <div class="event-marker" data-event="party">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Fiesta</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
