@extends('layouts.app')

@section('content')
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

            document.addEventListener('DOMContentLoaded', function() {
                const events = document.querySelectorAll('.event-marker');
                const content = document.querySelector('.event-content');
                let currentEventIndex = 0;
                let touchStartY = 0;
                let isTransitioning = false;

                function updateEvent(index) {
                    if(index < 0 || index >= events.length) return;
                    if (isTransitioning) return;
                    isTransitioning = true;

                    content.style.opacity = '0';

                    setTimeout(() => {
                        // Update active state
                        events.forEach(event => event.classList.remove('active'));
                        events[index].classList.add('active');

                        // Update content
                        const event = eventData[events[index].dataset.event];
                        const img = document.getElementById('eventImage');

                        const tempImg = new Image();
                        tempImg.onload = function() {
                            img.src = this.src;
                            document.getElementById('eventTitle').textContent = event.title;
                            document.getElementById('eventLocation').textContent = event.location;
                            document.getElementById('eventTime').textContent = event.time + ' PM';
                            document.getElementById('mapButton').onclick = () => window.open(event.maps_url,
                                '_blank');

                            content.style.opacity = '1';
                            currentEventIndex = index;

                            setTimeout(() => {
                                isTransitioning = false;
                            }, 300);
                        };
                        tempImg.src = `{{ asset('images/') }}/${event.image}`;
                    }, 300);
                }

                window.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    if (e.deltaY > 0) {
                        updateEvent(currentEventIndex + 1);
                    } else {
                        updateEvent(currentEventIndex - 1);
                    }
                }, {
                    passive: false
                });

                window.addEventListener('touchstart', (e) => {
                    touchStartY = e.touches[0].clientY;
                }, {
                    passive: true
                });

                window.addEventListener('touchmove', (e) => {
                    e.preventDefault();
                    const touchEndY = e.touches[0].clientY;
                    const diff = touchStartY - touchEndY;

                    if (Math.abs(diff) > 50) {
                        if (diff > 0) {
                            updateEvent(currentEventIndex + 1);
                        } else {
                            updateEvent(currentEventIndex - 1);
                        }
                        touchStartY = touchEndY;
                    }
                }, {
                    passive: false
                });

                events.forEach((event, index) => {
                    event.addEventListener('click', () => {
                        updateEvent(index);
                    });
                });
            });
        </script>
    @endpush
@endsection
