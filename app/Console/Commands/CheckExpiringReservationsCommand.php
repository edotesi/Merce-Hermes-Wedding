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

        // Buscamos todas las reservas activas
        $activeReservations = Gift::where('status', 'reserved')
            ->whereNotNull('reserved_at')
            ->whereNotNull('reservation_expires_at')
            ->where('reservation_expires_at', '>', now())
            ->get();

        $this->info("Found {$activeReservations->count()} active reservations");

        $sentCount = 0;

        // Para cada reserva activa, determinamos si debe recibir un recordatorio
        foreach ($activeReservations as $gift) {
            $timeUntilExpiry = now()->diffInHours($gift->reservation_expires_at, false);

            // Encontramos el intervalo de recordatorio más adecuado (si existe)
            $reminderHour = $this->findApplicableReminderHour($timeUntilExpiry);

            if ($reminderHour) {
                $this->sendReminderEmail($gift, $reminderHour);
                $sentCount++;
            }
        }

        $this->info("Finished checking expiring reservations. Sent {$sentCount} reminder emails.");

        return 0;
    }

    /**
     * Determina si una reserva debe recibir recordatorio y para qué intervalo
     *
     * @param int $hoursRemaining Horas restantes hasta la expiración
     * @return int|null El intervalo de recordatorio aplicable o null si no hay ninguno
     */
    protected function findApplicableReminderHour($hoursRemaining)
    {
        foreach ($this->reminderHours as $reminderHour) {
            // Verificamos si el tiempo hasta la expiración está dentro del rango del recordatorio
            // con una tolerancia de ±0.5 horas
            if ($hoursRemaining >= ($reminderHour - 0.5) && $hoursRemaining <= ($reminderHour + 0.5)) {
                return $reminderHour;
            }
        }

        return null;
    }

    /**
     * Envía un email de recordatorio para una reserva específica
     */
    protected function sendReminderEmail(Gift $gift, $reminderHour)
    {
        try {
            // Asegurarse de que tenemos la información para enviar el email
            if (!$gift->purchaser_email || !$gift->purchaser_name) {
                $this->error("Missing email or name for gift: {$gift->id}");
                return;
            }

            // Calcular tiempo restante exacto para mostrar en el email
            $minutesRemaining = now()->diffInMinutes($gift->reservation_expires_at, false);
            $exactHoursRemaining = floor($minutesRemaining / 60);
            $minutesLeft = $minutesRemaining % 60;

            $this->info("Sending {$reminderHour}-hour reminder email for gift: {$gift->id} - {$gift->name} - Expires at: {$gift->reservation_expires_at}");

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
            Log::info("Sent {$reminderHour}-hour reminder for gift {$gift->id}", [
                'gift_id' => $gift->id,
                'hours_remaining' => $reminderHour,
                'email' => $gift->purchaser_email,
                'expires_at' => $gift->reservation_expires_at
            ]);

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