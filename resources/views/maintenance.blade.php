@extends('layouts.app')

@section('content')
    <div class="hero-section">
        <div class="hero-left">
            <img src="{{ asset('images/homebackground.jpg') }}" class="hero-image" alt="Mercè & Hermes">
            <div class="hero-left-content">
                <h1 class="hero-title">Web en construcción</h1>
                <p class="hero-subtitle">¡Nos vemos en muy poquito!</p>
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
            </div>
        </div>

    @push('scripts')
        <script>
            const weddingDate = new Date('2025-03-31T17:00:00');

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
            setInterval(updateCountdown, 1000);
            updateCountdown();
        </script>
    @endpush
@endsection
