<?php
// app/Mail/SubscriptionExpiringMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subscription;
    public $daysRemaining;
    public $subscriptionType;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $subscription, $daysRemaining)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
        $this->subscriptionType = $user->role == 1 ? 'Masjid' : 'Kariah';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->daysRemaining <= 3 
            ? '⚠️ PERINGATAN SEGERA: Keahlian Anda Akan Tamat Tempoh' 
            : 'Peringatan: Keahlian Anda Akan Tamat Tempoh';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_expiring',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}