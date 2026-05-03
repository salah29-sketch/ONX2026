<?php

namespace App\Mail\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly ?string $clientName = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ONX — كود إعادة تعيين كلمة المرور',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client.password-reset-otp',
        );
    }
}