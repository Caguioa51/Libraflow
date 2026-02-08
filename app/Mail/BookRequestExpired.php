<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\BorrowingRequest;

class BookRequestExpired extends Mailable
{
    use Queueable;

    public $borrowingRequest;
    public $book;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(BorrowingRequest $borrowingRequest)
    {
        $this->borrowingRequest = $borrowingRequest;
        $this->book = $borrowingRequest->book;
        $this->user = $borrowingRequest->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Book Request Expired - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.book-request-expired',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'bookTitle' => $this->book->title,
                'bookAuthor' => $this->book->author->name ?? 'Unknown Author',
                'requestedAt' => $this->borrowingRequest->requested_at->format('M j, Y \a\t g:i A'),
                'expiredAt' => $this->borrowingRequest->expires_at->format('M j, Y \a\t g:i A'),
                'requestId' => $this->borrowingRequest->id,
                'appName' => config('app.name'),
                'bookRequestUrl' => route('borrowing-requests.index'),
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
