<?php

namespace App\Console\Commands;

use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckExpiringReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gifts:check-expiring-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for gift reservations that are about to expire and send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring gift reservations...');

        // Buscar reservas que tengan aproximadamente 24 horas (más o menos un margen de tiempo)
        // Esto significa que han pasado ~24 horas desde la reserva, y quedan ~24 horas para expirar
        $expiringReservations = Gift::where('status', 'reserved')
            ->whereNotNull('reserved_at')
            ->whereNotNull('reservation_expires_at')
            // La reserva se hizo hace aproximadamente 24 horas (entre 23 y 25 horas atrás)
            ->where('reserved_at', '<=', now()->subHours(23))
            ->where('reserved_at', '>=', now()->subHours(25))
            ->get();

        $this->info("Found {$expiringReservations->count()} expiring reservations");

        foreach ($expiringReservations as $gift) {
            try {
                // Asegurarse de que tenemos la información para enviar el email
                if ($gift->purchaser_email && $gift->purchaser_name) {
                    // Calcular tiempo restante para mostrar en el email
                    $hoursRemaining = now()->diffInHours($gift->reservation_expires_at, false);

                    // Solo enviar si quedan horas positivas (por si acaso)
                    if ($hoursRemaining > 0) {
                        $this->info("Sending reminder email for gift: {$gift->id} - {$gift->name}");

                        // Generar URLs para confirmar o cancelar
                        $confirmUrl = route('gifts.confirm', ['gift' => $gift->id, 'code' => $gift->unique_code]);
                        $cancelUrl = route('gifts.cancel', ['gift' => $gift->id, 'code' => $gift->unique_code]);

                        // Enviar email
                        Mail::to($gift->purchaser_email)
                            ->send(new GiftPurchaseConfirmation(
                                $gift->name,
                                $gift->unique_code,
                                $gift->purchaser_name,
                                $gift->product_url,
                                'reminder',
                                $confirmUrl,
                                $cancelUrl,
                                $hoursRemaining
                            ));

                        $this->info("Reminder email sent to {$gift->purchaser_email}");
                    } else {
                        $this->warn("Reservation already expired for gift: {$gift->id}");
                    }
                } else {
                    $this->error("Missing email or name for gift: {$gift->id}");
                }
            } catch (\Exception $e) {
                $this->error("Error sending reminder email for gift {$gift->id}: " . $e->getMessage());
                Log::error("Error sending reminder email", [
                    'gift_id' => $gift->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info('Finished checking expiring reservations');

        return 0;
    }
}
