<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\BorrowingRequest;
use App\Models\Borrowing;

class BookRequestApproved extends Mailable
{
    use Queueable;

    public $borrowingRequest;
    public $book;
    public $user;
    public $borrowing;
    public $approvedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(BorrowingRequest $borrowingRequest, Borrowing $borrowing, $approvedBy)
    {
        $this->borrowingRequest = $borrowingRequest;
        $this->book = $borrowingRequest->book;
        $this->user = $borrowingRequest->user;
        $this->borrowing = $borrowing;
        $this->approvedBy = $approvedBy;
    }

    /**
     * Get message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Book Request Approved - ' . config('app.name'),
        );
    }

    /**
     * Get message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.book-request-approved',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'bookTitle' => $this->book->title,
                'bookAuthor' => $this->book->author->name ?? 'Unknown Author',
                'requestedAt' => $this->borrowingRequest->requested_at->format('M j, Y \a\t g:i A'),
                'approvedAt' => $this->borrowingRequest->approved_at->format('M j, Y \a\t g:i A'),
                'dueDate' => $this->borrowing->due_date->format('M j, Y'),
                'borrowedAt' => $this->borrowing->borrowed_at->format('M j, Y \a\t g:i A'),
                'approvedByName' => $this->approvedBy->name ?? 'System Administrator',
                'requestId' => $this->borrowingRequest->id,
                'borrowingId' => $this->borrowing->id,
                'appName' => config('app.name'),
                'myBorrowingsUrl' => route('my-borrowed.index'),
            ]
        );
    }

    /**
     * Get attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
