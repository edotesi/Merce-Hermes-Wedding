@extends('layouts.app')

@section('content')
<div class="story-wrapper">
    <!-- Imagen de encabezado -->
    <div class="story-header-image-container">
        <img src="{{ asset($headerImage) }}" alt="Mercè y Hermes" class="story-header-image">
        <div class="story-header-overlay"></div>
    </div>

    <div class="story-content">
        <!-- Historia principal -->
        <div class="story-main">
            <div class="story-text">
                <p>La historia de Hermes y Mercè comenzó en el verano de 2019. Se conocieron y congeniaron de una manera que no esperaban. El 26 de agosto de 2019, Hermes le pidió a Mercè que fueran pareja, justo cuando él se preparaba para embarcarse en un Erasmus de cuatro meses en Ámsterdam. Aunque la distancia podía ser un reto, no fue un obstáculo para su amor. Ambos tomaron vuelos y se encontraron siempre que pudieron, demostrando que su conexión era más fuerte que cualquier kilómetro entre ellos.</p>

                <p>En 2020, la pandemia puso a prueba a muchos, pero no logró separarlos. Mientras el mundo cambiaba, Mercè, como enfermera, trabajaba incansablemente en el hospital de Sabadell, y Hermes, aunque a distancia, siempre estuvo a su lado, apoyándola. A pesar de los desafíos, su amor siguió creciendo.</p>

                <p>Luego llegaron las presentaciones familiares...</p>

                <p>La familia de Hermes, conocida por su pasión por los viajes, acogió a Mercè desde el principio, llevándola a disfrutar de mil aventuras alrededor del mundo.</p>

                <p>La familia de Mercè, caracterizada por su dedicación al trabajo, también acogió a Hermes con los brazos abiertos, y pronto lo pusieron a trabajar en Ca l'Àngels. Hermes siempre recordará con cariño el primer día que conoció a la familia de Mercè, sobre todo aquel momento en que Marta, su hermana, lo hizo doblar servilletas en el pasillo, en pleno invierno, sin luz ni calefacción. Todo sin rechistar, una clara señal de que este amor sería para siempre.</p>

                <p>Hoy, años después, nos encontramos aquí, listos para formalizar su amor con el matrimonio. Les haría muy felices contar con vuestra presencia el <strong>14 de junio de 2025</strong>, un día en el que celebraremos este amor que ha sido una aventura, y con vuestra compañía, continuará siendo un viaje inolvidable.</p>
            </div>
        </div>

        <!-- Carrusel de fotos mejorado -->
        <div class="photo-carousel-container">
            <div class="photo-carousel">
                <div class="carousel-inner">
                    @foreach ($photos as $index => $photo)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="image-container">
                                <div class="image-background" style="background-image: url('{{ asset($photo) }}')"></div>
                                <img src="{{ asset($photo) }}" alt="Momento de Mercè y Hermes" class="carousel-image">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control carousel-control-prev" id="prevPhoto">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-control carousel-control-next" id="nextPhoto">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <div class="carousel-indicators">
                    @foreach ($photos as $index => $photo)
                        <button type="button" data-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/story.js') }}"></script>
    <script src="{{ asset('js/image-zoom.js') }}"></script>
@endpush
@endsection