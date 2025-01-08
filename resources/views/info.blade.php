@extends('layouts.app')

@section('content')
<div class="container py-5">
   <div class="row">
       <div class="col-md-6 mb-4">
           <div class="card">
               <div class="card-body">
                   <h3>Ceremonia</h3>
                   <p>{{ $ceremony->name }}</p>
                   <p>{{ $ceremony->address }}</p>
                   <p>{{ $ceremony->date->format('d/m/Y') }} - {{ $ceremony->time->format('H:i') }}</p>
                   <a href="{{ $ceremony->maps_url }}" class="btn btn-primary" target="_blank">Ver en Maps</a>
               </div>
           </div>
       </div>

       <div class="col-md-6 mb-4">
           <div class="card">
               <div class="card-body">
                   <h3>Banquete</h3>
                   <p>{{ $reception->name }}</p>
                   <p>{{ $reception->address }}</p>
                   <p>{{ $reception->time->format('H:i') }}</p>
                   <a href="{{ $reception->maps_url }}" class="btn btn-primary" target="_blank">Ver en Maps</a>
               </div>
           </div>
       </div>

       <div class="col-12 mb-4">
           <div class="card">
               <div class="card-body">
                   <h3>Información Bancaria</h3>
                   <div class="input-group">
                       <input type="text" class="form-control" value="{{ $weddingInfo->iban }}" id="iban" readonly>
                       <button class="btn btn-outline-secondary" type="button" onclick="copyIban()">Copiar</button>
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

@push('scripts')
<script>
function copyIban() {
   const iban = document.getElementById('iban');
   navigator.clipboard.writeText(iban.value);
   alert('IBAN copiado al portapapeles');
}
</script>
@endpush
