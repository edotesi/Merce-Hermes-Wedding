<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\GiftPurchaseConfirmation;
use App\Models\Gift;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::all();
        return view('gifts.index', compact('gifts'));
    }

    // GiftController.php
public function markAsPurchased(Request $request, Gift $gift)
{
    try {
        $validated = $request->validate([
            'purchaser_name' => 'nullable|string|max:255',
            'purchaser_email' => 'nullable|email|max:255',
            'store' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255'
        ]);
        Log::info('Datos validados:', $validated);

        $gift->update([
            'is_purchased' => false,
            'purchased_at' => now(),
            'purchaser_name' => $validated['purchaser_name'],
            'purchaser_email' => $validated['purchaser_email'],
            'store' => $validated['store'],
            'order_number' => $validated['order_number'],
            'unique_code' => 'BODA-' . strtoupper(Str::random(4))
        ]);
        Log::info("message");

        if($validated['purchaser_email']) {
            Log::info('Enviando email a: ' . $validated['purchaser_email']);

            Mail::to($validated['purchaser_email'])
                ->send(new GiftPurchaseConfirmation(
                    $gift->name,
                    $gift->unique_code,
                    $validated['purchaser_name'],
                    $validated['store'],
                    $validated['order_number']
                ));
        }

        return response()->json([
            'message' => 'Regalo marcado como comprado',
            'unique_code' => $gift->unique_code
        ]);
    } catch(\Exception $e) {
        Log::error('Error en markAsPurchased: ' . $e->getMessage());
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
