document.addEventListener("DOMContentLoaded", function () {
    // Variables globales
    let currentIndex = 0;
    let filteredPhotos = [];
    let currentCategory = "todo";
    let selectedPhotos = new Set();
    let isSelectionMode = false;
    let isGridView = false;
    let isMobile = window.innerWidth <= 991;

    // Elementos del DOM
    const mainViewerImage = document.querySelector("#mainViewerImage");
    const viewerCategory = document.querySelector("#viewerCategory");
    const viewerCounter = document.querySelector("#viewerCounter");
    const photosRow = document.querySelector("#photosRow");
    const photoGrid = document.querySelector("#photoGrid");
    const gridViewSection = document.querySelector("#gridViewSection");
    const toggleGridBtn = document.querySelector("#toggleGridView");
    const selectionBar = document.querySelector("#selectionBar");
    const selectedCountEl = document.querySelector("#selectedCount");
    const fullscreenModal = document.querySelector("#fullscreenModal");
    const mobileModal = document.querySelector("#mobileModal");
    const loadingOverlay = document.querySelector(".loading-overlay");
    const mobilePhotoGrid = document.querySelector("#mobilePhotoGrid");

    // Verificar que los datos existen
    if (
        typeof window.galleryData === "undefined" ||
        !window.galleryData.photos
    ) {
        console.error("Gallery data not found");
        return;
    }

    const allPhotos = window.galleryData.photos;
    filteredPhotos = [...allPhotos];
    currentCategory = window.galleryData.currentCategory || "todo";

    console.log("🎬 Gallery starting...", {
        isMobile,
        photos: allPhotos.length,
        mobilePhotoGrid: !!mobilePhotoGrid,
        currentCategory,
    });

    /**
     * Inicializar la galería
     */
    function initGallery() {
        console.log("🚀 Initializing gallery...");

        if (isMobile) {
            console.log("📱 Initializing mobile gallery");
            initMobileGallery();
        } else {
            console.log("💻 Initializing desktop gallery");
            initDesktopGallery();
        }

        initCategoryFilters();
        initSelectionMode();
        initKeyboardNavigation();
        initTouchNavigation();

        // Filtrar por categoría inicial
        if (currentCategory !== "todo") {
            filterByCategory(currentCategory);
        } else {
            updateAllViews();
        }

        console.log("✅ Gallery initialization complete");
    }

    /**
     * Inicializar galería desktop
     */
    function initDesktopGallery() {
        initMainViewer();
        initPhotosRow();
        initToggleGrid();
        initFullscreenModal();
        updateMainViewer();
        updatePhotosRow();
    }

    /**
     * Inicializar galería móvil
     */
    function initMobileGallery() {
        console.log("📱 Setting up mobile gallery...");

        // Verificar elemento
        if (!mobilePhotoGrid) {
            console.error("❌ Mobile photo grid element not found!");
            return;
        }

        console.log("✅ Mobile photo grid element found");

        initMobileModal();
        createMobileGrid();

        // Debug: verificar visibilidad
        const section = document.querySelector(".mobile-grid-section");
        if (section) {
            const styles = getComputedStyle(section);
            console.log("📐 Mobile section styles:", {
                display: styles.display,
                visibility: styles.visibility,
                opacity: styles.opacity,
            });
        }
    }

    /**
     * Inicializar visualizador principal
     */
    function initMainViewer() {
        if (!mainViewerImage) return;

        const prevBtn = document.querySelector(".viewer-prev");
        const nextBtn = document.querySelector(".viewer-next");
        const fullscreenBtn = document.querySelector(".fullscreen-btn");

        console.log("🎮 Setting up main viewer controls:", {
            prevBtn: !!prevBtn,
            nextBtn: !!nextBtn,
            fullscreenBtn: !!fullscreenBtn,
        });

        if (prevBtn) {
            prevBtn.addEventListener("click", () => {
                console.log("⬅️ Previous button clicked");
                navigateViewer(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", () => {
                console.log("➡️ Next button clicked");
                navigateViewer(1);
            });
        }

        if (fullscreenBtn) {
            fullscreenBtn.addEventListener("click", () => {
                console.log("🔍 Fullscreen button clicked");
                openFullscreen();
            });
        }
    }

    /**
     * Actualizar visualizador principal
     */
    function updateMainViewer() {
        if (!mainViewerImage || filteredPhotos.length === 0) return;

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        mainViewerImage.src = photo.url;
        mainViewerImage.alt = photo.name;

        // AÑADIR: Configurar fondo borroso
        const mainViewerBackground = document.querySelector(
            "#mainViewerBackground"
        );
        if (mainViewerBackground) {
            mainViewerBackground.style.backgroundImage = `url(${photo.url})`;
        }

        // AÑADIR: Detectar si la imagen llena completamente el espacio
        mainViewerImage.onload = function () {
            setTimeout(checkImageFill, 200); // Pequeño delay para que se renderice
        };

        // Si la imagen ya está cargada
        if (mainViewerImage.complete) {
            setTimeout(checkImageFill, 200);
        }

        if (viewerCategory) {
            viewerCategory.textContent = window.getCategoryDisplayName(
                photo.category
            );
        }

        if (viewerCounter) {
            viewerCounter.textContent = `${currentIndex + 1} de ${
                filteredPhotos.length
            }`;
        }
    }

    function checkImageFill() {
        const mainViewerContainer = document.querySelector(".main-viewer");
        const mainViewerImage = document.querySelector("#mainViewerImage");

        if (!mainViewerImage || !mainViewerContainer || !filteredPhotos.length)
            return;

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        // Crear una imagen temporal para obtener dimensiones reales
        const tempImg = new Image();
        tempImg.onload = function () {
            const imageAspectRatio = this.naturalWidth / this.naturalHeight;
            const containerAspectRatio =
                mainViewerContainer.clientWidth /
                mainViewerContainer.clientHeight;

            // Si la imagen es vertical o no llena completamente el contenedor
            if (
                imageAspectRatio < 0.9 ||
                imageAspectRatio < containerAspectRatio * 0.85
            ) {
                mainViewerContainer.classList.add("incomplete-fill");
            } else {
                mainViewerContainer.classList.remove("incomplete-fill");
            }
        };
        tempImg.src = photo.url;
    }

    /**
     * Navegar en el visualizador principal
     */
    function navigateViewer(direction) {
        if (filteredPhotos.length === 0) return;

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
     * Crear fila horizontal de fotos
     */
    function createPhotosRow() {
        if (!photosRow) return;

        photosRow.innerHTML = "";

        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement("div");
            item.className = `row-photo-item ${
                index === currentIndex ? "active" : ""
            }`;
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
            item.addEventListener("click", () => selectPhoto(index));

            const viewBtn = item.querySelector(".view-btn");
            const selectBtn = item.querySelector(".select-btn");

            if (viewBtn) {
                viewBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    // CAMBIO: Activar modo selección en lugar de setActivePhoto
                    if (!isSelectionMode) {
                        toggleSelectionMode();
                    }
                    togglePhotoSelection(photo.id);
                });
            }

            if (selectBtn) {
                selectBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    togglePhotoSelection(photo.id);
                });
            }

            photosRow.appendChild(item);
        });
    }

    /**
     * Inicializar fila de fotos - ARREGLADO
     */
    function initPhotosRow() {
        if (!photosRow) return;

        createPhotosRow();

        // ARREGLADO: Esperar a que el DOM esté listo
        setTimeout(() => {
            const scrollLeft = document.querySelector(".row-scroll-left");
            const scrollRight = document.querySelector(".row-scroll-right");

            console.log("🔧 Setting up scroll buttons:", {
                scrollLeft: !!scrollLeft,
                scrollRight: !!scrollRight,
                photosRow: !!photosRow,
            });

            if (scrollLeft && photosRow) {
                scrollLeft.addEventListener("click", (e) => {
                    e.preventDefault();
                    console.log("⬅️ Scroll left clicked");
                    photosRow.scrollBy({ left: -300, behavior: "smooth" });
                });
            }

            if (scrollRight && photosRow) {
                scrollRight.addEventListener("click", (e) => {
                    e.preventDefault();
                    console.log("➡️ Scroll right clicked");
                    photosRow.scrollBy({ left: 300, behavior: "smooth" });
                });
            }
        }, 100);
    }

    /**
     * Actualizar fila de fotos
     */
    function updatePhotosRow() {
        if (!photosRow) return;

        // Actualizar estados activos
        const items = photosRow.querySelectorAll(".row-photo-item");
        items.forEach((item, index) => {
            item.classList.toggle("active", index === currentIndex);
        });

        // Scroll al elemento activo
        const activeItem = photosRow.querySelector(".row-photo-item.active");
        if (activeItem) {
            activeItem.scrollIntoView({
                behavior: "smooth",
                block: "nearest",
                inline: "center",
            });
        }
    }

    /**
     * Seleccionar foto del row
     */
    function selectPhoto(index) {
        if (isMobile) {
            // En móvil mantener comportamiento original
            openMobileModal(index);
        } else if (isSelectionMode) {
            // Si ya está en modo selección, alternar selección
            togglePhotoSelection(filteredPhotos[index]?.id);
        } else {
            // CAMBIO: En desktop, activar modo selección automáticamente
            toggleSelectionMode();
            togglePhotoSelection(filteredPhotos[index]?.id);
        }
    }

    /**
     * Inicializar toggle de vista grid
     */
    function initToggleGrid() {
        if (!toggleGridBtn || isMobile) return;

        console.log("🔄 Setting up grid toggle");

        toggleGridBtn.addEventListener("click", () => {
            isGridView = !isGridView;
            console.log("🔄 Grid view toggled:", isGridView);

            if (isGridView) {
                // Cambiar a vista Grid
                document.querySelector(".main-viewer-section").style.display =
                    "none";
                document.querySelector(".photos-row-section").style.display =
                    "none";
                gridViewSection.style.display = "block";

                // Cambiar texto a "Visualizador"
                toggleGridBtn.innerHTML =
                    '<i class="fas fa-images"></i><span>Visualizador</span>';
                toggleGridBtn.classList.add("active");

                updatePhotoGrid();
            } else {
                // Cambiar a vista Visualizador
                gridViewSection.style.display = "none";
                document.querySelector(".main-viewer-section").style.display =
                    "block";
                document.querySelector(".photos-row-section").style.display =
                    "block";

                // Cambiar texto a "Grid"
                toggleGridBtn.innerHTML =
                    '<i class="fas fa-th"></i><span>Grid</span>';
                toggleGridBtn.classList.remove("active");

                updateMainViewer();
                updatePhotosRow();
            }
        });
    }

    /**
     * Crear grid de fotos WEB
     */
    function createPhotoGrid() {
        if (!photoGrid) return;

        photoGrid.innerHTML = "";

        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement("div");
            item.className = "grid-photo-item";
            item.dataset.index = index;

            item.innerHTML = `
                <img src="${photo.url}" alt="${photo.name}" loading="lazy">
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

            // Event listeners
            item.addEventListener("click", () => selectPhoto(index));

            const viewBtn = item.querySelector(".view-btn");
            const selectBtn = item.querySelector(".select-btn");

            if (viewBtn) {
                viewBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    if (isMobile) {
                        openMobileModal(index);
                    } else {
                        // CAMBIO: Activar modo selección en lugar de fullscreen
                        if (!isSelectionMode) {
                            toggleSelectionMode();
                        }
                        togglePhotoSelection(photo.id);
                    }
                });
            }

            if (selectBtn) {
                selectBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    togglePhotoSelection(photo.id);
                });
            }

            photoGrid.appendChild(item);
        });
    }

    /**
     * Actualizar grid de fotos WEB
     */
    function updatePhotoGrid() {
        createPhotoGrid();
        updateSelectionStates();
    }

    /**
     * Crear grid móvil - ARREGLADO CON DEBUG
     */
    function createMobileGrid() {
        console.log("📱 Creating mobile grid...");

        if (!mobilePhotoGrid) {
            console.error("❌ Mobile photo grid element not found");
            return;
        }

        console.log("📱 Grid element found, photos:", filteredPhotos.length);

        // Limpiar grid
        mobilePhotoGrid.innerHTML = "";

        if (filteredPhotos.length === 0) {
            console.warn("⚠️ No photos to display");
            return;
        }

        // Crear elementos
        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement("div");
            item.className = "mobile-grid-item";
            item.dataset.index = index;

            item.innerHTML = `
                <img src="${photo.url}" alt="${photo.name}" loading="lazy" onerror="console.error('Image failed to load:', this.src)">
                <div class="mobile-grid-overlay">
                    <div class="mobile-grid-actions">
                        <button class="mobile-grid-action-btn view-btn" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="${photo.downloadUrl}" class="mobile-grid-action-btn download-btn" title="Descargar">
                            <i class="fas fa-download"></i>
                        </a>
                        <button class="mobile-grid-action-btn select-btn" title="Seleccionar">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
                <div class="selection-indicator">
                    <i class="fas fa-check"></i>
                </div>
            `;

            // Event listeners
            item.addEventListener("click", () => {
                console.log("📱 Mobile grid item clicked:", index);
                if (isSelectionMode) {
                    togglePhotoSelection(photo.id);
                } else {
                    openMobileModal(index);
                }
            });

            const viewBtn = item.querySelector(".view-btn");
            const selectBtn = item.querySelector(".select-btn");

            if (viewBtn) {
                viewBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    console.log("📱 View button clicked:", index);
                    openMobileModal(index);
                });
            }

            if (selectBtn) {
                selectBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    console.log("📱 Select button clicked:", photo.id);
                    togglePhotoSelection(photo.id);
                });
            }

            mobilePhotoGrid.appendChild(item);
        });

        console.log(
            "✅ Mobile grid created with",
            mobilePhotoGrid.children.length,
            "items"
        );

        // Debug: verificar que los elementos están en el DOM
        setTimeout(() => {
            const items = document.querySelectorAll(".mobile-grid-item");
            console.log("🔍 Mobile grid items in DOM:", items.length);

            if (items.length > 0) {
                const firstItem = items[0];
                const styles = getComputedStyle(firstItem);
                console.log("📐 First item styles:", {
                    display: styles.display,
                    width: styles.width,
                    height: styles.height,
                    visibility: styles.visibility,
                });
            }
        }, 100);
    }

    /**
     * Actualizar grid móvil
     */
    function updateMobileGrid() {
        createMobileGrid();
        updateSelectionStates();
    }

    /**
     * Inicializar filtros de categoría
     */
    function initCategoryFilters() {
        // Filtros web
        const webFilters = document.querySelectorAll(".filter-btn");
        webFilters.forEach((btn) => {
            btn.addEventListener("click", () => {
                const category = btn.dataset.category;
                console.log("🏷️ Category filter clicked:", category);
                filterByCategory(category);
                updateFilterStates(category);
            });
        });

        // Filtros móvil
        const mobileFilters = document.querySelectorAll(".filter-btn-mobile");
        mobileFilters.forEach((btn) => {
            btn.addEventListener("click", () => {
                const category = btn.dataset.category;
                console.log("📱 Mobile category filter clicked:", category);
                filterByCategory(category);
                updateFilterStates(category);
            });
        });
    }

    /**
     * Filtrar por categoría
     */
    function filterByCategory(category) {
        currentCategory = category;
        currentIndex = 0;

        if (category === "todo") {
            filteredPhotos = [...allPhotos];
        } else {
            filteredPhotos = allPhotos.filter(
                (photo) => photo.category === category
            );
        }

        console.log(
            "🏷️ Filtered to category:",
            category,
            "Photos:",
            filteredPhotos.length
        );

        updateAllViews();
        updateURL(category);
    }

    /**
     * Actualizar estados de filtros
     */
    function updateFilterStates(activeCategory) {
        // Filtros web
        document.querySelectorAll(".filter-btn").forEach((btn) => {
            btn.classList.toggle(
                "active",
                btn.dataset.category === activeCategory
            );
        });

        // Filtros móvil
        document.querySelectorAll(".filter-btn-mobile").forEach((btn) => {
            btn.classList.toggle(
                "active",
                btn.dataset.category === activeCategory
            );
        });
    }

    /**
     * Actualizar URL sin recargar página
     */
    function updateURL(category) {
        const url = new URL(window.location);
        if (category === "todo") {
            url.searchParams.delete("category");
        } else {
            url.searchParams.set("category", category);
        }
        window.history.pushState({}, "", url);
    }

    /**
     * Actualizar todas las vistas
     */
    function updateAllViews() {
        console.log("🔄 Updating all views...");

        if (isMobile) {
            console.log("📱 Updating mobile grid");
            updateMobileGrid();
        } else {
            console.log("💻 Updating desktop views");
            updateMainViewer();
            updatePhotosRow();
            if (isGridView) {
                updatePhotoGrid();
            }
        }
    }

    /**
     * Inicializar modo selección
     */
    function initSelectionMode() {
        const selectModeBtn = document.querySelector("#selectModeBtn");
        const selectModeBtnMobile = document.querySelector(
            "#selectModeBtnMobile"
        );
        const cancelBtn = document.querySelector("#cancelSelectionBtn");
        const selectAllBtn = document.querySelector("#selectAllBtn");
        const deselectAllBtn = document.querySelector("#deselectAllBtn");
        const downloadSelectedBtn = document.querySelector(
            "#downloadSelectedBtn"
        );

        if (selectModeBtn) {
            selectModeBtn.addEventListener("click", toggleSelectionMode);
        }

        if (selectModeBtnMobile) {
            selectModeBtnMobile.addEventListener("click", toggleSelectionMode);
        }

        if (cancelBtn) {
            cancelBtn.addEventListener("click", exitSelectionMode);
        }

        if (selectAllBtn) {
            selectAllBtn.addEventListener("click", selectAllPhotos);
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener("click", deselectAllPhotos);
        }

        if (downloadSelectedBtn) {
            downloadSelectedBtn.addEventListener(
                "click",
                downloadSelectedPhotos
            );
        }
    }

    /**
     * Toggle modo selección
     */
    function toggleSelectionMode() {
        isSelectionMode = !isSelectionMode;
        console.log("✅ Selection mode toggled:", isSelectionMode);

        document.body.classList.toggle("selection-mode", isSelectionMode);
        if (selectionBar) {
            selectionBar.classList.toggle("active", isSelectionMode);
        }

        if (!isSelectionMode) {
            selectedPhotos.clear();
            updateSelectionStates();
        }

        updateSelectionCounter();
    }

    /**
     * Salir del modo selección
     */
    function exitSelectionMode() {
        isSelectionMode = false;
        selectedPhotos.clear();

        document.body.classList.remove("selection-mode");
        if (selectionBar) {
            selectionBar.classList.remove("active");
        }

        updateSelectionStates();
        updateSelectionCounter();
    }

    /**
     * Seleccionar todas las fotos
     */
    function selectAllPhotos() {
        filteredPhotos.forEach((photo) => {
            selectedPhotos.add(photo.id);
        });
        updateSelectionStates();
        updateSelectionCounter();
    }

    /**
     * Deseleccionar todas las fotos
     */
    function deselectAllPhotos() {
        selectedPhotos.clear();
        updateSelectionStates();
        updateSelectionCounter();
    }

    /**
     * Toggle selección de foto
     */
    function togglePhotoSelection(photoId) {
        if (selectedPhotos.has(photoId)) {
            selectedPhotos.delete(photoId);
        } else {
            selectedPhotos.add(photoId);
        }
        updateSelectionStates();
        updateSelectionCounter();
    }

    /**
     * Actualizar estados de selección
     */
    function updateSelectionStates() {
        // Actualizar elementos en la fila
        document.querySelectorAll(".row-photo-item").forEach((item) => {
            const index = parseInt(item.dataset.index);
            const photo = filteredPhotos[index];
            if (photo) {
                item.classList.toggle("selected", selectedPhotos.has(photo.id));
            }
        });

        // Actualizar elementos en el grid web
        document.querySelectorAll(".grid-photo-item").forEach((item) => {
            const index = parseInt(item.dataset.index);
            const photo = filteredPhotos[index];
            if (photo) {
                item.classList.toggle("selected", selectedPhotos.has(photo.id));
            }
        });

        // Actualizar elementos en grid móvil
        document.querySelectorAll(".mobile-grid-item").forEach((item) => {
            const index = parseInt(item.dataset.index);
            const photo = filteredPhotos[index];
            if (photo) {
                item.classList.toggle("selected", selectedPhotos.has(photo.id));
            }
        });
    }

    /**
     * Actualizar contador de selección
     */
    function updateSelectionCounter() {
        if (selectedCountEl) {
            selectedCountEl.textContent = selectedPhotos.size;
        }
    }

    /**
     * Descargar fotos seleccionadas
     */
    function downloadSelectedPhotos() {
        if (selectedPhotos.size === 0) return;

        const selectedIds = Array.from(selectedPhotos);
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "/gallery/download-selected";
        form.style.display = "none";

        // CSRF token
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        form.appendChild(csrfInput);

        // IDs seleccionados
        selectedIds.forEach((id) => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "photo_ids[]";
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    /**
     * Inicializar modal fullscreen - ARREGLADO
     */
    function initFullscreenModal() {
        if (!fullscreenModal) {
            console.warn("⚠️ Fullscreen modal not found");
            return;
        }

        console.log("🔍 Setting up fullscreen modal");

        const closeBtn = fullscreenModal.querySelector(".fullscreen-close");
        const prevBtn = fullscreenModal.querySelector(".fullscreen-prev");
        const nextBtn = fullscreenModal.querySelector(".fullscreen-next");
        const downloadBtn = fullscreenModal.querySelector(
            "#fullscreenDownload"
        );

        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                console.log("❌ Fullscreen close clicked");
                closeFullscreen();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener("click", () => {
                console.log("⬅️ Fullscreen prev clicked");
                navigateFullscreen(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", () => {
                console.log("➡️ Fullscreen next clicked");
                navigateFullscreen(1);
            });
        }

        if (downloadBtn) {
            downloadBtn.addEventListener("click", () => {
                console.log("💾 Fullscreen download clicked");
                downloadCurrentPhoto();
            });
        }

        // Cerrar con ESC
        document.addEventListener("keydown", (e) => {
            if (
                e.key === "Escape" &&
                fullscreenModal.classList.contains("active")
            ) {
                console.log("⌨️ ESC pressed - closing fullscreen");
                closeFullscreen();
            }
        });

        // Cerrar al hacer clic fuera
        fullscreenModal.addEventListener("click", (e) => {
            if (e.target === fullscreenModal) {
                console.log("🖱️ Clicked outside - closing fullscreen");
                closeFullscreen();
            }
        });
    }

    /**
     * Abrir modal fullscreen - ARREGLADO
     */
    function openFullscreen(index = null) {
        if (!fullscreenModal) {
            console.error("❌ Fullscreen modal not available");
            return;
        }

        if (index !== null) {
            currentIndex = index;
        }

        const photo = filteredPhotos[currentIndex];
        if (!photo) {
            console.error("❌ No photo found for index:", currentIndex);
            return;
        }

        console.log("🔍 Opening fullscreen for photo:", currentIndex);

        const image = fullscreenModal.querySelector(".fullscreen-image");
        const title = fullscreenModal.querySelector(".fullscreen-title");
        const category = fullscreenModal.querySelector(".fullscreen-category");
        const position = fullscreenModal.querySelector("#fullscreenPosition");
        const total = fullscreenModal.querySelector("#fullscreenTotal");
        const downloadBtn = fullscreenModal.querySelector(
            "#fullscreenDownload"
        );

        if (image) image.src = photo.url;
        if (title) title.textContent = photo.name;
        if (category)
            category.textContent = window.getCategoryDisplayName(
                photo.category
            );
        if (position) position.textContent = currentIndex + 1;
        if (total) total.textContent = filteredPhotos.length;
        if (downloadBtn) downloadBtn.href = photo.downloadUrl;

        fullscreenModal.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    /**
     * Cerrar modal fullscreen
     */
    function closeFullscreen() {
        if (!fullscreenModal) return;

        fullscreenModal.classList.remove("active");
        document.body.style.overflow = "";
    }

    /**
     * Navegar en fullscreen
     */
    function navigateFullscreen(direction) {
        if (filteredPhotos.length === 0) return;

        currentIndex += direction;

        if (currentIndex >= filteredPhotos.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = filteredPhotos.length - 1;
        }

        openFullscreen();
        updatePhotosRow();
    }

    /**
     * Inicializar modal móvil
     */
    function initMobileModal() {
        if (!mobileModal) return;

        console.log("📱 Setting up mobile modal");

        const closeBtn = mobileModal.querySelector(".mobile-close-btn");
        const prevBtn = mobileModal.querySelector(".mobile-prev");
        const nextBtn = mobileModal.querySelector(".mobile-next");
        const downloadBtn = mobileModal.querySelector(".mobile-download-btn");

        if (closeBtn) {
            closeBtn.addEventListener("click", closeMobileModal);
        }

        if (prevBtn) {
            prevBtn.addEventListener("click", () => navigateMobileModal(-1));
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", () => navigateMobileModal(1));
        }

        if (downloadBtn) {
            downloadBtn.addEventListener("click", downloadCurrentPhoto);
        }
    }

    /**
     * Abrir modal móvil
     */
    function openMobileModal(index = null) {
        if (!mobileModal) return;

        if (index !== null) {
            currentIndex = index;
        }

        const photo = filteredPhotos[currentIndex];
        if (!photo) return;

        console.log("📱 Opening mobile modal for photo:", currentIndex);

        const image = mobileModal.querySelector(".mobile-modal-image");
        const title = mobileModal.querySelector(".mobile-modal-title");
        const category = mobileModal.querySelector(".mobile-modal-category");
        const position = mobileModal.querySelector("#mobilePosition");
        const total = mobileModal.querySelector("#mobileTotal");

        if (image) image.src = photo.url;
        if (title) title.textContent = photo.name;
        if (category)
            category.textContent = window.getCategoryDisplayName(
                photo.category
            );
        if (position) position.textContent = currentIndex + 1;
        if (total) total.textContent = filteredPhotos.length;

        createMobileThumbnails();
        mobileModal.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    /**
     * Cerrar modal móvil
     */
    function closeMobileModal() {
        if (!mobileModal) return;

        mobileModal.classList.remove("active");
        document.body.style.overflow = "";
    }

    /**
     * Navegar en modal móvil
     */
    function navigateMobileModal(direction) {
        if (filteredPhotos.length === 0) return;

        currentIndex += direction;

        if (currentIndex >= filteredPhotos.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = filteredPhotos.length - 1;
        }

        openMobileModal();
    }

    /**
     * Crear thumbnails para móvil
     */
    function createMobileThumbnails() {
        const track = document.querySelector("#mobileThumbnailsTrack");
        if (!track) return;

        track.innerHTML = "";

        filteredPhotos.forEach((photo, index) => {
            const thumb = document.createElement("div");
            thumb.className = `mobile-thumb-item ${
                index === currentIndex ? "active" : ""
            }`;
            thumb.innerHTML = `<img src="${
                photo.thumbnailUrl || photo.url
            }" alt="${photo.name}">`;

            thumb.addEventListener("click", () => {
                currentIndex = index;
                openMobileModal();
            });

            track.appendChild(thumb);
        });
    }

    /**
     * Descargar foto actual
     */
    function downloadCurrentPhoto() {
        const photo = filteredPhotos[currentIndex];
        if (photo && photo.downloadUrl) {
            console.log("💾 Downloading photo:", photo.name);
            window.open(photo.downloadUrl, "_blank");
        }
    }

    /**
     * Inicializar navegación por teclado
     */
    function initKeyboardNavigation() {
        document.addEventListener("keydown", (e) => {
            if (
                fullscreenModal &&
                fullscreenModal.classList.contains("active")
            ) {
                switch (e.key) {
                    case "ArrowLeft":
                        e.preventDefault();
                        navigateFullscreen(-1);
                        break;
                    case "ArrowRight":
                        e.preventDefault();
                        navigateFullscreen(1);
                        break;
                    case "Escape":
                        closeFullscreen();
                        break;
                }
            } else if (!isMobile) {
                switch (e.key) {
                    case "ArrowLeft":
                        e.preventDefault();
                        navigateViewer(-1);
                        break;
                    case "ArrowRight":
                        e.preventDefault();
                        navigateViewer(1);
                        break;
                }
            }
        });
    }

    /**
     * Inicializar navegación táctil
     */
    function initTouchNavigation() {
        if (!isMobile) return;

        let startX = 0;
        let startY = 0;

        document.addEventListener("touchstart", (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        document.addEventListener("touchend", (e) => {
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const diffX = startX - endX;
            const diffY = startY - endY;

            // Solo si el movimiento horizontal es mayor que el vertical
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    navigateViewer(1); // Swipe left - next
                } else {
                    navigateViewer(-1); // Swipe right - previous
                }
            }
        });
    }

    /**
     * Configurar lazy loading de imágenes
     */
    function setupLazyLoading() {
        if ("IntersectionObserver" in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute("data-src");
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            // Observar imágenes con data-src
            document.querySelectorAll("img[data-src]").forEach((img) => {
                imageObserver.observe(img);
            });
        }
    }

    // Cleanup al salir
    window.addEventListener("beforeunload", () => {
        document.body.style.overflow = "";
    });

    // INICIALIZAR TODO
    initGallery();
    setupLazyLoading();

    // Debug final
    setTimeout(() => {
        console.log("🎯 Final gallery state:", {
            isMobile,
            totalPhotos: allPhotos.length,
            filteredPhotos: filteredPhotos.length,
            currentCategory,
            mobilePhotoGrid: !!mobilePhotoGrid,
            mobileGridVisible: isMobile
                ? mobilePhotoGrid
                    ? getComputedStyle(mobilePhotoGrid.parentElement).display
                    : "N/A"
                : "N/A",
            mobileGridItems: isMobile
                ? document.querySelectorAll(".mobile-grid-item").length
                : "N/A",
        });
    }, 500);
});
