document.addEventListener('DOMContentLoaded', function() {
    // Menu móvil - abrir/cerrar con botón
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navbarNav = document.querySelector('.navbar-nav');

    mobileMenuToggle?.addEventListener('click', function(e) {
        e.stopPropagation(); // Evitar propagación del clic
        navbarNav.classList.toggle('active');
    });

    // Cerrar menú al hacer clic en un enlace
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            navbarNav.classList.remove('active');
        });
    });

    // Cerrar menú al hacer clic en cualquier parte fuera de él
    document.addEventListener('click', function(e) {
        // Si el menú está abierto y el clic no fue dentro del menú ni en el botón
        if (navbarNav.classList.contains('active') &&
            !navbarNav.contains(e.target) &&
            e.target !== mobileMenuToggle) {
            navbarNav.classList.remove('active');
        }
    });

    // Detectar si estamos en la página de inicio
    const isHomePage = window.location.pathname === '/' ||
                       window.location.pathname === '/index.php' ||
                       window.location.pathname.endsWith('/home');
    if (isHomePage) {
        document.body.classList.add('is-home');
    }

    // Detectar si estamos en la página de regalos
    const isGiftsPage = window.location.pathname.includes('/gifts');
    if (isGiftsPage) {
        document.body.classList.add('gifts-page');
    }
});
