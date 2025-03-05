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

            $uniqueCode = 'BODA-' . strtoupper(Str::random(4));
            $confirmUrl = route('gifts.confirm', ['gift' => $gift->id, 'code' => $uniqueCode]);
            $cancelUrl = route('gifts.cancel', ['gift' => $gift->id, 'code' => $uniqueCode]);

            // Usamos una transacción para asegurar que todo se complete correctamente
            $emailSent = false;
            $result = DB::transaction(function () use ($gift, $validated, $uniqueCode, $confirmUrl, $cancelUrl, &$emailSent) {
                // Primero actualizamos la base de datos
                $gift->update([
                    'status' => 'reserved',
                    'reserved_at' => now(),
                    'reservation_expires_at' => now()->addHours(48),
                    'purchaser_name' => $validated['purchaser_name'],
                    'purchaser_email' => $validated['purchaser_email'],
                    'unique_code' => $uniqueCode,
                    'reserved_stock' => $gift->reserved_stock + 1
                ]);

                // Luego enviamos el correo solo si la BD se actualizó correctamente
                Log::info('Preparando envío de email', [
                    'to' => $validated['purchaser_email'],
                    'confirmUrl' => $confirmUrl,
                    'cancelUrl' => $cancelUrl
                ]);

                try {
                    Mail::to($validated['purchaser_email'])
                        ->send(new GiftPurchaseConfirmation(
                            $gift->name,
                            $uniqueCode,
                            $validated['purchaser_name'],
                            $gift->product_url,
                            'reservation',  // Tipo de email: reserva
                            $confirmUrl,
                            $cancelUrl
                        ));
                    $emailSent = true;
                    Log::info('Email enviado correctamente');
                } catch (\Exception $e) {
                    Log::error('Error enviando email: ' . $e->getMessage());
                    // No hacemos rethrow para no revertir la transacción si el email falla
                }

                return [
                    'message' => 'Regalo reservado correctamente',
                    'unique_code' => $uniqueCode,
                    'product_url' => $gift->product_url,
                    'email_sent' => $emailSent
                ];
            });

            // Añadimos información sobre el estado del email
            $response = $result;
            if (!$emailSent) {
                $response['warning'] = 'La reserva se completó pero hubo un problema al enviar el email.';
            }

            return response()->json($response);

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
                'unique_code' => 'required|string'
            ]);

            if ($gift->unique_code !== $validated['unique_code']) {
                return redirect()->route('home')->with('error', 'Código de reserva inválido');
            }

            if ($gift->status !== 'reserved') {
                return redirect()->route('home')->with('error', 'Este regalo no está en estado de reserva');
            }

            if ($gift->reservation_expires_at && now()->gt($gift->reservation_expires_at)) {
                return redirect()->route('home')->with('error', 'La reserva ha expirado (48 horas)');
            }

            $emailSent = false;

            DB::transaction(function () use ($gift, $validated, &$emailSent) {
                // Actualizamos el estado en la base de datos
                $gift->update([
                    'status' => 'purchased',
                    'purchased_at' => now(),
                    'stock' => $gift->stock - 1,
                    'reserved_stock' => $gift->reserved_stock - 1
                ]);

                // Enviamos el correo de confirmación
                try {
                    Mail::to($gift->purchaser_email)
                        ->send(new GiftPurchaseConfirmation(
                            $gift->name,
                            $validated['unique_code'],
                            $gift->purchaser_name,
                            $gift->product_url,
                            'confirmation'  // Tipo de email: confirmación
                        ));
                    $emailSent = true;
                    Log::info('Email de confirmación enviado correctamente');
                } catch (\Exception $e) {
                    Log::error('Error enviando email de confirmación: ' . $e->getMessage());
                    // No hacemos rethrow para no revertir la transacción si el email falla
                }
            });

            return redirect()->route('home')->with('success', '¡Enhorabuena! Has confirmado correctamente la compra del regalo.');

        } catch (\Exception $e) {
            Log::error('Error en confirmación de compra', [
                'error' => $e->getMessage(),
                'gift_id' => $gift->id
            ]);

            return redirect()->route('home')->with('error', 'Error al procesar la confirmación: ' . $e->getMessage());
        }
    }

    public function cancelReservation(Request $request, Gift $gift)
    {
        try {
            $validated = $request->validate([
                'unique_code' => 'required|string'
            ]);

            if ($gift->unique_code !== $validated['unique_code']) {
                return redirect()->route('home')->with('error', 'Código de reserva inválido');
            }

            if ($gift->status !== 'reserved') {
                return redirect()->route('home')->with('error', 'Este regalo no está en estado de reserva');
            }

            $uniqueCode = $gift->unique_code;
            $purchaserName = $gift->purchaser_name;
            $purchaserEmail = $gift->purchaser_email;
            $giftName = $gift->name;

            $emailSent = false;

            DB::transaction(function () use ($gift, &$emailSent, $purchaserEmail, $giftName, $uniqueCode, $purchaserName) {
                // Guardar los datos antes de eliminarlos
                $gift->update([
                    'status' => 'available',
                    'reserved_at' => null,
                    'reservation_expires_at' => null,
                    'purchaser_name' => null,
                    'purchaser_email' => null,
                    'unique_code' => null,
                    'reserved_stock' => $gift->reserved_stock - 1
                ]);

                // Enviar email de cancelación
                try {
                    Mail::to($purchaserEmail)
                        ->send(new GiftPurchaseConfirmation(
                            $giftName,
                            $uniqueCode,
                            $purchaserName,
                            null,
                            'cancellation'  // Tipo de email: cancelación
                        ));
                    $emailSent = true;
                    Log::info('Email de cancelación enviado correctamente');
                } catch (\Exception $e) {
                    Log::error('Error enviando email de cancelación: ' . $e->getMessage());
                    // No hacemos rethrow para no revertir la transacción si el email falla
                }
            });

            return redirect()->route('home')->with('success', 'Has cancelado correctamente la reserva del regalo.');

        } catch (\Exception $e) {
            Log::error('Error en cancelación de reserva', [
                'error' => $e->getMessage(),
                'gift_id' => $gift->id
            ]);

            return redirect()->route('home')->with('error', 'Error al cancelar la reserva: ' . $e->getMessage());
        }
    }

    public function showConfirmForm(Gift $gift, $code)
    {
        // Validaciones
        if ($gift->unique_code !== $code) {
            return redirect()->route('home')->with('error', 'Código de confirmación inválido');
        }

        if ($gift->status !== 'reserved') {
            $errorMsg = 'Este regalo no está en estado de reserva';
            if ($gift->status === 'purchased') {
                $errorMsg = 'Este regalo ya ha sido confirmado previamente';
            }
            return redirect()->route('home')->with('error', $errorMsg);
        }

        if ($gift->reservation_expires_at && now()->gt($gift->reservation_expires_at)) {
            return redirect()->route('home')->with('error', 'La reserva ha expirado (48 horas)');
        }

        // Si todo está bien, mostrar el formulario de confirmación
        return view('gifts.confirm', compact('gift', 'code'));
    }

    public function showCancelForm(Gift $gift, $code)
    {
        // Validaciones
        if ($gift->unique_code !== $code) {
            return redirect()->route('home')->with('error', 'Código de cancelación inválido');
        }

        if ($gift->status !== 'reserved') {
            $errorMsg = 'Este regalo no está en estado de reserva';
            if ($gift->status === 'purchased') {
                $errorMsg = 'Este regalo ya ha sido confirmado y no se puede cancelar';
            }
            return redirect()->route('home')->with('error', $errorMsg);
        }

        // Si todo está bien, mostrar el formulario de cancelación
        return view('gifts.cancel', compact('gift', 'code'));
    }
}
