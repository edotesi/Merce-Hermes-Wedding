/**
 * Simplified Gallery JavaScript
 * Maneja la galería simplificada con masonry en desktop y grid en móvil
 */

class SimplifiedGallery {
    constructor() {
        this.photos = window.galleryData?.photos || [];
        this.currentCategory = window.galleryData?.currentCategory || 'todo';
        this.currentPhotoIndex = 0;
        this.isSelectionMode = false;
        this.selectedPhotos = new Set();
        this.isLightboxOpen = false;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupKeyboardNavigation();
        this.setupIntersectionObserver();
        this.setupCategoryIcons();
    }

    setupEventListeners() {
        // Category filters
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const category = e.currentTarget.dataset.category;
                this.filterByCategory(category);
            });
        });

        // Selection mode
        document.getElementById('selectModeBtn')?.addEventListener('click', () => {
            this.toggleSelectionMode();
        });

        // Photo interactions
        this.setupPhotoListeners();

        // Lightbox controls
        this.setupLightboxListeners();

        // Selection bar
        this.setupSelectionBarListeners();
    }

    setupPhotoListeners() {
        document.addEventListener('click', (e) => {
            const photoItem = e.target.closest('.gallery-photo');
            if (!photoItem) return;

            const action = e.target.closest('[data-action]')?.dataset.action;
            const photoIndex = parseInt(photoItem.dataset.index);

            switch (action) {
                case 'view':
                    e.preventDefault();
                    this.openLightbox(photoIndex);
                    break;
                case 'select':
                    e.preventDefault();
                    this.togglePhotoSelection(photoItem);
                    break;
                default:
                    // Click en la foto sin acción específica
                    if (!this.isSelectionMode && !e.target.closest('.photo-actions')) {
                        this.openLightbox(photoIndex);
                    } else if (this.isSelectionMode) {
                        this.togglePhotoSelection(photoItem);
                    }
            }
        });
    }

    setupLightboxListeners() {
        // Close lightbox
        document.getElementById('lightboxClose')?.addEventListener('click', () => {
            this.closeLightbox();
        });

        // Navigation
        document.getElementById('lightboxPrev')?.addEventListener('click', () => {
            this.navigateLightbox(-1);
        });

        document.getElementById('lightboxNext')?.addEventListener('click', () => {
            this.navigateLightbox(1);
        });

        // Thumbnail navigation
        document.addEventListener('click', (e) => {
            if (e.target.closest('.thumbnail-item')) {
                const index = parseInt(e.target.closest('.thumbnail-item').dataset.index);
                this.showPhotoInLightbox(index);
            }
        });

        // Click outside to close
        document.getElementById('lightboxModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'lightboxModal') {
                this.closeLightbox();
            }
        });
    }

    setupSelectionBarListeners() {
        document.getElementById('selectAllBtn')?.addEventListener('click', () => {
            this.selectAllPhotos();
        });

        document.getElementById('deselectAllBtn')?.addEventListener('click', () => {
            this.deselectAllPhotos();
        });

        document.getElementById('downloadSelectedBtn')?.addEventListener('click', () => {
            this.downloadSelectedPhotos();
        });

        document.getElementById('cancelSelectionBtn')?.addEventListener('click', () => {
            this.toggleSelectionMode();
        });
    }

    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (!this.isLightboxOpen) return;

            switch (e.key) {
                case 'Escape':
                    this.closeLightbox();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    this.navigateLightbox(-1);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.navigateLightbox(1);
                    break;
            }
        });
    }

    setupIntersectionObserver() {
        // Lazy loading para imágenes
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '100px'
        });

        // Observar imágenes con data-src
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    setupCategoryIcons() {
        // Función helper para obtener iconos de categorías
        window.getCategoryIcon = (category) => {
            const icons = {
                'ceremonia': 'church',
                'bienvenida': 'glass-cheers',
                'banquete': 'utensils',
                'fiesta': 'music',
                'fotomaton': 'camera',
                'preboda': 'heart'
            };
            return icons[category] || 'image';
        };
    }

    filterByCategory(category) {
        // Mostrar loading
        this.showLoading();

        // Actualizar URL sin recargar página
        const url = new URL(window.location);
        if (category === 'todo') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.history.pushState({}, '', url);

        // Simular carga (en una implementación real sería una llamada AJAX)
        setTimeout(() => {
            this.currentCategory = category;
            this.updateActiveFilter();
            this.filterPhotosDisplay();
            this.hideLoading();
        }, 300);
    }

    updateActiveFilter() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === this.currentCategory);
        });
    }

    filterPhotosDisplay() {
        const photoItems = document.querySelectorAll('.gallery-photo');

        photoItems.forEach(item => {
            const photoCategory = item.dataset.category;
            const shouldShow = this.currentCategory === 'todo' || photoCategory === this.currentCategory;

            if (shouldShow) {
                item.style.display = '';
                this.animateIn(item);
            } else {
                this.animateOut(item);
            }
        });
    }

    toggleSelectionMode() {
        this.isSelectionMode = !this.isSelectionMode;

        // Actualizar botón
        const selectBtn = document.getElementById('selectModeBtn');
        if (selectBtn) {
            selectBtn.classList.toggle('active', this.isSelectionMode);
            const icon = selectBtn.querySelector('i');
            const text = selectBtn.querySelector('span');

            if (this.isSelectionMode) {
                icon.className = 'fas fa-times';
                text.textContent = 'Cancelar';
            } else {
                icon.className = 'fas fa-check-square';
                text.textContent = 'Seleccionar fotos';
                this.deselectAllPhotos();
            }
        }

        // Mostrar/ocultar barra de selección
        const selectionBar = document.getElementById('selectionBar');
        if (this.isSelectionMode) {
            selectionBar?.classList.add('active');
        } else {
            selectionBar?.classList.remove('active');
        }

        // Actualizar cursor del documento
        document.body.classList.toggle('selection-mode', this.isSelectionMode);
    }

    togglePhotoSelection(photoItem) {
        const photoId = photoItem.dataset.photoId;

        if (this.selectedPhotos.has(photoId)) {
            this.selectedPhotos.delete(photoId);
            photoItem.classList.remove('selected');
        } else {
            this.selectedPhotos.add(photoId);
            photoItem.classList.add('selected');
        }

        this.updateSelectionCount();
    }

    selectAllPhotos() {
        const visiblePhotos = document.querySelectorAll('.gallery-photo:not([style*="display: none"])');

        visiblePhotos.forEach(photo => {
            const photoId = photo.dataset.photoId;
            this.selectedPhotos.add(photoId);
            photo.classList.add('selected');
        });

        this.updateSelectionCount();
    }

    deselectAllPhotos() {
        this.selectedPhotos.clear();
        document.querySelectorAll('.gallery-photo').forEach(photo => {
            photo.classList.remove('selected');
        });

        this.updateSelectionCount();
    }

    updateSelectionCount() {
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = this.selectedPhotos.size;
        }

        // Habilitar/deshabilitar botones según selección
        const downloadBtn = document.getElementById('downloadSelectedBtn');
        const deselectBtn = document.getElementById('deselectAllBtn');

        const hasSelection = this.selectedPhotos.size > 0;
        downloadBtn?.classList.toggle('disabled', !hasSelection);
        deselectBtn?.classList.toggle('disabled', !hasSelection);
    }

    downloadSelectedPhotos() {
        if (this.selectedPhotos.size === 0) return;

        // En una implementación real, esto haría una llamada al servidor
        // Para este ejemplo, abrimos cada enlace de descarga
        const selectedPhotosData = this.photos.filter(photo =>
            this.selectedPhotos.has(photo.id.toString())
        );

        selectedPhotosData.forEach((photo, index) => {
            setTimeout(() => {
                const link = document.createElement('a');
                link.href = photo.downloadUrl;
                link.download = photo.name;
                link.click();
            }, index * 100); // Pequeño delay entre descargas
        });

        this.showNotification(`Descargando ${this.selectedPhotos.size} fotos...`);
    }

    openLightbox(photoIndex) {
        this.currentPhotoIndex = photoIndex;
        this.isLightboxOpen = true;

        const modal = document.getElementById('lightboxModal');
        modal?.classList.add('active');

        this.showPhotoInLightbox(photoIndex);

        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
    }

    closeLightbox() {
        this.isLightboxOpen = false;

        const modal = document.getElementById('lightboxModal');
        modal?.classList.remove('active');

        // Restaurar scroll del body
        document.body.style.overflow = '';
    }

    navigateLightbox(direction) {
        const newIndex = this.currentPhotoIndex + direction;

        if (newIndex >= 0 && newIndex < this.photos.length) {
            this.showPhotoInLightbox(newIndex);
        }
    }

    showPhotoInLightbox(index) {
        if (index < 0 || index >= this.photos.length) return;

        this.currentPhotoIndex = index;
        const photo = this.photos[index];

        // Actualizar imagen
        const lightboxImage = document.getElementById('lightboxImage');
        if (lightboxImage) {
            lightboxImage.src = photo.url;
            lightboxImage.alt = photo.name;
        }

        // Actualizar información
        document.getElementById('lightboxTitle').textContent = photo.name;
        document.getElementById('lightboxCategory').textContent = `Categoría: ${photo.category}`;

        // Actualizar botón de descarga
        const downloadBtn = document.getElementById('lightboxDownload');
        if (downloadBtn) {
            downloadBtn.href = photo.downloadUrl;
        }

        // Actualizar contador
        document.getElementById('lightboxPosition').textContent = index + 1;
        document.getElementById('lightboxTotal').textContent = this.photos.length;

        // Actualizar thumbnails activos
        this.updateActiveThumbnail(index);

        // Scroll a thumbnail visible
        this.scrollToThumbnail(index);
    }

    updateActiveThumbnail(index) {
        document.querySelectorAll('.thumbnail-item').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
    }

    scrollToThumbnail(index) {
        const thumbnail = document.querySelector(`.thumbnail-item[data-index="${index}"]`);
        if (thumbnail) {
            thumbnail.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }
    }

    // Utility methods
    showLoading() {
        document.getElementById('galleryLoading')?.classList.add('active');
    }

    hideLoading() {
        document.getElementById('galleryLoading')?.classList.remove('active');
    }

    showNotification(message) {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: var(--gold-bright);
            color: var(--olive);
            padding: 1rem 2rem;
            border-radius: 8px;
            z-index: 10001;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    animateIn(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.3s ease';

        requestAnimationFrame(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        });
    }

    animateOut(element) {
        element.style.transition = 'all 0.3s ease';
        element.style.opacity = '0';
        element.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            element.style.display = 'none';
        }, 300);
    }
}

// CSS adicional para animaciones
const additionalStyles = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    .selection-mode .gallery-photo {
        cursor: pointer !important;
    }

    .gallery-photo.selected {
        transform: scale(0.95) !important;
        opacity: 0.8 !important;
    }

    .selection-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
`;

// Inyectar estilos adicionales
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// Inicializar galería cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new SimplifiedGallery();
});

// Exportar para uso global si es necesario
window.SimplifiedGallery = SimplifiedGallery;
