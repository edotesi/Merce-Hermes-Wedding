@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>Información Bancaria</h3>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $weddingInfo->iban }}" id="iban"
                                    readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="copyIban()">Copiar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>Dress Code</h3>
                            <p>{{ $weddingInfo->dress_code }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyIban() {
                const iban = document.getElementById('iban');
                navigator.clipboard.writeText(iban.value);
                alert('IBAN copiado al portapapeles');
            }
        </script>
    @endpush
