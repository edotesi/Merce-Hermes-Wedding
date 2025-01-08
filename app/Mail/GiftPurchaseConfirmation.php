<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GiftPurchaseConfirmation extends Mailable implements ShouldQueue
{
   use Queueable, SerializesModels;

   public $giftName;
   public $uniqueCode;
   public $purchaserName;
   public $store;
   public $orderNumber;

   public function __construct($giftName, $uniqueCode, $purchaserName = null, $store = null, $orderNumber = null)
   {
       $this->giftName = $giftName;
       $this->uniqueCode = $uniqueCode;
       $this->purchaserName = $purchaserName;
       $this->store = $store;
       $this->orderNumber = $orderNumber;
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
