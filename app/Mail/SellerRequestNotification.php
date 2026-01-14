<?php

namespace App\Mail;

use App\Models\SellerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(SellerRequest $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Permohonan Seller Baru')
            ->view('emails.seller-request-notification');
    }
}
