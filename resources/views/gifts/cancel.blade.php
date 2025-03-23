@extends('layouts.app')

@section('content')

        <div class="gift-form-container">
            <h2 class="gift-title">Cancelar Reserva</h2>
            <form action="{{ route('gifts.cancelReservation', $gift) }}" method="POST" class="event-details">
                @csrf
                <input type="hidden" name="unique_code" value="{{ $code }}">

                <div class="gift-form-group">
                    <label class="event-label">Regalo</label>
                    <div class="gift-name">{{ $gift->name }}</div>
                </div>
                <div class="gift-form-group">
                    <label class="event-label">Código de reserva</label>
                    <div class="code-display">{{ $code }}</div>
                </div>
                <div class="button-group">
                    <button type="button" onclick="window.history.back()" class="button">
                        VOLVER
                    </button>
                    <button type="submit" class="button primary">
                        CANCELAR RESERVA
                    </button>
                </div>
            </form>

    </div>
@endsection
