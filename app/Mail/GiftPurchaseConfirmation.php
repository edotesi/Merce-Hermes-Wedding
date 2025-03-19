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
    public $confirmUrl;
    public $cancelUrl;
    public $emailType;
    public $hoursRemaining;

    public function __construct(
        $giftName,
        $uniqueCode,
        $purchaserName,
        $productUrl = null,
        $emailType = 'reservation',  // 'reservation', 'confirmation', 'cancellation', 'reminder'
        $confirmUrl = null,
        $cancelUrl = null,
        $hoursRemaining = null
    ) {
        $this->giftName = $giftName;
        $this->uniqueCode = $uniqueCode;
        $this->purchaserName = $purchaserName;
        $this->productUrl = $productUrl;
        $this->emailType = $emailType;
        $this->confirmUrl = $confirmUrl ? (string)$confirmUrl : null;
        $this->cancelUrl = $cancelUrl ? (string)$cancelUrl : null;
        $this->hoursRemaining = $hoursRemaining;
    }

    public function envelope()
    {
        $subject = 'Boda Mercè & Hermes - ';

        switch ($this->emailType) {
            case 'confirmation':
                $subject .= 'Confirmación de Compra';
                break;
            case 'cancellation':
                $subject .= 'Cancelación de Reserva';
                break;
            case 'reminder':
                $subject .= 'Recordatorio: Tu reserva expirará pronto';
                break;
            default:
                $subject .= 'Reserva de Regalo';
                break;
        }

        return new \Illuminate\Mail\Mailables\Envelope(
            subject: $subject,
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
