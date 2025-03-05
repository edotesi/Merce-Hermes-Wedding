@extends('layouts.app')

@section('content')
<div class="event-wrapper">
    <!-- Contenido principal del evento -->
    <div class="container">
        <div class="event-content">
            <div class="event-image-container">
                <img class="event-image" src="{{ asset('images/chronogram_ceremony.jpg') }}" alt="Ceremonia" id="eventImage">
                <span class="event-time-badge" id="eventTime">15:30</span>
            </div>
            <div class="event-info">
                <h2 class="event-title" id="eventTitle">Ceremonia</h2>
                <div class="event-location" id="eventLocation">{{ $ceremony->address }}</div>
                <a class="button" id="mapButton" href="{{ $ceremony->maps_url }}" target="_blank">
                    VER EN MAPA
                </a>
            </div>
        </div>

        <!-- Navegación de eventos y puntos de paginación - solo visible en móvil -->
        <div class="event-navigation">
            <button id="prevEvent" class="event-nav-button">
                &laquo;
            </button>
            <div class="mobile-pagination">
                <div class="pagination-dots">
                    <div class="pagination-dot active" data-event="ceremony"></div>
                    <div class="pagination-dot" data-event="reception"></div>
                    <div class="pagination-dot" data-event="appetizer"></div>
                    <div class="pagination-dot" data-event="feast"></div>
                    <div class="pagination-dot" data-event="party"></div>
                </div>
            </div>
            <button id="nextEvent" class="event-nav-button">
                &raquo;
            </button>
        </div>

        <!-- Timeline mejorada -->
        <div class="timeline-container">
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
</div>

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
