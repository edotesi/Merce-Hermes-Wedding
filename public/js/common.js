document.addEventListener('DOMContentLoaded', function() {
    // Menu móvil
    document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
        document.querySelector('.navbar-nav').classList.toggle('active');
    });

    // Otros comportamientos comunes pueden ir aquí
});
