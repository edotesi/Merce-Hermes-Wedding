@extends('layouts.app')

@section('content')
<div class="accommodation-wrapper">
    <div class="accommodation-content">
        <h1 class="main-title">Alojamientos</h1>

        <p class="intro-text">
            Queremos que todos nuestros invitados puedan disfrutar de nuestra celebración con total comodidad
            y seguridad. Por ese motivo, a continuación, encontraréis un listado de hoteles y alojamientos cerca
            de La Farinera Sant Lluis.
        </p>

        @foreach($accommodations as $zone => $zoneAccommodations)
            <div class="accommodation-zone">
                <h2 class="zone-title">{{ $zone }}</h2>
                <div class="accommodation-list">
                    @foreach($zoneAccommodations as $accommodation)
                        <div class="accommodation-item">
                            <div class="accommodation-left">
                                <div class="hotel-info-header">
                                    <h3 class="hotel-name">
                                        {{ $accommodation->name }}
                                        @if($accommodation->stars)
                                            <span class="stars">
                                                @for($i = 0; $i < strlen($accommodation->stars); $i++)
                                                    <span class="star"></span>
                                                @endfor
                                            </span>
                                        @endif
                                    </h3>
                                </div>
                                <span class="distance">{{ $accommodation->distance }} km</span>
                                @if($accommodation->discount_info)
                                    <p class="discount-info">{{ $accommodation->discount_info }}</p>
                                @endif
                            </div>
                            <div class="accommodation-right">
                                <a href="https://{{ $accommodation->website }}" class="reserve-button" target="_blank">
                                    Reservar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
