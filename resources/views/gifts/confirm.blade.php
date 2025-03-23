@extends('layouts.app')

@section('content')
    <div class="gift-form-container">
        <h2 class="gift-title">Confirmar Regalo</h2>
        <form action="{{ route('gifts.confirmPurchase', $gift) }}" method="POST" class="gift-form">
            @csrf
            <input type="hidden" name="unique_code" value="{{ $code }}">

            <div class="gift-form-group">
                <label class="gift-form-label">Regalo</label>
                <div class="gift-name">{{ $gift->name }}</div>
            </div>
            
            <div class="gift-form-group">
                <label class="gift-form-label">Código de reserva</label>
                <div class="code-display">{{ $code }}</div>
            </div>
            
            <div class="warning-message">
                ¿Estás seguro de que quieres confirmar la reserva?
                El regalo será marcado como comprado. Confirma una vez hayas comprado el producto.
            </div>

            <div class="button-group">
                <button type="button" onclick="window.history.back()" class="button">
                    VOLVER
                </button>
                <button type="submit" class="button primary">
                    CONFIRMAR COMPRA
                </button>
            </div>
        </form>
    </div>
@endsection