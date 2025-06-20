@extends('layouts.app')

@section('content')
<div class="gallery-wrapper">
    <div class="container">
        <!-- Header de la galería simplificado -->
        <div class="gallery-header">
            <div class="gallery-intro">
                <p class="gallery-subtitle">Revive los momentos más especiales de nuestro gran día</p>
            </div>
        </div>

        <!-- Controles de la galería -->
        <div class="gallery-controls">
            <!-- Filtros por categoría -->
            <div class="category-filters">
                <button class="filter-btn {{ $category == 'todo' ? 'active' : '' }}" data-category="todo">
                    <i class="fas fa-images"></i>
                    <span>Todas</span>
                    <span class="count">{{ $photos->count() }}</span>
                </button>
                @foreach($categories as $cat)
                    @php $catCount = $photos->where('category', $cat)->count(); @endphp
                    <button class="filter-btn {{ $category == $cat ? 'active' : '' }}" data-category="{{ $cat }}">
                        <i class="fas fa-{{ getCategoryIcon($cat) }}"></i>
                        <span>{{ ucfirst($cat) }}</span>
                        <span class="count">{{ $catCount }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Controles de vista y acciones simplificados -->
            <div class="view-controls">
                <div class="action-buttons">
                    <button id="selectModeBtn" class="action-btn">
                        <i class="fas fa-check-square"></i>
                        <span>Seleccionar fotos</span>
                    </button>
                    <a href="{{ route('gallery.downloadAll', ['category' => $category]) }}" class="action-btn download-all">
                        <i class="fas fa-download"></i>
                        <span>Descargar todas</span>
                    </a>
                </div>
            </div>
        </div>

        @if($photos->count() > 0)
            <!-- Galería principal - Solo Masonry -->
            <div class="gallery-container">
                <div id="photoGallery" class="photo-gallery">
                    @foreach($photos as $index => $photo)
                        <div class="gallery-photo"
                             data-index="{{ $index }}"
                             data-category="{{ $photo->category }}"
                             data-photo-id="{{ $photo->id }}"
                             data-photo-url="{{ $photo->image_url }}"
                             data-download-url="{{ route('gallery.download', $photo->id) }}">

                            <div class="photo-wrapper">
                                <img src="{{ $photo->image_url }}"
                                     alt="{{ $photo->name }}"
                                     loading="lazy"
                                     class="gallery-image">

                                <div class="photo-overlay">
                                    <div class="photo-info">
                                        <span class="photo-category">{{ ucfirst($photo->category) }}</span>
                                    </div>

                                    <div class="photo-actions">
                                        <button class="action-btn-small view-btn-small" data-action="view" title="Ver imagen">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('gallery.download', $photo->id) }}" class="action-btn-small download-btn-small" title="Descargar">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="action-btn-small select-btn-small" data-action="select" title="Seleccionar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="selection-indicator">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

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
                    <button id="cancelSelectionBtn" class="selection-btn secondary">
                        Cancelar
                    </button>
                </div>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h3 class="empty-title">No hay fotos disponibles</h3>
                <p class="empty-description">
                    {{ $category != 'todo' ? 'No hay fotos en esta categoría' : 'Aún no se han subido fotos.' }}
                </p>
                @if($category != 'todo')
                    <button class="button" data-category="todo">Ver todas las fotos</button>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Modal de vista completa mejorado -->
<div id="lightboxModal" class="lightbox-modal">
    <div class="lightbox-container">
        <!-- Header del modal -->
        <div class="lightbox-header">
            <div class="lightbox-info">
                <h3 id="lightboxTitle" class="lightbox-title"></h3>
                <p id="lightboxCategory" class="lightbox-category"></p>
            </div>
            <div class="lightbox-controls">
                <button id="lightboxDownload" class="lightbox-btn download-btn">
                    <i class="fas fa-download"></i>
                </button>
                <button id="lightboxClose" class="lightbox-btn close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Contenedor de imagen -->
        <div class="lightbox-content">
            <img id="lightboxImage" src="" alt="" class="lightbox-image">

            <!-- Controles de navegación -->
            <button id="lightboxPrev" class="lightbox-nav prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="lightboxNext" class="lightbox-nav next">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Indicador de posición -->
            <div class="lightbox-counter">
                <span id="lightboxPosition">1</span> de <span id="lightboxTotal">{{ $photos->count() }}</span>
            </div>
        </div>

        <!-- Thumbnails de navegación -->
        <div class="lightbox-thumbnails">
            <div class="thumbnails-track" id="thumbnailsTrack">
                @foreach($photos as $index => $photo)
                    <div class="thumbnail-item {{ $index == 0 ? 'active' : '' }}"
                         data-index="{{ $index }}">
                        <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->name }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="galleryLoading" class="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p>Cargando fotos...</p>
    </div>
</div>

<!-- Datos para JavaScript -->
<script>
    window.galleryData = {
        photos: [
            @foreach($photos as $index => $photo)
                {
                    id: {{ $photo->id }},
                    url: "{{ $photo->image_url }}",
                    thumbnailUrl: "{{ $photo->thumbnail_url }}",
                    downloadUrl: "{{ route('gallery.download', $photo->id) }}",
                    name: "{{ $photo->name }}",
                    category: "{{ $photo->category }}",
                    dimensions: "{{ $photo->dimensions ?? '' }}",
                    filesize: "{{ $photo->formatted_filesize ?? '' }}",
                    index: {{ $index }}
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        currentCategory: '{{ $category }}',
        totalPhotos: {{ $photos->count() }},
        categories: {!! json_encode($categories) !!}
    };

    // Helper function para iconos de categorías
    function getCategoryIcon(category) {
        const icons = {
            'ceremonia': 'church',
            'bienvenida': 'glass-cheers',
            'banquete': 'utensils',
            'fiesta': 'music',
            'fotomaton': 'camera',
            'preboda': 'heart'
        };
        return icons[category] || 'image';
    }
</script>

@push('scripts')
    <script src="{{ asset('js/gallery.js') }}"></script>
@endpush
@endsection
