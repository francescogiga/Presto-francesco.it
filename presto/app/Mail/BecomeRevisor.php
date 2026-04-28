<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BecomeRevisor extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $motivation = '') {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Richiesta di diventare revisore',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.become-revisor',
        );
    }
}
