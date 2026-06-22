<?php

namespace App\Mail;

use App\Models\AjkRegister;
use App\Models\Masjid;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class AjkApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $app;
    public $masjid;
    public $Amount;

    public function __construct($app, $masjid, $Amount)
    {
        $this->app = $app;
        $this->masjid = $masjid;
        $this->Amount = $Amount;
    }

    public function build()
    {
        return $this->subject('Pendaftaran sistem E-Khairat Anda Telah Diluluskan')
            ->view('emails.ajk_approved');
    }
}