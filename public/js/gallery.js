document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentIndex = 0;
    let filteredPhotos = [];
    let currentCategory = 'todo';
    let selectedPhotos = new Set();
    let isSelectionMode = false;
    let isGridView = false;
    // CAMBIO PRINCIPAL: Nuevo breakpoint 1024px (antes 991px)
    let isMobile = window.innerWidth <= 1024;

    // Elementos del DOM
    const mainViewerImage = document.querySelector('#mainViewerImage');
    const viewerCategory = document.querySelector('#viewerCategory');
    const viewerCounter = document.querySelector('#viewerCounter');
    const photosRow = document.querySelector('#photosRow');
    const photoGrid = document.querySelector('#photoGrid');
    const gridViewSection = document.querySelector('#gridViewSection');
    const toggleGridBtn = document.querySelector('#toggleGridView');
    const selectionBar = document.querySelector('#selectionBar');
    const selectedCountEl = document.querySelector('#selectedCount');
    const fullscreenModal = document.querySelector('#fullscreenModal');
    const mobileModal = document.querySelector('#mobileModal');
    const loadingOverlay = document.querySelector('.loading-overlay');

    // Verificar que los datos existen
    if (typeof window.galleryData === 'undefined' || !window.galleryData.photos) {
        console.error('Gallery data not found');
        return;
    }

    const allPhotos = window.galleryData.photos;
    filteredPhotos = [...allPhotos];
    currentCategory = window.galleryData.currentCategory || 'todo';

    /**
     * Inicializar la galería
     */
    function initGallery() {
        if (isMobile) {
            // TABLETS Y MÓVILES: Solo grid
            initMobileGallery();
        } else {
            // DESKTOP: Funcionalidad completa
            initDesktopGallery();
        }

        initCategoryFilters();
        initSelectionMode();
        initKeyboardNavigation();
        initTouchNavigation();

        // Filtrar por categoría inicial
        if (currentCategory !== 'todo') {
            filterByCategory(currentCategory);
        } else {
            updateAllViews();
        }
    }

    /**
     * Inicializar galería desktop
     */
    function initDesktopGallery() {
        createMainViewer();
        createPhotosRow();
        initViewToggle();
        initFullscreenModal();
        updateMainViewer();
    }

    /**
     * Inicializar galería móvil/tablet - SOLO GRID
     */
    function initMobileGallery() {
        // Forzar vista grid para móviles/tablets
        if (gridViewSection) {
            gridViewSection.style.display = 'block';
            isGridView = true;
        }

        createPhotoGrid();
        initMobileModal();
    }

    /**
     * Crear visualizador principal - SOLO DESKTOP
     */
    function createMainViewer() {
        if (!mainViewerImage || filteredPhotos.length === 0) return;

        updateMainViewer();

        // Event listeners para navegación
        const prevBtn = document.querySelector('.viewer-prev');
        const nextBtn = document.querySelector('.viewer-next');
        const fullscreenBtn = document.querySelector('.fullscreen-btn');

        if (prevBtn) prevBtn.addEventListener('click', () => navigateViewer(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => navigateViewer(1));
        if (fullscreenBtn) fullscreenBtn.addEventListener('click', openFullscreen);
    }

    /**
     * Actualizar visualizador principal
     */
    function updateMainViewer() {
        if (isMobile || !mainViewerImage || filteredPhotos.length === 0) return;

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        mainViewerImage.src = photo.url;
        mainViewerImage.alt = photo.name;

        if (viewerCategory) {
            viewerCategory.textContent = window.getCategoryDisplayName(photo.category);
        }

        if (viewerCounter) {
            viewerCounter.textContent = `${currentIndex + 1} de ${filteredPhotos.length}`;
        }
    }

    /**
     * Navegar en el visualizador principal
     */
    function navigateViewer(direction) {
        if (isMobile || filteredPhotos.length === 0) return;

        currentIndex += direction;

        if (currentIndex >= filteredPhotos.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = filteredPhotos.length - 1;
        }

        updateMainViewer();
        updatePhotosRow();
        updatePhotoGrid();
    }

    /**
     * Crear fila horizontal de fotos - SOLO DESKTOP
     */
    function createPhotosRow() {
        if (isMobile || !photosRow) return;

        photosRow.innerHTML = '';

        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement('div');
            item.className = `row-photo-item ${index === currentIndex ? 'active' : ''}`;
            item.dataset.index = index;

            item.innerHTML = `
                <img src="${photo.url}" alt="${photo.name}" loading="lazy">
                <div class="row-photo-overlay">
                    <div class="row-photo-actions">
                        <button class="row-action-btn view-btn" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="${photo.downloadUrl}" class="row-action-btn download-btn" title="Descargar">
                            <i class="fas fa-download"></i>
                        </a>
                        <button class="row-action-btn select-btn" title="Seleccionar">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
                <div class="selection-indicator">
                    <i class="fas fa-check"></i>
                </div>
            `;

            // Event listeners
            item.addEventListener('click', () => selectPhoto(index));

            const viewBtn = item.querySelector('.view-btn');
            const selectBtn = item.querySelector('.select-btn');

            if (viewBtn) {
                viewBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    setActivePhoto(index);
                });
            }

            if (selectBtn) {
                selectBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    togglePhotoSelection(photo.id);
                });
            }

            photosRow.appendChild(item);
        });

        // Scroll buttons
        initRowScrollButtons();
    }

    /**
     * Configurar botones de scroll para la fila
     */
    function initRowScrollButtons() {
        const scrollLeft = document.querySelector('.row-scroll-left');
        const scrollRight = document.querySelector('.row-scroll-right');

        if (scrollLeft) {
            scrollLeft.addEventListener('click', () => {
                if (photosRow) {
                    photosRow.scrollBy({ left: -400, behavior: 'smooth' });
                }
            });
        }

        if (scrollRight) {
            scrollRight.addEventListener('click', () => {
                if (photosRow) {
                    photosRow.scrollBy({ left: 400, behavior: 'smooth' });
                }
            });
        }
    }

    /**
     * Actualizar fila de fotos
     */
    function updatePhotosRow() {
        if (isMobile) return;

        const items = photosRow?.querySelectorAll('.row-photo-item');
        if (!items) return;

        items.forEach((item, index) => {
            item.classList.toggle('active', index === currentIndex);
            item.classList.toggle('selected', selectedPhotos.has(filteredPhotos[index]?.id));
        });

        // Scroll automático para mantener la foto activa visible
        const activeItem = photosRow?.querySelector('.row-photo-item.active');
        if (activeItem && photosRow) {
            const containerRect = photosRow.getBoundingClientRect();
            const itemRect = activeItem.getBoundingClientRect();

            if (itemRect.left < containerRect.left || itemRect.right > containerRect.right) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
    }

    /**
     * Establecer foto activa
     */
    function setActivePhoto(index) {
        if (isSelectionMode) {
            togglePhotoSelection(filteredPhotos[index]?.id);
            return;
        }

        currentIndex = index;

        if (!isMobile) {
            updateMainViewer();
            updatePhotosRow();
        }
        updatePhotoGrid();
    }

    /**
     * Seleccionar foto (en modo selección o clic normal)
     */
    function selectPhoto(index) {
        if (isMobile) {
            // En móvil/tablet abrir modal
            openMobileModal(index);
        } else if (isSelectionMode) {
            togglePhotoSelection(filteredPhotos[index]?.id);
        } else {
            setActivePhoto(index);
        }
    }

    /**
     * Toggle para vista grid - SOLO DESKTOP
     */
    function initViewToggle() {
        if (isMobile || !toggleGridBtn) return;

        toggleGridBtn.addEventListener('click', () => {
            isGridView = !isGridView;

            if (isGridView) {
                gridViewSection.style.display = 'block';
                toggleGridBtn.innerHTML = '<i class="fas fa-list"></i><span>Vista en fila</span>';
                toggleGridBtn.classList.add('active');
                createPhotoGrid();
            } else {
                gridViewSection.style.display = 'none';
                toggleGridBtn.innerHTML = '<i class="fas fa-th"></i><span>Vista en cuadrícula</span>';
                toggleGridBtn.classList.remove('active');
            }
        });
    }

    /**
     * Crear grid de fotos
     */
    function createPhotoGrid() {
        if (!photoGrid) return;

        photoGrid.innerHTML = '';

        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement('div');
            item.className = 'grid-photo-item';
            item.dataset.index = index;

            // CAMBIO: Para móviles/tablets NO incluir sub-opciones
            if (isMobile) {
                item.innerHTML = `
                    <img src="${photo.thumbnailUrl || photo.url}" alt="${photo.name}" loading="lazy">
                    <div class="selection-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                `;
            } else {
                // Desktop: incluir sub-opciones
                item.innerHTML = `
                    <img src="${photo.thumbnailUrl || photo.url}" alt="${photo.name}" loading="lazy">
                    <div class="grid-photo-overlay">
                        <div class="grid-photo-actions">
                            <button class="grid-action-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="${photo.downloadUrl}" class="grid-action-btn download-btn" title="Descargar">
                                <i class="fas fa-download"></i>
                            </a>
                            <button class="grid-action-btn select-btn" title="Seleccionar">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    <div class="selection-indicator">
                        <i class="fas fa-check"></i>
                    </div>
                `;
            }

            // Event listener principal
            item.addEventListener('click', () => {
                if (isSelectionMode) {
                    togglePhotoSelection(photo.id);
                } else {
                    selectPhoto(index);
                }
            });

            // Event listeners adicionales solo para desktop
            if (!isMobile) {
                const viewBtn = item.querySelector('.view-btn');
                const selectBtn = item.querySelector('.select-btn');

                if (viewBtn) {
                    viewBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (isGridView) {
                            openFullscreen(index);
                        } else {
                            setActivePhoto(index);
                        }
                    });
                }

                if (selectBtn) {
                    selectBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        togglePhotoSelection(photo.id);
                    });
                }
            }

            photoGrid.appendChild(item);
        });

        updatePhotoGrid();
    }

    /**
     * Actualizar grid de fotos
     */
    function updatePhotoGrid() {
        const items = photoGrid?.querySelectorAll('.grid-photo-item');
        if (!items) return;

        items.forEach((item, index) => {
            const isSelected = selectedPhotos.has(filteredPhotos[index]?.id);
            item.classList.toggle('selected', isSelected);

            const indicator = item.querySelector('.selection-indicator');
            if (indicator) {
                indicator.classList.toggle('visible', isSelectionMode || isSelected);
            }
        });
    }

    /**
     * Filtros de categoría
     */
    function initCategoryFilters() {
        const filterBtns = document.querySelectorAll('.filter-btn, .filter-btn-mobile');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.dataset.category;
                filterByCategory(category);
            });
        });
    }

    function filterByCategory(category) {
        showLoading();

        currentCategory = category;

        // Actualizar estado de los filtros
        const filterBtns = document.querySelectorAll('.filter-btn, .filter-btn-mobile');
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === category);
        });

        // Filtrar fotos
        if (category === 'todo') {
            filteredPhotos = [...allPhotos];
        } else {
            filteredPhotos = allPhotos.filter(photo => photo.category === category);
        }

        // Reiniciar índices
        currentIndex = 0;

        // Actualizar todas las vistas
        setTimeout(() => {
            updateAllViews();
            hideLoading();
        }, 300);

        // Actualizar URL
        const url = new URL(window.location);
        if (category === 'todo') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.history.pushState({}, '', url);
    }

    /**
     * Actualizar todas las vistas
     */
    function updateAllViews() {
        if (isMobile) {
            // Móviles/tablets: solo grid
            createPhotoGrid();
        } else {
            // Desktop: todas las vistas
            updateMainViewer();
            createPhotosRow();
            if (isGridView) {
                createPhotoGrid();
            }
        }
        updateSelectionBar();
    }

    /**
     * Modo de selección
     */
    function initSelectionMode() {
        const selectModeBtn = document.querySelector('#selectModeBtn');
        const selectModeBtnMobile = document.querySelector('#selectModeBtnMobile');
        const selectAllBtn = document.querySelector('#selectAllBtn');
        const deselectAllBtn = document.querySelector('#deselectAllBtn');
        const downloadSelectedBtn = document.querySelector('#downloadSelectedBtn');
        const cancelSelectionBtn = document.querySelector('#cancelSelectionBtn');

        [selectModeBtn, selectModeBtnMobile].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', toggleSelectionMode);
            }
        });

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', selectAllPhotos);
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', deselectAllPhotos);
        }

        if (downloadSelectedBtn) {
            downloadSelectedBtn.addEventListener('click', downloadSelectedPhotos);
        }

        if (cancelSelectionBtn) {
            cancelSelectionBtn.addEventListener('click', cancelSelection);
        }
    }

    function toggleSelectionMode() {
        isSelectionMode = !isSelectionMode;

        document.body.classList.toggle('selection-mode', isSelectionMode);

        if (!isSelectionMode) {
            selectedPhotos.clear();
            updateSelectionBar();
            if (!isMobile) {
                updatePhotosRow();
            }
            updatePhotoGrid();
        } else {
            updateSelectionBar();
        }
    }

    function togglePhotoSelection(photoId) {
        if (selectedPhotos.has(photoId)) {
            selectedPhotos.delete(photoId);
        } else {
            selectedPhotos.add(photoId);
        }

        updateSelectionBar();
        if (!isMobile) {
            updatePhotosRow();
        }
        updatePhotoGrid();
    }

    function selectAllPhotos() {
        filteredPhotos.forEach(photo => {
            selectedPhotos.add(photo.id);
        });

        updateSelectionBar();
        if (!isMobile) {
            updatePhotosRow();
        }
        updatePhotoGrid();
    }

    function deselectAllPhotos() {
        selectedPhotos.clear();

        updateSelectionBar();
        if (!isMobile) {
            updatePhotosRow();
        }
        updatePhotoGrid();
    }

    function cancelSelection() {
        toggleSelectionMode();
    }

    function downloadSelectedPhotos() {
        if (selectedPhotos.size === 0) return;

        // Crear formulario para envío POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/galeria/download-selected';
        form.style.display = 'none';

        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.content;
            form.appendChild(tokenInput);
        }

        // IDs de fotos seleccionadas
        selectedPhotos.forEach(photoId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'photo_ids[]';
            input.value = photoId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    function updateSelectionBar() {
        if (!selectionBar || !selectedCountEl) return;

        const count = selectedPhotos.size;
        selectedCountEl.textContent = count;

        if (isSelectionMode && count > 0) {
            selectionBar.classList.add('active');
        } else {
            selectionBar.classList.remove('active');
        }
    }

    /**
     * Modal pantalla completa (Desktop)
     */
    function initFullscreenModal() {
        if (isMobile || !fullscreenModal) return;

        const closeBtn = fullscreenModal.querySelector('.fullscreen-close');
        const prevBtn = fullscreenModal.querySelector('.fullscreen-prev');
        const nextBtn = fullscreenModal.querySelector('.fullscreen-next');
        const downloadBtn = fullscreenModal.querySelector('#fullscreenDownload');

        if (closeBtn) closeBtn.addEventListener('click', closeFullscreen);
        if (prevBtn) prevBtn.addEventListener('click', () => navigateFullscreen(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => navigateFullscreen(1));

        // Cerrar al hacer clic fuera
        fullscreenModal.addEventListener('click', (e) => {
            if (e.target === fullscreenModal) {
                closeFullscreen();
            }
        });
    }

    function openFullscreen(index = currentIndex) {
        if (isMobile || !fullscreenModal) return;

        currentIndex = index;
        updateFullscreenContent();
        fullscreenModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFullscreen() {
        if (!fullscreenModal) return;

        fullscreenModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function navigateFullscreen(direction) {
        if (filteredPhotos.length === 0) return;

        currentIndex += direction;

        if (currentIndex >= filteredPhotos.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = filteredPhotos.length - 1;
        }

        updateFullscreenContent();
        updateMainViewer();
        updatePhotosRow();
    }

    function updateFullscreenContent() {
        if (!fullscreenModal || filteredPhotos.length === 0) return;

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        const img = fullscreenModal.querySelector('.fullscreen-image');
        const title = fullscreenModal.querySelector('.fullscreen-title');
        const category = fullscreenModal.querySelector('.fullscreen-category');
        const position = fullscreenModal.querySelector('#fullscreenPosition');
        const total = fullscreenModal.querySelector('#fullscreenTotal');
        const downloadBtn = fullscreenModal.querySelector('#fullscreenDownload');

        if (img) {
            img.src = photo.url;
            img.alt = photo.name;
        }

        if (title) title.textContent = photo.name;
        if (category) category.textContent = window.getCategoryDisplayName(photo.category);
        if (position) position.textContent = currentIndex + 1;
        if (total) total.textContent = filteredPhotos.length;

        if (downloadBtn) {
            downloadBtn.onclick = () => window.open(photo.downloadUrl, '_blank');
        }
    }

    /**
     * Modal móvil/tablet
     */
    function initMobileModal() {
        if (!mobileModal) return;

        const closeBtn = mobileModal.querySelector('.mobile-close-btn');
        const prevBtn = mobileModal.querySelector('.mobile-prev');
        const nextBtn = mobileModal.querySelector('.mobile-next');

        if (closeBtn) closeBtn.addEventListener('click', closeMobileModal);
        if (prevBtn) prevBtn.addEventListener('click', () => navigateMobileModal(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => navigateMobileModal(1));

        // Cerrar al hacer clic fuera
        mobileModal.addEventListener('click', (e) => {
            if (e.target === mobileModal) {
                closeMobileModal();
            }
        });
    }

    function openMobileModal(index = 0) {
        if (!mobileModal) return;

        currentIndex = index;
        updateMobileModalContent();
        createMobileThumbnails();
        mobileModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileModal() {
        if (!mobileModal) return;

        mobileModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function navigateMobileModal(direction) {
        if (filteredPhotos.length === 0) return;

        currentIndex += direction;

        if (currentIndex >= filteredPhotos.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = filteredPhotos.length - 1;
        }

        updateMobileModalContent();
        updateMobileThumbnails();
    }

    function updateMobileModalContent() {
        if (!mobileModal || filteredPhotos.length === 0) return;

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        const img = mobileModal.querySelector('.mobile-modal-image');
        const title = mobileModal.querySelector('.mobile-modal-title');
        const category = mobileModal.querySelector('.mobile-modal-category');
        const position = mobileModal.querySelector('#mobilePosition');
        const total = mobileModal.querySelector('#mobileTotal');
        const downloadBtn = mobileModal.querySelector('.mobile-download-btn');

        if (img) {
            img.src = photo.url;
            img.alt = photo.name;
        }

        if (title) title.textContent = photo.name;
        if (category) category.textContent = window.getCategoryDisplayName(photo.category);
        if (position) position.textContent = currentIndex + 1;
        if (total) total.textContent = filteredPhotos.length;

        if (downloadBtn) {
            downloadBtn.onclick = () => window.open(photo.downloadUrl, '_blank');
        }
    }

    function createMobileThumbnails() {
        const track = document.querySelector('#mobileThumbnailsTrack');
        if (!track) return;

        track.innerHTML = '';

        filteredPhotos.forEach((photo, index) => {
            const thumb = document.createElement('div');
            thumb.className = `mobile-thumb-item ${index === currentIndex ? 'active' : ''}`;
            thumb.innerHTML = `<img src="${photo.thumbnailUrl || photo.url}" alt="${photo.name}">`;

            thumb.addEventListener('click', () => {
                currentIndex = index;
                updateMobileModalContent();
                updateMobileThumbnails();
            });

            track.appendChild(thumb);
        });
    }

    function updateMobileThumbnails() {
        const thumbs = document.querySelectorAll('.mobile-thumb-item');
        thumbs.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentIndex);
        });

        // Scroll automático
        const activeThumb = document.querySelector('.mobile-thumb-item.active');
        if (activeThumb) {
            activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    /**
     * Navegación con teclado
     */
    function initKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (fullscreenModal?.classList.contains('active')) {
                handleFullscreenKeys(e);
            } else if (mobileModal?.classList.contains('active')) {
                handleMobileModalKeys(e);
            } else if (!isMobile) {
                handleMainViewerKeys(e);
            }
        });
    }

    function handleFullscreenKeys(e) {
        switch (e.key) {
            case 'Escape':
                closeFullscreen();
                break;
            case 'ArrowLeft':
                navigateFullscreen(-1);
                break;
            case 'ArrowRight':
                navigateFullscreen(1);
                break;
        }
    }

    function handleMobileModalKeys(e) {
        switch (e.key) {
            case 'Escape':
                closeMobileModal();
                break;
            case 'ArrowLeft':
                navigateMobileModal(-1);
                break;
            case 'ArrowRight':
                navigateMobileModal(1);
                break;
        }
    }

    function handleMainViewerKeys(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        switch (e.key) {
            case 'ArrowLeft':
                navigateViewer(-1);
                break;
            case 'ArrowRight':
                navigateViewer(1);
                break;
            case ' ':
                e.preventDefault();
                openFullscreen();
                break;
        }
    }

    /**
     * Navegación táctil
     */
    function initTouchNavigation() {
        let startX = 0;
        let endX = 0;

        const handleTouchStart = (e) => {
            startX = e.touches[0].clientX;
        };

        const handleTouchEnd = (e) => {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        };

        const handleSwipe = () => {
            const diff = startX - endX;
            const threshold = 50;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Swipe left - next
                    if (fullscreenModal?.classList.contains('active')) {
                        navigateFullscreen(1);
                    } else if (mobileModal?.classList.contains('active')) {
                        navigateMobileModal(1);
                    } else if (!isMobile) {
                        navigateViewer(1);
                    }
                } else {
                    // Swipe right - previous
                    if (fullscreenModal?.classList.contains('active')) {
                        navigateFullscreen(-1);
                    } else if (mobileModal?.classList.contains('active')) {
                        navigateMobileModal(-1);
                    } else if (!isMobile) {
                        navigateViewer(-1);
                    }
                }
            }
        };

        // Aplicar a elementos relevantes
        const swipeElements = [
            document.querySelector('.main-viewer'),
            fullscreenModal,
            mobileModal
        ].filter(Boolean);

        swipeElements.forEach(element => {
            element.addEventListener('touchstart', handleTouchStart);
            element.addEventListener('touchend', handleTouchEnd);
        });
    }

    /**
     * Utilidades
     */
    function showLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.add('active');
        }
    }

    function hideLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('active');
        }
    }

    // Manejar cambio de tamaño de ventana
    window.addEventListener('resize', () => {
        // CAMBIO: Nuevo breakpoint 1024px
        const newIsMobile = window.innerWidth <= 1024;
        if (newIsMobile !== isMobile) {
            isMobile = newIsMobile;
            // Recargar página para cambiar entre desktop/mobile
            window.location.reload();
        }
    });

    // Optimización: Lazy loading para imágenes
    function setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            // Observar imágenes con data-src
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // Cleanup al salir
    window.addEventListener('beforeunload', () => {
        document.body.style.overflow = '';
    });

    // Inicializar galería
    initGallery();
    setupLazyLoading();

    // Log para debugging
    console.log('Gallery initialized:', {
        isMobile,
        totalPhotos: allPhotos.length,
        filteredPhotos: filteredPhotos.length,
        currentCategory,
        breakpoint: '1024px (changed from 991px)'
    });
});
