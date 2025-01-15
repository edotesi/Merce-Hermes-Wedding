<?php

namespace App\Http\Controllers;

use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mailer\Exception\TransportException;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::all();
        return view('gifts.index', compact('gifts'));
    }

    public function reserve(Request $request, Gift $gift)
{
    try {
        $validated = $request->validate([
            'purchaser_name' => 'required|string|max:255',
            'purchaser_email' => 'required|email|max:255',
        ]);

        Log::info('Iniciando proceso de reserva', [
            'gift_id' => $gift->id,
            'validated_data' => $validated
        ]);

        // Verificamos configuración SMTP
        Log::info('Configuración SMTP', [
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
        ]);

        try {
            $uniqueCode = 'BODA-' . strtoupper(Str::random(4));
            $confirmUrl = route('gifts.confirm', ['gift' => $gift->id, 'code' => $uniqueCode]);
            $cancelUrl = route('gifts.cancel', ['gift' => $gift->id, 'code' => $uniqueCode]);

            Log::info('Preparando envío de email', [
                'to' => $validated['purchaser_email'],
                'confirmUrl' => $confirmUrl,
                'cancelUrl' => $cancelUrl
            ]);

            Mail::to($validated['purchaser_email'])
                ->send(new GiftPurchaseConfirmation(
                    $gift->name,
                    $uniqueCode,
                    $validated['purchaser_name'],
                    $gift->product_url,
                    null,
                    null,
                    $confirmUrl,
                    $cancelUrl
                ));

            Log::info('Email enviado correctamente');

                // Si el email se envió correctamente, procedemos con la actualización
                DB::transaction(function () use ($gift, $validated, $uniqueCode) {
                    $gift->update([
                        'status' => 'reserved',
                        'reserved_at' => now(),
                        'reservation_expires_at' => now()->addHours(48),
                        'purchaser_name' => $validated['purchaser_name'],
                        'purchaser_email' => $validated['purchaser_email'],
                        'unique_code' => $uniqueCode,
                        'reserved_stock' => $gift->reserved_stock + 1
                    ]);
                });

                return response()->json([
                    'message' => 'Regalo reservado correctamente',
                    'unique_code' => $uniqueCode,
                    'product_url' => $gift->product_url
                ]);

            } catch (TransportException $e) {
                Log::error('Error de transporte SMTP', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'error' => 'Error al enviar el email: ' . $e->getMessage()
                ], 500);
            } catch (\Exception $e) {
                Log::error('Error general al enviar email', [
                    'error' => $e->getMessage(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'error' => 'Error inesperado: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error en proceso de reserva', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error en el proceso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmPurchase(Request $request, Gift $gift)
    {
        try {
            $validated = $request->validate([
                'unique_code' => 'required|string',
                'store' => 'required|string|max:255',
                'order_number' => 'nullable|string|max:255'
            ]);

            if ($gift->unique_code !== $validated['unique_code']) {
                return response()->json([
                    'error' => 'Código de reserva inválido'
                ], 422);
            }

            if ($gift->status !== 'reserved') {
                return response()->json([
                    'error' => 'Este regalo no está en estado de reserva'
                ], 422);
            }

            try {
                Mail::to($gift->purchaser_email)
                    ->send(new GiftPurchaseConfirmation(
                        $gift->name,
                        $validated['unique_code'],
                        $gift->purchaser_name,
                        $validated['store'],
                        $validated['order_number']
                    ));

                DB::transaction(function () use ($gift, $validated) {
                    $gift->update([
                        'status' => 'purchased',
                        'purchased_at' => now(),
                        'store' => $validated['store'],
                        'order_number' => $validated['order_number'],
                        'stock' => $gift->stock - 1,
                        'reserved_stock' => $gift->reserved_stock - 1
                    ]);
                });

                return response()->json([
                    'message' => 'Compra confirmada correctamente'
                ]);

            } catch (TransportException $e) {
                Log::error('Error al enviar email de confirmación de compra', [
                    'error' => $e->getMessage(),
                    'gift_id' => $gift->id
                ]);

                return response()->json([
                    'error' => 'No se pudo enviar el email de confirmación. Por favor, inténtalo más tarde.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error en confirmación de compra', [
                'error' => $e->getMessage(),
                'gift_id' => $gift->id
            ]);

            return response()->json([
                'error' => 'Error al procesar la confirmación'
            ], 500);
        }
    }

    public function cancelReservation(Request $request, Gift $gift)
    {
        try {
            $validated = $request->validate([
                'unique_code' => 'required|string'
            ]);

            if ($gift->unique_code !== $validated['unique_code']) {
                return response()->json([
                    'error' => 'Código de reserva inválido'
                ], 422);
            }

            DB::transaction(function () use ($gift) {
                $gift->update([
                    'status' => 'available',
                    'reserved_at' => null,
                    'reservation_expires_at' => null,
                    'purchaser_name' => null,
                    'purchaser_email' => null,
                    'unique_code' => null,
                    'reserved_stock' => $gift->reserved_stock - 1
                ]);
            });

            return response()->json([
                'message' => 'Reserva cancelada correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en cancelación de reserva', [
                'error' => $e->getMessage(),
                'gift_id' => $gift->id
            ]);

            return response()->json([
                'error' => 'Error al cancelar la reserva'
            ], 500);
        }
    }

    public function showConfirmForm(Gift $gift, $code)
{
    if ($gift->unique_code !== $code || $gift->status !== 'reserved') {
        abort(404);
    }

    return view('gifts.confirm', compact('gift', 'code'));
}

public function showCancelForm(Gift $gift, $code)
{
    if ($gift->unique_code !== $code || $gift->status !== 'reserved') {
        abort(404);
    }

    return view('gifts.cancel', compact('gift', 'code'));
}

}
