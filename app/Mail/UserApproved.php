<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;

class UserApproved extends Mailable
{
    use Queueable;

    public $user;
    public $approvedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, User $approvedBy = null)
    {
        $this->user = $user;
        $this->approvedBy = $approvedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Your Account Has Been Approved - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-approved',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'userRole' => $this->user->role,
                'approvedByName' => $this->approvedBy ? $this->approvedBy->name : 'System Administrator',
                'approvedAt' => $this->user->approved_at ? $this->user->approved_at->format('M j, Y \a\t g:i A') : now()->format('M j, Y \a\t g:i A'),
                'loginUrl' => route('login'),
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
