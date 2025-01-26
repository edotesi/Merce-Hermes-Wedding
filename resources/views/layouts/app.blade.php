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
    <nav class="navbar">
        <button class="mobile-menu-toggle"></button>
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    @if (Route::has('home'))
                        <a class="nav-link" href="{{ routeWithPreview('home') }}">M&H</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('schedule'))
                        <a class="nav-link" href="{{ routeWithPreview('schedule') }}">Programa</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('gifts.index'))
                        <a class="nav-link" href="{{ routeWithPreview('gifts.index') }}">Regalos</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('info'))
                        <a class="nav-link" href="{{ routeWithPreview('info') }}">Información</a>
                    @endif
                </li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.navbar-nav').classList.toggle('active');
        });
    </script>
</body>

</html>
