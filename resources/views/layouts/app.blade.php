<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Boda Mercè y Hermes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <button class="mobile-menu-toggle"></button>
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">M&H</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('schedule') }}">Programa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('gifts.index') }}">Regalos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('info') }}">Información</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.navbar-nav').classList.toggle('active');
        });
    </script>
</body>
</html>
