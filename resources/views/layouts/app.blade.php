<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Boda Mercè y Hermes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sage: #BCB88A;
        }
        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: var(--sage);">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">M&H</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gifts.index') }}">Regalos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('info') }}">Información</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
