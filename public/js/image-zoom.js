// Función para expandir imágenes al hacer clic
function setupImageZoom() {
    // Variables globales para el zoom
    let currentZoomIndex = 0;
    let zoomImages = [];

    // Crear el modal para la imagen ampliada si no existe
    if (!document.querySelector('.image-zoom-modal')) {
        const modal = document.createElement('div');
        modal.className = 'image-zoom-modal';
        modal.innerHTML = `
            <div class="image-zoom-content">
                <img src="" class="image-zoom-img">
                <button class="image-zoom-close">&times;</button>
                <div class="zoom-nav-buttons">
                    <button class="zoom-nav-button zoom-prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="zoom-nav-button zoom-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Recolectar todas las imágenes del carrusel
    const carouselImages = document.querySelectorAll('.carousel-image');
    zoomImages = Array.from(carouselImages).map(img => img.src);

    // Agregar los eventos de clic a las imágenes del carrusel
    carouselImages.forEach((img, index) => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() {
            openZoomModal(index);
        });
    });

    // Función para abrir el modal de zoom
    function openZoomModal(index) {
        const modal = document.querySelector('.image-zoom-modal');
        const zoomImg = document.querySelector('.image-zoom-img');

        currentZoomIndex = index;
        zoomImg.src = zoomImages[currentZoomIndex];

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        updateZoomNavButtons();
    }

    // Función para actualizar estado de botones de navegación
    function updateZoomNavButtons() {
        const prevBtn = document.querySelector('.zoom-prev');
        const nextBtn = document.querySelector('.zoom-next');

        prevBtn.disabled = currentZoomIndex === 0;
        nextBtn.disabled = currentZoomIndex === zoomImages.length - 1;

        prevBtn.style.opacity = prevBtn.disabled ? '0.3' : '1';
        nextBtn.style.opacity = nextBtn.disabled ? '0.3' : '1';
    }

    // Función para navegar entre imágenes en modo zoom
    function navigateZoom(direction) {
        const newIndex = currentZoomIndex + direction;

        if (newIndex >= 0 && newIndex < zoomImages.length) {
            currentZoomIndex = newIndex;
            document.querySelector('.image-zoom-img').src = zoomImages[currentZoomIndex];
            updateZoomNavButtons();
        }
    }

    // Eventos para los botones de navegación en zoom
    document.querySelector('.zoom-prev')?.addEventListener('click', () => navigateZoom(-1));
    document.querySelector('.zoom-next')?.addEventListener('click', () => navigateZoom(1));

    // Cerrar el modal
    const closeBtn = document.querySelector('.image-zoom-close');
    const modal = document.querySelector('.image-zoom-modal');

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }

    // Navegación con teclado en modo zoom
    document.addEventListener('keydown', function(e) {
        const modal = document.querySelector('.image-zoom-modal');
        if (modal && modal.style.display === 'flex') {
            if (e.key === 'Escape') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            } else if (e.key === 'ArrowLeft') {
                navigateZoom(-1);
            } else if (e.key === 'ArrowRight') {
                navigateZoom(1);
            }
        }
    });
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', setupImageZoom);