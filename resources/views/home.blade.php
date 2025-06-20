@extends('layouts.app')

@section('content')
    <div class="hero-section">
        <img src="{{ asset('images/home_background.jpg') }}" class="hero-image" alt="Mercè & Hermes" id="heroImage">
        <div class="hero-overlay"></div>

        <div class="hero-left-content">
            <h1 class="hero-title">Mercè & Hermes</h1>
        </div>

        <div class="hero-right-content">
            <div class="elegant-container">
                <!-- Elementos decorativos elegantes -->
                <div class="elegant-border"></div>
                <div class="elegant-border-inner"></div>

                <div class="location-content">
                    <h2>La Farinera Sant Lluis</h2>
                    <h3>14 junio 2025</h3>

                    <div class="elegant-divider"></div>

                    {{-- <div class="countdown">
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
                    </button> --}}
                    <h2 id="marriedTimeEasterEgg" style="cursor: pointer;">¡Viva los novios!</h2>
                </div>
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

    <!-- Modal del Easter Egg -->
    <div class="modal fade" id="marriedTimeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🎉 Mercè y Hermes llevan casados 🎉</h5>
                </div>
                <div class="modal-body">
                    <div class="countdown">
                        <div class="countdown-item">
                            <span class="countdown-number" id="daysUp">00</span>
                            <span class="countdown-label">DÍAS</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="hoursUp">00</span>
                            <span class="countdown-label">HORAS</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="minutesUp">00</span>
                            <span class="countdown-label">MINUTOS</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="secondsUp">00</span>
                            <span class="countdown-label">SEGUNDOS</span>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 1rem;">
                        <button class="button" data-bs-dismiss="modal">
                            CERRAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts directos en el HTML para garantizar orden -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    @push('scripts')
        <script src="{{ asset('js/home.js') }}"></script>
    @endpush
@endsection
