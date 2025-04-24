document.addEventListener('DOMContentLoaded', function() {
    // Variables para el carrusel
    const carouselItems = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.carousel-indicators button');
    const prevButton = document.getElementById('prevPhoto');
    const nextButton = document.getElementById('nextPhoto');
    let currentIndex = 0;
    const totalItems = carouselItems.length;

    // Detección de imágenes verticales y aplicación de estilos
    function detectVerticalImages() {
        const images = document.querySelectorAll('.carousel-image');

        images.forEach(img => {
            // Cuando la imagen carga, verificamos su orientación
            img.onload = function() {
                if (this.naturalHeight > this.naturalWidth) {
                    // Es una imagen vertical
                    this.classList.add('vertical');
                } else {
                    this.classList.remove('vertical');
                }
            };

            // Para imágenes ya cargadas
            if (img.complete && img.naturalHeight > 0) {
                if (img.naturalHeight > img.naturalWidth) {
                    img.classList.add('vertical');
                }
            }
        });
    }

    // Función para mostrar una slide específica
    function showSlide(index) {
        // Ocultar todas las slides
        carouselItems.forEach(item => {
            item.classList.remove('active');
        });

        // Desactivar todos los indicadores
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
            indicator.setAttribute('aria-current', 'false');
        });

        // Mostrar la slide e indicador activos
        carouselItems[index].classList.add('active');
        indicators[index].classList.add('active');
        indicators[index].setAttribute('aria-current', 'true');

        // Actualizar el índice actual
        currentIndex = index;

        // Verificar si debe habilitar/deshabilitar botones de navegación
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === totalItems - 1;
    }

    // Event Listeners para los botones
    prevButton.addEventListener('click', function() {
        if (currentIndex > 0) {
            showSlide(currentIndex - 1);
        }
    });

    nextButton.addEventListener('click', function() {
        if (currentIndex < totalItems - 1) {
            showSlide(currentIndex + 1);
        }
    });

    // Event Listeners para los indicadores
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            showSlide(index);
        });
    });

    // Comprobar estado inicial de los botones
    prevButton.disabled = currentIndex === 0;
    nextButton.disabled = currentIndex === totalItems - 1;

    // Iniciar un temporizador para cambiar las imágenes automáticamente
    let slideInterval = setInterval(function() {
        let nextIndex = (currentIndex + 1) % totalItems;
        showSlide(nextIndex);
    }, 5000); // Cambiar cada 5 segundos

    // Pausar el temporizador cuando el usuario interactúa con el carrusel
    document.querySelector('.photo-carousel').addEventListener('mouseenter', function() {
        clearInterval(slideInterval);
    });

    // Reanudar el temporizador cuando el mouse sale del carrusel
    document.querySelector('.photo-carousel').addEventListener('mouseleave', function() {
        slideInterval = setInterval(function() {
            let nextIndex = (currentIndex + 1) % totalItems;
            showSlide(nextIndex);
        }, 5000);
    });

    // Navegación con teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && currentIndex > 0) {
            showSlide(currentIndex - 1);
        } else if (e.key === 'ArrowRight' && currentIndex < totalItems - 1) {
            showSlide(currentIndex + 1);
        }
    });

    // Ejecutar la detección de imágenes verticales
    detectVerticalImages();
});