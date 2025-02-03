@extends('layouts.app')

@section('content')
    <div class="event-content">
        <img class="event-image" src="{{ asset('images/chronogram_ceremony.jpg') }}" alt="Ceremonia" id="eventImage">
        <div>
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
    </div>
    <div class="event-display">
        <div class="timeline">
            <div class="timeline-events">
                <div class="event-marker active" data-event="ceremony">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Ceremonia</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">15:30 PM</span>
                        </div>
                    </div>
                </div>
                <div class="event-marker" data-event="reception">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Bienvenida</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">17:30 PM</span>
                        </div>
                    </div>
                </div>

                <div class="event-marker" data-event="appetizer">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Aperitivo</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">18:00 PM</span>
                        </div>
                    </div>
                </div>

                <div class="event-marker" data-event="feast">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Banquete</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">20:00 PM</span>
                        </div>
                    </div>
                </div>

                <div class="event-marker" data-event="party">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Fiesta</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">23:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const eventData = {
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
                },
            };

            document.querySelectorAll('.event-marker').forEach(marker => {
                marker.addEventListener('click', function() {
                    document.querySelectorAll('.event-marker').forEach(m => m.classList.remove('active'));
                    this.classList.add('active');

                    const event = eventData[this.dataset.event];
                    document.getElementById('eventTitle').textContent = event.title;
                    document.getElementById('eventLocation').textContent = event.location;
                    document.getElementById('eventTime').textContent = event.time + ' PM';
                    document.getElementById('eventImage').src = `{{ asset('images/') }}/${event.image}`;
                    document.getElementById('mapButton').onclick = () => window.open(event.maps_url, '_blank');
                });
            });
        </script>
    @endpush
@endsection
