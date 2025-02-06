<?php

namespace App\Console\Commands;

use App\Models\Gift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                    Log::info('Limpiando reserva expirada', [
                        'gift_id' => $gift->id,
                        'gift_name' => $gift->name,
                        'reserved_at' => $gift->reserved_at,
                        'expires_at' => $gift->reservation_expires_at
                    ]);

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
