@extends('layouts.app')

@section('content')
<div class="gallery-wrapper">
    <div class="container">
        <!-- Controles de la galería -->
        <div class="gallery-controls">
            <!-- Filtros por categoría -->
            <div class="category-filters">
                <button class="filter-btn {{ $category == 'todo' ? 'active' : '' }}"
                        data-category="todo">Todo</button>
                @foreach($categories as $cat)
                    <button class="filter-btn {{ $category == $cat ? 'active' : '' }}"
                            data-category="{{ $cat }}">{{ ucfirst($cat) }}</button>
                @endforeach
            </div>

            <!-- Controles de vista y descarga -->
            <div class="view-controls">
                <button id="toggleView" class="button view-toggle">
                    <i class="fas fa-th"></i> Vista Grid
                </button>
                <a href="{{ route('gallery.downloadAll', ['category' => $category]) }}"
                   class="button download-all">
                    <i class="fas fa-download"></i> Descargar Todo
                </a>
            </div>
        </div>

        @if($photos->count() > 0)
            <!-- Vista Carrusel (por defecto) -->
            <div id="carouselView" class="gallery-carousel-view">
                <!-- Imagen principal -->
                <div class="main-image-container">
                    <img id="mainImage" src="{{ $photos->first()->image_url }}" alt="{{ $photos->first()->name }}">
                    <div class="image-controls">
                        <button class="nav-btn prev-btn" id="prevMain">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn next-btn" id="nextMain">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <a href="{{ route('gallery.download', $photos->first()->id) }}"
                           class="download-btn" id="downloadBtn">
                            <i class="fas fa-download"></i>
                        </a>
                        <button class="fullscreen-btn" id="fullscreenBtn">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>

                <!-- Thumbnails -->
                <div class="thumbnails-container">
                    <div class="thumbnails-scroll" id="thumbnailsScroll">
                        @foreach($photos as $index => $photo)
                            <div class="thumbnail {{ $index == 0 ? 'active' : '' }}"
                                 data-index="{{ $index }}"
                                 data-photo-id="{{ $photo->id }}"
                                 data-photo-url="{{ $photo->image_url }}"
                                 data-thumbnail-url="{{ $photo->thumbnail_url }}"
                                 data-download-url="{{ route('gallery.download', $photo->id) }}">
                                <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->name }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                    <button class="scroll-btn scroll-left" id="scrollLeft">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="scroll-btn scroll-right" id="scrollRight">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Vista Grid (oculta por defecto) -->
            <div id="gridView" class="gallery-grid-view" style="display: none;">
                <div class="photos-grid">
                    @foreach($photos as $photo)
                        <div class="grid-photo"
                             data-photo-url="{{ $photo->image_url }}"
                             data-thumbnail-url="{{ $photo->thumbnail_url }}"
                             data-photo-id="{{ $photo->id }}"
                             data-download-url="{{ route('gallery.download', $photo->id) }}">
                            <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->name }}" loading="lazy">
                            <div class="photo-overlay">
                                <a href="{{ route('gallery.download', $photo->id) }}" class="overlay-download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="overlay-fullscreen" data-photo-url="{{ $photo->image_url }}">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="no-photos">
                <h3>No hay fotos disponibles</h3>
                <p>{{ $category != 'todo' ? 'No hay fotos en la categoría "' . ucfirst($category) . '"' : 'Aún no se han subido fotos a la galería.' }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de pantalla completa -->
<div id="fullscreenModal" class="fullscreen-modal">
    <div class="modal-content">
        <button class="modal-close" id="modalClose">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="">
        <div class="modal-controls">
            <button class="modal-nav prev" id="modalPrev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="modal-nav next" id="modalNext">
                <i class="fas fa-chevron-right"></i>
            </button>
            <a href="#" class="modal-download" id="modalDownload">
                <i class="fas fa-download"></i>
            </a>
        </div>
    </div>
</div>

<!-- Datos para JavaScript (versión simplificada) -->
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
                    index: {{ $index }}
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        currentCategory: '{{ $category }}',
        totalPhotos: {{ $photos->count() }}
    };
</script>

@push('scripts')
    <script src="{{ asset('js/gallery.js') }}"></script>
@endpush
@endsection
