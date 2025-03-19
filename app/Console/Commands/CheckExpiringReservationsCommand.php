<?php

namespace App\Console\Commands;

use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
     * Los momentos en que se envían recordatorios (horas antes de que expire)
     */
    protected $reminderHours = [24, 12, 3];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring gift reservations...');

        // Procesamos cada intervalo de recordatorio
        foreach ($this->reminderHours as $hoursRemaining) {
            $this->sendRemindersForInterval($hoursRemaining);
        }

        $this->info('Finished checking expiring reservations');

        return 0;
    }

    /**
     * Envía recordatorios para reservas que están a cierto número de horas de expirar
     */
    protected function sendRemindersForInterval($hoursRemaining)
    {
        // Calculamos el rango de tiempo para las reservas que deben recibir recordatorio
        // Por ejemplo, para recordatorio de 24h, buscamos reservas que expirarán entre 23.5 y 24.5 horas desde ahora
        $lowerBound = now()->addHours($hoursRemaining - 0.5); // 30 minutos menos
        $upperBound = now()->addHours($hoursRemaining + 0.5); // 30 minutos más

        $this->info("Buscando reservas que expiran entre {$lowerBound} y {$upperBound}");

        // Buscamos reservas activas cuya fecha de expiración caiga en nuestro rango objetivo
        $expiringReservations = Gift::where('status', 'reserved')
            ->whereNotNull('reserved_at')
            ->whereNotNull('reservation_expires_at')
            ->where('reservation_expires_at', '>=', $lowerBound)
            ->where('reservation_expires_at', '<=', $upperBound)
            ->get();

        $this->info("Found {$expiringReservations->count()} reservations expiring in approximately {$hoursRemaining} hours");

        foreach ($expiringReservations as $gift) {
            try {
                // Asegurarse de que tenemos la información para enviar el email
                if ($gift->purchaser_email && $gift->purchaser_name) {
                    // Calcular tiempo restante exacto para mostrar en el email
                    $minutesRemaining = now()->diffInMinutes($gift->reservation_expires_at, false);
                    $exactHoursRemaining = floor($minutesRemaining / 60);
                    $minutesLeft = $minutesRemaining % 60;

                    // Solo enviar si quedan minutos positivos (por si acaso)
                    if ($minutesRemaining > 0) {
                        $this->info("Sending {$hoursRemaining}-hour reminder email for gift: {$gift->id} - {$gift->name} - Expires at: {$gift->reservation_expires_at}");

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
                                $exactHoursRemaining,
                                $minutesLeft
                            ));

                        $this->info("Reminder email sent to {$gift->purchaser_email}");

                        // Registrar en logs que se envió este recordatorio específico
                        Log::info("Sent {$hoursRemaining}-hour reminder for gift {$gift->id}", [
                            'gift_id' => $gift->id,
                            'hours_remaining' => $hoursRemaining,
                            'email' => $gift->purchaser_email,
                            'expires_at' => $gift->reservation_expires_at
                        ]);
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
    }
}