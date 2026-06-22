<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class UserApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;
    public $harga;
    public $masjid;
    public $wakalah;
    public $totalAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $payment, $harga, $masjid, $wakalah, $totalAmount)
{
    $this->user = $user;
    $this->payment = $payment;
    $this->harga = $harga;
    $this->masjid = $masjid;
    $this->wakalah = $wakalah;
    $this->totalAmount = $totalAmount;
}

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Permohonan Diluluskan')
                    ->view('emails.user_approved');
    }
}