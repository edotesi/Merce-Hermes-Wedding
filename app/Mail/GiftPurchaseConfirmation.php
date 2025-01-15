<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftPurchaseConfirmation extends Mailable
{
    use SerializesModels;

    public $giftName;
    public $uniqueCode;
    public $purchaserName;
    public $productUrl;
    public $store;
    public $orderNumber;
    public $confirmUrl;
    public $cancelUrl;

    public function __construct(
        $giftName,
        $uniqueCode,
        $purchaserName,
        $productUrl = null,
        $store = null,
        $orderNumber = null,
        $confirmUrl = null,
        $cancelUrl = null
    ) {
        $this->giftName = $giftName;
        $this->uniqueCode = $uniqueCode;
        $this->purchaserName = $purchaserName;
        $this->productUrl = $productUrl;
        $this->store = $store;
        $this->orderNumber = $orderNumber;
        $this->confirmUrl = $confirmUrl;
        $this->cancelUrl = $cancelUrl;
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Confirmación Regalo - Boda Mercè & Hermes',
            from: new \Illuminate\Mail\Mailables\Address('informacion@merceyhermes.com', 'Mercè & Hermes')
        );
    }

    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.gift-purchase',
        );
    }

    public function attachments()
    {
        return [];
    }
}
