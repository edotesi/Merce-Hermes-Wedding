<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportException;

class GiftController extends Controller
{
   public function index()
   {
       $gifts = Gift::all();
       return view('gifts.index', compact('gifts'));
   }

   public function markAsPurchased(Request $request, Gift $gift)
   {
       try {
           $validated = $request->validate([
               'purchaser_name' => 'nullable|string|max:255',
               'purchaser_email' => 'nullable|email|max:255',
               'store' => 'nullable|string|max:255',
               'order_number' => 'nullable|string|max:255'
           ]);

           Log::debug('Iniciando proceso de compra', [
               'gift_id' => $gift->id,
               'validated_data' => $validated
           ]);

           $gift->update([
               'is_purchased' => false, // Cambiado de false a true
               'purchased_at' => now(),
               'purchaser_name' => $validated['purchaser_name'],
               'purchaser_email' => $validated['purchaser_email'],
               'store' => $validated['store'],
               'order_number' => $validated['order_number'],
               'unique_code' => 'BODA-' . strtoupper(Str::random(4))
           ]);

           Log::debug('Regalo actualizado correctamente', [
               'gift_id' => $gift->id,
               'unique_code' => $gift->unique_code
           ]);

           if($validated['purchaser_email']) {
            try {
                // Test de conexión SMTP
                Log::debug('Iniciando test de conexión SMTP');
                try {
                    $fp = fsockopen('ssl://smtp.hostinger.com', 465, $errno, $errstr, 20);
                    Log::debug('SMTP Connection test', [
                        'success' => ($fp !== false),
                        'errno' => $errno,
                        'errstr' => $errstr
                    ]);
                    if ($fp) {
                        fclose($fp);
                    }
                } catch (\Exception $e) {
                    Log::error('SMTP Connection test failed', [
                        'error' => $e->getMessage()
                    ]);
                }

                Log::debug('Iniciando envío de email', [
                    'connection' => [
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'encryption' => config('mail.mailers.smtp.encryption'),
                        'username' => config('mail.mailers.smtp.username'),
                        'from' => config('mail.from.address'),
                    ],
                    'to' => $validated['purchaser_email'],
                    'gift' => $gift->name
                ]);

                Mail::to($validated['purchaser_email'])
                    ->send(new GiftPurchaseConfirmation(
                        $gift->name,
                        $gift->unique_code,
                        $validated['purchaser_name'],
                        $validated['store'],
                        $validated['order_number']
                    ));

                Log::debug('Email enviado correctamente');
            } catch (TransportException $e) {
                Log::error('Error de transporte SMTP', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            } catch (\Exception $e) {
                Log::error('Error general de envío', [
                    'error' => $e->getMessage(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        }

           return response()->json([
               'message' => 'Regalo marcado como comprado',
               'unique_code' => $gift->unique_code
           ]);
       } catch(\Exception $e) {
           Log::error('Error en markAsPurchased', [
               'error' => $e->getMessage(),
               'class' => get_class($e),
               'trace' => $e->getTraceAsString()
           ]);
           return response()->json(['error' => $e->getMessage()], 422);
       }
   }

   public function unmarkAsPurchased(Request $request, Gift $gift)
   {
       if($request->unique_code !== $gift->unique_code) {
           return response()->json(['message' => 'Código inválido'], 422);
       }

       $gift->update([
           'is_purchased' => false,
           'purchased_at' => null,
           'purchaser_name' => null,
           'purchaser_email' => null,
           'unique_code' => null
       ]);

       return response()->json(['message' => 'Regalo desmarcado como comprado']);
   }
}
