@extends('layouts.app')

@section('content')
    <div class="event-content">
        <img class="event-image" src="{{ asset('images/ceremony.jpg') }}" alt="Ceremonia" id="eventImage">
        <div>
            <div class="event-info">
                <h2 class="subtitle" id="eventTitle">Ceremonia</h2>
                <p id="eventDescription" class="event-description">{{ $ceremony->description }}</p>
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
                            <span class="time">17:00 PM</span>
                        </div>
                    </div>
                </div>

                <div class="event-marker" data-event="reception">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Banquete</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">18:30 PM</span>
                        </div>
                    </div>
                </div>

                <div class="event-marker" data-event="party">
                    <div class="diamond"></div>
                    <div class="event-label">
                        <h3>Fiesta</h3>
                        <div class="event-time">
                            <span class="date">14 de Junio</span>
                            <span class="time">09:19 PM</span>
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
                        description: '{{ $ceremony->description }}',
                        location: '{{ $ceremony->address }}',
                        time: '17:00',
                        maps_url: '{{ $ceremony->maps_url }}',
                        image: 'ceremony.jpg'
                    },
                    reception: {
                        title: 'Banquete',
                        description: '{{ $reception->description }}',
                        location: '{{ $reception->address }}',
                        time: '18:30',
                        maps_url: '{{ $reception->maps_url }}',
                        image: 'reception.jpg'
                    },
                    party: {
                        title: 'Fiesta',
                        description: '{{ $party->description }}',
                        location: '{{ $party->address }}',
                        time: '09:19',
                        maps_url: '{{ $party->maps_url }}',
                        image: 'party.jpg'
                    }
                };

                document.querySelectorAll('.event-marker').forEach(marker => {
                    marker.addEventListener('click', function() {
                        document.querySelectorAll('.event-marker').forEach(m => m.classList.remove('active'));
                        this.classList.add('active');

                        const event = eventData[this.dataset.event];
                        document.getElementById('eventTitle').textContent = event.title;
                        document.getElementById('eventDescription').textContent = event.description;
                        document.getElementById('eventLocation').textContent = event.location;
                        document.getElementById('eventTime').textContent = event.time + ' PM';
                        document.getElementById('eventImage').src = `{{ asset('images/') }}/${event.image}`;
                        document.getElementById('mapButton').onclick = () => window.open(event.maps_url, '_blank');
                    });
                });
            </script>
        @endpush
    @endsection
