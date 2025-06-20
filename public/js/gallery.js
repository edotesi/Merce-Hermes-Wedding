document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentPhotoIndex = 0;
    let currentView = localStorage.getItem('galleryView') || 'carousel'; // Recordar vista
    let photos = window.galleryData?.photos || [];

    // Elementos del DOM
    const carouselView = document.getElementById('carouselView');
    const gridView = document.getElementById('gridView');
    const toggleViewBtn = document.getElementById('toggleView');
    const mainImage = document.getElementById('mainImage');
    const mainImageContainer = document.querySelector('.main-image-container');
    const downloadBtn = document.getElementById('downloadBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const prevMainBtn = document.getElementById('prevMain');
    const nextMainBtn = document.getElementById('nextMain');
    const thumbnailsScroll = document.getElementById('thumbnailsScroll');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    // Modal elements
    const fullscreenModal = document.getElementById('fullscreenModal');
    const modalImage = document.getElementById('modalImage');
    const modalContent = document.querySelector('.modal-content');
    const modalClose = document.getElementById('modalClose');
    const modalPrev = document.getElementById('modalPrev');
    const modalNext = document.getElementById('modalNext');
    const modalDownload = document.getElementById('modalDownload');

    // Inicialización
    if (photos.length > 0) {
        initializeGallery();
    }

    function initializeGallery() {
        // Restaurar vista guardada
        if (currentView === 'grid') {
            toggleToGrid();
        } else {
            toggleToCarousel();
        }

        // Event listeners para toggle de vista
        toggleViewBtn?.addEventListener('click', toggleView);

        // Event listeners para navegación principal
        prevMainBtn?.addEventListener('click', () => navigatePhoto(-1));
        nextMainBtn?.addEventListener('click', () => navigatePhoto(1));

        // Event listeners para thumbnails
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            thumb.addEventListener('click', () => goToPhoto(index));
        });

        // Event listeners para scroll de thumbnails
        scrollLeftBtn?.addEventListener('click', () => scrollThumbnails(-1));
        scrollRightBtn?.addEventListener('click', () => scrollThumbnails(1));

        // Event listeners para pantalla completa
        fullscreenBtn?.addEventListener('click', openFullscreen);
        modalClose?.addEventListener('click', closeFullscreen);
        modalPrev?.addEventListener('click', () => navigateModal(-1));
        modalNext?.addEventListener('click', () => navigateModal(1));

        // Event listeners para grid view
        document.querySelectorAll('.grid-photo').forEach((gridPhoto, index) => {
            gridPhoto.addEventListener('click', (e) => {
                if (!e.target.closest('.photo-overlay')) {
                    currentPhotoIndex = index;
                    openFullscreen();
                }
            });
        });

        // Event listeners para fullscreen desde grid
        document.querySelectorAll('.overlay-fullscreen').forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                currentPhotoIndex = index;
                openFullscreen();
            });
        });

        // Event listeners para filtros de categoría (MANTENER VISTA)
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.target.dataset.category;
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('category', category);
                currentUrl.searchParams.set('view', currentView); // Mantener vista actual
                window.location.href = currentUrl.toString();
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboard);

        // Cerrar modal con click fuera de la imagen
        fullscreenModal?.addEventListener('click', (e) => {
            if (e.target === fullscreenModal) {
                closeFullscreen();
            }
        });

        // Actualizar controles de scroll
        updateScrollButtons();

        // Configurar imagen inicial
        if (photos.length > 0) {
            goToPhoto(0);
        }

        // Detectar imágenes verticales para fondo borroso
        detectVerticalImages();
    }

    function toggleView() {
        if (currentView === 'carousel') {
            toggleToGrid();
        } else {
            toggleToCarousel();
        }

        // Guardar preferencia
        localStorage.setItem('galleryView', currentView);
    }

    function toggleToGrid() {
        carouselView.style.display = 'none';
        gridView.style.display = 'block';
        toggleViewBtn.innerHTML = '<i class="fas fa-images"></i> Vista Carrusel';
        currentView = 'grid';
    }

    function toggleToCarousel() {
        gridView.style.display = 'none';
        carouselView.style.display = 'block';
        toggleViewBtn.innerHTML = '<i class="fas fa-th"></i> Vista Biblioteca';
        currentView = 'carousel';
    }

    function navigatePhoto(direction) {
        const newIndex = currentPhotoIndex + direction;
        if (newIndex >= 0 && newIndex < photos.length) {
            goToPhoto(newIndex);
        }
    }

    function goToPhoto(index) {
        if (index < 0 || index >= photos.length) return;

        currentPhotoIndex = index;
        const photo = photos[index];

        // Actualizar imagen principal
        if (mainImage) {
            mainImage.src = photo.url;
            mainImage.alt = photo.name;

            // Configurar fondo borroso para imágenes verticales
            setVerticalBackground(mainImageContainer, photo.url);
        }

        // Actualizar botón de descarga
        if (downloadBtn) {
            downloadBtn.href = photo.downloadUrl;
        }

        // Actualizar thumbnails
        document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });

        // Scroll automático a thumbnail activo
        scrollToActiveThumbnail();

        // Actualizar navegación (deshabilitar botones en los extremos)
        updateNavigationButtons();
    }

    function detectVerticalImages() {
        if (!mainImage) return;

        const img = new Image();
        img.onload = function() {
            if (this.naturalHeight > this.naturalWidth) {
                mainImageContainer?.classList.add('has-vertical');
                setVerticalBackground(mainImageContainer, this.src);
            } else {
                mainImageContainer?.classList.remove('has-vertical');
            }
        };
        img.src = mainImage.src;
    }

    function setVerticalBackground(container, imageUrl) {
        if (!container) return;

        const img = new Image();
        img.onload = function() {
            if (this.naturalHeight > this.naturalWidth) {
                container.style.setProperty('--bg-image', `url('${imageUrl}')`);
                container.classList.add('has-vertical');
            } else {
                container.classList.remove('has-vertical');
            }
        };
        img.src = imageUrl;
    }

    function scrollThumbnails(direction) {
        const scrollAmount = 140;
        const currentScroll = thumbnailsScroll.scrollLeft;
        const newScroll = currentScroll + (direction * scrollAmount);

        thumbnailsScroll.scrollTo({
            left: newScroll,
            behavior: 'smooth'
        });

        setTimeout(updateScrollButtons, 300);
    }

    function scrollToActiveThumbnail() {
        const activeThumbnail = document.querySelector('.thumbnail.active');
        if (activeThumbnail && thumbnailsScroll) {
            const thumbnailRect = activeThumbnail.getBoundingClientRect();
            const containerRect = thumbnailsScroll.getBoundingClientRect();

            if (thumbnailRect.left < containerRect.left || thumbnailRect.right > containerRect.right) {
                activeThumbnail.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }
    }

    function updateScrollButtons() {
        if (!thumbnailsScroll || !scrollLeftBtn || !scrollRightBtn) return;

        const canScrollLeft = thumbnailsScroll.scrollLeft > 0;
        const canScrollRight = thumbnailsScroll.scrollLeft <
            (thumbnailsScroll.scrollWidth - thumbnailsScroll.clientWidth);

        scrollLeftBtn.disabled = !canScrollLeft;
        scrollRightBtn.disabled = !canScrollRight;

        scrollLeftBtn.style.opacity = canScrollLeft ? '1' : '0.3';
        scrollRightBtn.style.opacity = canScrollRight ? '1' : '0.3';
    }

    function updateNavigationButtons() {
        if (prevMainBtn) {
            prevMainBtn.disabled = currentPhotoIndex === 0;
            prevMainBtn.style.opacity = currentPhotoIndex === 0 ? '0.3' : '1';
        }
        if (nextMainBtn) {
            nextMainBtn.disabled = currentPhotoIndex === photos.length - 1;
            nextMainBtn.style.opacity = currentPhotoIndex === photos.length - 1 ? '0.3' : '1';
        }
    }

    function openFullscreen() {
        if (!fullscreenModal || photos.length === 0) return;

        const photo = photos[currentPhotoIndex];
        modalImage.src = photo.url;
        modalImage.alt = photo.name;
        modalDownload.href = photo.downloadUrl;

        // Configurar fondo borroso para modal también
        setVerticalBackground(modalContent, photo.url, true);

        fullscreenModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        updateModalNavigation();
    }

    function closeFullscreen() {
        if (!fullscreenModal) return;

        fullscreenModal.classList.remove('active');
        document.body.style.overflow = '';
        modalContent?.classList.remove('has-vertical');
    }

    function navigateModal(direction) {
        const newIndex = currentPhotoIndex + direction;
        if (newIndex >= 0 && newIndex < photos.length) {
            currentPhotoIndex = newIndex;
            const photo = photos[currentPhotoIndex];

            modalImage.src = photo.url;
            modalImage.alt = photo.name;
            modalDownload.href = photo.downloadUrl;

            // Configurar fondo borroso para nueva imagen
            setVerticalBackground(modalContent, photo.url, true);

            // Si estamos en vista carrusel, actualizar también la imagen principal
            if (currentView === 'carousel') {
                goToPhoto(currentPhotoIndex);
            }

            updateModalNavigation();
        }
    }

    function updateModalNavigation() {
        if (modalPrev) {
            modalPrev.disabled = currentPhotoIndex === 0;
            modalPrev.style.opacity = currentPhotoIndex === 0 ? '0.3' : '1';
        }
        if (modalNext) {
            modalNext.disabled = currentPhotoIndex === photos.length - 1;
            modalNext.style.opacity = currentPhotoIndex === photos.length - 1 ? '0.3' : '1';
        }
    }

    function handleKeyboard(e) {
        if (fullscreenModal?.classList.contains('active')) {
            switch(e.key) {
                case 'Escape':
                    closeFullscreen();
                    break;
                case 'ArrowLeft':
                    navigateModal(-1);
                    break;
                case 'ArrowRight':
                    navigateModal(1);
                    break;
            }
        } else if (currentView === 'carousel') {
            switch(e.key) {
                case 'ArrowLeft':
                    navigatePhoto(-1);
                    break;
                case 'ArrowRight':
                    navigatePhoto(1);
                    break;
                case ' ':
                case 'Enter':
                    openFullscreen();
                    e.preventDefault();
                    break;
            }
        }
    }

    // Touch/Swipe support para móviles
    let touchStartX = 0;
    let touchEndX = 0;

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - next photo
                if (fullscreenModal?.classList.contains('active')) {
                    navigateModal(1);
                } else if (currentView === 'carousel') {
                    navigatePhoto(1);
                }
            } else {
                // Swipe right - previous photo
                if (fullscreenModal?.classList.contains('active')) {
                    navigateModal(-1);
                } else if (currentView === 'carousel') {
                    navigatePhoto(-1);
                }
            }
        }
    }

    // Event listeners para touch
    [mainImage, modalImage].forEach(element => {
        if (element) {
            element.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            element.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
    });

    // Scroll listener para thumbnails
    thumbnailsScroll?.addEventListener('scroll', updateScrollButtons);

    // Resize listener
    window.addEventListener('resize', () => {
        setTimeout(updateScrollButtons, 100);
    });

    // Restaurar vista desde URL si existe
    const urlParams = new URLSearchParams(window.location.search);
    const viewParam = urlParams.get('view');
    if (viewParam && (viewParam === 'grid' || viewParam === 'carousel')) {
        currentView = viewParam;
        localStorage.setItem('galleryView', currentView);
    }
});
