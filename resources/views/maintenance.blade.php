<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Boda Mercè y Hermes</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
        <div class="hero-center">
            <img src="{{ asset('images/background_maintenance.jpg') }}" class="hero-image" alt="Mercè & Hermes">
            <div class="hero-center-content">
                <img src="{{ asset('images/logo.png') }}" alt="Mercè & Hermes">
                <h1>Web en construcción</h1>
                <p>¡En muy poco podréis disfrutar de la web!</p>
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

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
        <script>
            document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
                document.querySelector('.navbar-nav').classList.toggle('active');
            });
        </script>
    @endpush
</body>
</html>
