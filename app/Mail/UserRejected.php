<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class UserRejected extends Mailable
{
    use Queueable;

    public $userName;
    public $userEmail;
    public $rejectionReason;
    public $rejectedAt;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $userEmail, string $rejectionReason = null)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->rejectionReason = $rejectionReason ?: 'Registration rejected by administrator';
        $this->rejectedAt = now();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Regarding Your Registration Request - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-rejected',
            with: [
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'rejectionReason' => $this->rejectionReason,
                'rejectedAt' => $this->rejectedAt->format('M j, Y \a\t g:i A'),
                'appName' => config('app.name'),
            ]
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
