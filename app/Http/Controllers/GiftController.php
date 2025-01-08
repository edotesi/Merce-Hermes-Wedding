<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::all();
        return view('gifts.index', compact('gifts'));
    }

    public function markAsPurchased(Request $request, Gift $gift)
    {
        $validated = $request->validate([
            'purchaser_name' => 'nullable|string|max:255',
            'purchaser_email' => 'nullable|email|max:255'
        ]);

        $gift->update([
            'is_purchased' => true,
            'purchased_at' => now(),
            'purchaser_name' => $validated['purchaser_name'],
            'purchaser_email' => $validated['purchaser_email'],
            'unique_code' => 'BODA-' . strtoupper(Str::random(4))
        ]);

        if($validated['purchaser_email']) {
            // Enviar email con código
        }

        return response()->json([
            'message' => 'Regalo marcado como comprado',
            'unique_code' => $gift->unique_code
        ]);
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
