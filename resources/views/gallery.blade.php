@extends('layouts.app')

@section('content')
    <div class="gallery-wrapper">
        <div class="gallery-container">
            <!-- Header de la galería -->
            <div class="gallery-header">
                <div class="gallery-intro">
                    <p class="gallery-subtitle">Revive los momentos más especiales de nuestro gran día</p>
                </div>
            </div>

            @if ($photos->count() > 0)
                <!-- Filtros móvil - ARRIBA del grid -->
                <div class="category-filters-mobile">
                    <button class="filter-btn-mobile {{ $category == 'todo' ? 'active' : '' }}" data-category="todo">
                        <i class="fas fa-images"></i>
                        <span class="count">{{ $photos->count() }}</span>
                    </button>
                    @foreach ($categories as $cat)
                        @php $catCount = $photos->where('category', $cat)->count(); @endphp
                        <button class="filter-btn-mobile {{ $category == $cat ? 'active' : '' }}"
                            data-category="{{ $cat }}">
                            <i class="fas fa-{{ getCategoryIcon($cat) }}"></i>
                            <span class="count">{{ $catCount }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Controles móvil - TAMBIÉN ARRIBA -->
                <div class="mobile-controls">
                    <button id="selectModeBtnMobile" class="mobile-control-btn">
                        <i class="fas fa-check-square"></i>
                    </button>
                    <a href="{{ route('gallery.downloadAll', ['category' => $category]) }}" class="mobile-control-btn">
                        <i class="fas fa-download"></i>
                    </a>
                </div>

                <!-- Grid móvil - Solo visible en móvil -->
                <section class="mobile-grid-section">
                    <div class="mobile-photo-grid" id="mobilePhotoGrid">
                        <!-- Las fotos se insertan dinámicamente -->
                    </div>
                </section>

                <!-- CONTENEDOR WEB: Filtros + Toggle en la misma línea -->
                <div class="gallery-web-header">
                    <!-- Filtros de categoría -->

                    <!-- Contenedor de filtros con toggle a la derecha -->
                    <div class="category-filters-web">
                        <!-- Filtros de categoría centrados -->
                        <button class="filter-btn {{ $category == 'todo' ? 'active' : '' }}" data-category="todo">
                            <i class="fas fa-images"></i>
                            <span>Todas</span>
                            <span class="count">{{ $photos->count() }}</span>
                        </button>
                        @foreach ($categories as $cat)
                            @php $catCount = $photos->where('category', $cat)->count(); @endphp
                            <button class="filter-btn {{ $category == $cat ? 'active' : '' }}"
                                data-category="{{ $cat }}">
                                <i class="fas fa-{{ getCategoryIcon($cat) }}"></i>
                                <span>{{ getCategoryDisplayName($cat) }}</span>
                                <span class="count">{{ $catCount }}</span>
                            </button>
                        @endforeach

                        <!-- Toggle para vista Grid - POSICIONADO A LA DERECHA -->
                        <div class="view-toggle">
                            <button id="toggleGridView" class="toggle-btn">
                                <i class="fas fa-th"></i>
                                <span>Galeria</span>
                            </button>
                        </div>
                    </div>

                    <!-- Controles superiores WEB -->
                    <div class="gallery-top-controls">
                        <button id="selectModeBtn" class="control-btn">
                            <i class="fas fa-check-square"></i>
                            <span>Seleccionar fotos</span>
                        </button>
                        <a href="{{ route('gallery.downloadAll', ['category' => $category]) }}" class="control-btn">
                            <i class="fas fa-download"></i>
                            <span>Descargar todas</span>
                        </a>
                    </div>

                    <!-- Visualizador principal WEB -->
                    <section class="main-viewer-section">
                        <div class="main-viewer-container">
                            <div class="main-viewer">
                                <!-- AÑADIR esta línea para el fondo borroso -->
                                <div class="main-viewer-background" id="mainViewerBackground"></div>

                                <img id="mainViewerImage" src="" alt="" class="main-viewer-image">

                                <!-- Controles del visualizador (sin cambios) -->
                                <button class="viewer-nav viewer-prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="viewer-nav viewer-next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>

                                <!-- Botón pantalla completa (sin cambios) -->
                                <button class="fullscreen-btn" title="Pantalla completa">
                                    <i class="fas fa-expand"></i>
                                </button>

                                <!-- Info de la imagen (sin cambios) -->

                            </div>
                        </div>
                    </section>

                    <!-- Fila horizontal de fotos WEB -->
                    <section class="photos-row-section">
                        <div class="photos-row-container">
                            <button class="row-scroll-btn row-scroll-left">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <div class="photos-row" id="photosRow">
                                <!-- Las fotos se insertan dinámicamente -->
                            </div>

                            <button class="row-scroll-btn row-scroll-right">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </section>

                    <!-- Vista Grid WEB -->
                    <section class="grid-view-section" id="gridViewSection" style="display: none;">
                        <div class="photo-grid" id="photoGrid">
                            <!-- Las fotos se insertan dinámicamente -->
                        </div>
                    </section>

                    <!-- Barra de selección múltiple -->
                    <div id="selectionBar" class="selection-bar">
                        <div class="selection-info">
                            <span id="selectedCount">0</span> fotos elegidas
                        </div>
                        <div class="selection-actions">
                            <button id="selectAllBtn" class="selection-btn">
                                <i class="fas fa-check-double"></i>
                                Elegir todas
                            </button>
                            <button id="deselectAllBtn" class="selection-btn">
                                <i class="fas fa-times"></i>
                                Quitar todas
                            </button>
                            <button id="downloadSelectedBtn" class="selection-btn primary">
                                <i class="fas fa-download"></i>
                                Descargar elegidas
                            </button>
                            <button id="cancelSelectionBtn" class="selection-btn cancel">
                                Cancelar
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="empty-gallery">
                        <i class="fas fa-images"></i>
                        <h3>No hay fotos disponibles</h3>
                        <p>{{ $category != 'todo' ? 'No hay fotos en esta categoría' : 'Aún no se han subido fotos.' }}</p>
                        @if ($category != 'todo')
                            <button class="filter-btn" data-category="todo">Ver todas las fotos</button>
                        @endif
                    </div>
            @endif
        </div>
    </div>

    <!-- Modal para desktop - pantalla completa -->
    <div id="fullscreenModal" class="fullscreen-modal">
        <div class="fullscreen-container">
            <div class="fullscreen-header">
                <div class="fullscreen-info">
                    <h3 class="fullscreen-title"></h3>
                    <p class="fullscreen-category"></p>
                </div>
                <div class="fullscreen-controls">
                    <button id="fullscreenDownload" class="fullscreen-btn" title="Descargar">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="fullscreen-btn fullscreen-close" title="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="fullscreen-content">
                <img class="fullscreen-image" src="" alt="">

                <button class="fullscreen-nav fullscreen-prev" title="Anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="fullscreen-nav fullscreen-next" title="Siguiente">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <div class="fullscreen-counter">
                    <span id="fullscreenPosition">1</span> de <span id="fullscreenTotal">{{ $photos->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para móvil -->
    <div id="mobileModal" class="mobile-modal">
        <div class="mobile-modal-header">
            <div class="mobile-modal-info">
                <span class="mobile-modal-title"></span>
                <span class="mobile-modal-category"></span>
            </div>
            <div class="mobile-modal-controls">
                <button class="mobile-modal-btn mobile-download-btn" title="Descargar">
                    <i class="fas fa-download"></i>
                </button>
                <button class="mobile-modal-btn mobile-close-btn" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="mobile-modal-content">
            <img class="mobile-modal-image" src="" alt="">

            <button class="mobile-nav mobile-prev" title="Anterior">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="mobile-nav mobile-next" title="Siguiente">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="mobile-counter">
                <span id="mobilePosition">1</span> de <span id="mobileTotal">{{ $photos->count() }}</span>
            </div>
        </div>

        <!-- Thumbnails inferiores para móvil -->
        <div class="mobile-thumbnails">
            <div class="mobile-thumbnails-track" id="mobileThumbnailsTrack">
                <!-- Se generan dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Cargando fotos...</p>
        </div>
    </div>

    <!-- Datos para JavaScript -->
    <script>
        window.galleryData = {
            photos: [
                @foreach ($photos as $index => $photo)
                    {
                        id: {{ $photo->id }},
                        url: "{{ $photo->image_url }}",
                        thumbnailUrl: "{{ $photo->thumbnail_url }}",
                        downloadUrl: "{{ route('gallery.download', $photo->id) }}",
                        name: "{{ addslashes($photo->name) }}",
                        category: "{{ $photo->category }}",
                        dimensions: "{{ $photo->dimensions ?? '' }}",
                        filesize: "{{ $photo->formatted_filesize ?? '' }}",
                        index: {{ $index }}
                    }
                    {{ !$loop->last ? ',' : '' }}
                @endforeach
            ],
            currentCategory: '{{ $category }}',
            totalPhotos: {{ $photos->count() }},
            categories: {!! json_encode($categories) !!}
        };

        // Helper functions
        window.getCategoryIcon = function(category) {
            const icons = {
                'ceremonia': 'church',
                'bienvenida': 'glass-cheers',
                'banquete': 'utensils',
                'fiesta': 'music',
                'fotomaton': 'camera',
                'preboda': 'heart',
                'todo': 'images'
            };
            return icons[category] || 'image';
        };

        window.getCategoryDisplayName = function(category) {
            const names = {
                'ceremonia': 'Ceremonia',
                'bienvenida': 'Bienvenida',
                'banquete': 'Banquete',
                'fiesta': 'Fiesta',
                'fotomaton': 'Fotomatón',
                'preboda': 'Preboda',
                'todo': 'Todas'
            };
            return names[category] || category.charAt(0).toUpperCase() + category.slice(1);
        };
    </script>

    @push('scripts')
        <script src="{{ asset('js/gallery.js') }}"></script>
    @endpush
@endsection
