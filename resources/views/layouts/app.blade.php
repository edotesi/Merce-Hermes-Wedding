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

<body class="{{ Request::routeIs('gifts.*') ? 'gifts-page' : '' }}">
    <nav class="navbar">
        <button class="mobile-menu-toggle"></button>
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    @if (Route::has('home'))
                        <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}"
                            href="{{ routeWithPreview('home') }}">M&H</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('story'))
                        <a class="nav-link {{ Request::routeIs('story') ? 'active' : '' }}"
                            href="{{ routeWithPreview('story') }}">Nuestra Historia</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('schedule'))
                        <a class="nav-link {{ Request::routeIs('schedule') ? 'active' : '' }}"
                            href="{{ routeWithPreview('schedule') }}">Programa</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('gifts.index'))
                        <a class="nav-link {{ Request::routeIs('gifts.*') ? 'active' : '' }}"
                            href="{{ routeWithPreview('gifts.index') }}">Lista de bodas</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('dress-code'))
                        <a class="nav-link {{ Request::routeIs('dress-code') ? 'active' : '' }}"
                            href="{{ routeWithPreview('dress-code') }}">Dress Code</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Route::has('accommodations'))
                        <a class="nav-link {{ Request::routeIs('accommodations') ? 'active' : '' }}"
                            href="{{ routeWithPreview('accommodations') }}">Alojamientos</a>
                    @endif
                </li>
            </ul>
        </div>
    </nav>

    <!-- Título móvil (solo visible en móvil) -->
    <div class="mobile-page-title">
        @if (Request::routeIs('schedule'))
            <h1>Programa</h1>
        @elseif (Request::routeIs('gifts.*'))
            <h1>Lista de bodas</h1>
        @elseif (Request::routeIs('dress-code'))
            <h1>Dress Code</h1>
        @elseif (Request::routeIs('accommodations'))
            <h1>Alojamientos</h1>
        @elseif (Request::routeIs('story'))
            <h1>Nuestra Historia</h1>
        @endif
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    @stack('scripts')
</body>

</html>
