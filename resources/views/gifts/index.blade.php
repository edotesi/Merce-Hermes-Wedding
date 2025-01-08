@extends('layouts.app')

@section('content')
<div class="container py-5">
   <div class="row g-4">
       @foreach($gifts as $gift)
       <div class="col-12 col-md-6 col-lg-4">
           <div class="card h-100">
               <img src="{{ $gift->image_url }}" class="card-img-top" alt="{{ $gift->name }}">
               <div class="card-body">
                   <h5 class="card-title">{{ $gift->name }}</h5>
                   <p class="card-text">{{ number_format($gift->price, 2) }}€</p>

                   @if(!$gift->is_purchased)
                       <button class="btn btn-primary purchase-btn" data-gift-id="{{ $gift->id }}">
                           Marcar como comprado
                       </button>
                   @else
                       <button class="btn btn-secondary unpurchase-btn" data-gift-id="{{ $gift->id }}">
                           Ya comprado
                       </button>
                   @endif
               </div>
           </div>
       </div>
       @endforeach
   </div>
</div>

<!-- Modal de compra -->
<div class="modal fade" id="purchaseModal" tabindex="-1">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title">Información de compra</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
           </div>
           <div class="modal-body">
               <form id="purchaseForm">
                   <div class="mb-3">
                       <label class="form-label">Nombre y Apellidos (opcional)</label>
                       <input type="text" class="form-control" name="purchaser_name">
                   </div>
                   <div class="mb-3">
                       <label class="form-label">Email (opcional)</label>
                       <input type="email" class="form-control" name="purchaser_email">
                   </div>
               </form>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
               <button type="button" class="btn btn-primary" id="confirmPurchase">Confirmar</button>
           </div>
       </div>
   </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
   let currentGiftId = null;
   const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));

   // Botones de compra
   document.querySelectorAll('.purchase-btn').forEach(button => {
       button.addEventListener('click', () => {
           currentGiftId = button.dataset.giftId;
           modal.show();
       });
   });

   // Confirmación de compra
   document.getElementById('confirmPurchase').addEventListener('click', async () => {
       const form = document.getElementById('purchaseForm');
       const formData = new FormData(form);

       try {
           const response = await fetch(`/gifts/${currentGiftId}/purchase`, {
               method: 'POST',
               headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}',
                   'Content-Type': 'application/json',
               },
               body: JSON.stringify(Object.fromEntries(formData))
           });

           const data = await response.json();
           alert(`Regalo marcado como comprado. Tu código es: ${data.unique_code}`);
           location.reload();
       } catch (error) {
           alert('Error al procesar la compra');
       }
   });

   // Desmarcar como comprado
   document.querySelectorAll('.unpurchase-btn').forEach(button => {
       button.addEventListener('click', async () => {
           const code = prompt('Introduce el código de compra:');
           if (!code) return;

           try {
               const response = await fetch(`/gifts/${button.dataset.giftId}/unpurchase`, {
                   method: 'POST',
                   headers: {
                       'X-CSRF-TOKEN': '{{ csrf_token() }}',
                       'Content-Type': 'application/json',
                   },
                   body: JSON.stringify({ unique_code: code })
               });

               if (response.ok) {
                   location.reload();
               } else {
                   alert('Código inválido');
               }
           } catch (error) {
               alert('Error al procesar la solicitud');
           }
       });
   });
});
</script>
@endpush
