<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TestSession;
use App\Models\Participant;

class TestAccessCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $participant;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(TestSession $session, Participant $participant)
    {
        $this->session = $session;
        $this->participant = $participant;
        $this->loginUrl = url('/');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your StressLab Test Access Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test_access_code',
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
