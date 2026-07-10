<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $masjidName;
    public $tanggunganCount;
    public $totalAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $masjidName, $tanggunganCount, $totalAmount)
    {
        $this->user = $user;
        $this->masjidName = $masjidName;
        $this->tanggunganCount = $tanggunganCount;
        $this->totalAmount = $totalAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengesahan Pendaftaran Keahlian - ' . $this->masjidName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}