<?php
// app/Mail/ApprovalStatusMail.php

namespace App\Mail;

use App\Models\UserRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subjectText;
    public $messageText;
    public $masjid;

    public function __construct(UserRegister $user, $subject, $message, $masjid = null)
    {
        $this->user = $user;
        $this->subjectText = $subject;
        $this->messageText = $message;
        $this->masjid = $masjid;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval_status',
            with: [
                'user' => $this->user,
                'messageText' => $this->messageText,
                'masjid' => $this->masjid,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}