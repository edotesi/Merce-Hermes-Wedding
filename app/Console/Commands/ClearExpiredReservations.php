<?php

namespace App\Console\Commands;

use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ClearExpiredReservations extends Command
{
    protected $signature = 'gifts:clear-expired';
    protected $description = 'Clear expired gift reservations';

    public function handle()
    {
        Log::info('Iniciando ClearExpiredReservations', [
            'timestamp' => now(),
            'memory' => memory_get_usage(true)
        ]);

        try {
            DB::transaction(function () {
                $expiredGifts = Gift::query()
                    ->where('status', 'reserved')
                    ->where('reservation_expires_at', '<', now())
                    ->get();

                Log::info('Consulta realizada', [
                    'found_gifts' => $expiredGifts->count(),
                    'current_time' => now()
                ]);

                foreach ($expiredGifts as $gift) {
                    Log::info('Procesando reserva expirada', [
                        'gift_id' => $gift->id,
                        'gift_name' => $gift->name,
                        'reserved_at' => $gift->reserved_at,
                        'expires_at' => $gift->reservation_expires_at
                    ]);

                    // Si tenemos información de contacto del usuario, le notificamos
                    if ($gift->purchaser_email && $gift->purchaser_name) {
                        $this->info("Enviando email de notificación a: {$gift->purchaser_email}");

                        try {
                            // Enviamos el email de cancelación automática
                            Mail::to($gift->purchaser_email)
                                ->send(new GiftPurchaseConfirmation(
                                    $gift->name,
                                    $gift->unique_code,
                                    $gift->purchaser_name,
                                    null,
                                    'expiration', // Nuevo tipo de email para expiración automática
                                    null,
                                    null
                                ));

                            $this->info("Email de expiración enviado para: {$gift->name}");

                            // Esperamos un momento para asegurar que el email se envíe antes de liberar la reserva
                            sleep(1);
                        } catch (\Exception $emailError) {
                            $this->error("Error enviando email de expiración para gift {$gift->id}: " . $emailError->getMessage());
                            Log::error("Error enviando email de expiración", [
                                'gift_id' => $gift->id,
                                'error' => $emailError->getMessage()
                            ]);
                            // Continuamos con la liberación aunque falle el email
                        }
                    }

                    // Ahora limpiamos la reserva
                    $gift->update([
                        'status' => 'available',
                        'reserved_at' => null,
                        'reservation_expires_at' => null,
                        'purchaser_name' => null,
                        'purchaser_email' => null,
                        'unique_code' => null,
                        'reserved_stock' => $gift->reserved_stock - 1
                    ]);

                    $this->info("Cleared expired reservation for gift: {$gift->name}");
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en ClearExpiredReservations', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
