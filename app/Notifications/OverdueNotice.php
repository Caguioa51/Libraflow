<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OverdueNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public $borrowing;

    public function __construct($borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $fineAmount = $this->borrowing->calculateFine();
        $overdueDays = $this->borrowing->getOverdueDays();
        
        return (new MailMessage)
            ->subject('Library Book Overdue Notice')
            ->greeting('Hello ' . $notifiablename . ',')
            ->line('This is a notice that the book you borrowed is now overdue.')
            ->line('Book Details:')
            ->line('Title: ' . $this->borrowing->book->title)
            ->line('Author: ' . ($this->borrowing->book->author->name ?? 'Unknown'))
            ->line('Due Date: ' . $this->borrowing->due_date->format('M d, Y'))
            ->line('Days Overdue: ' . $overdueDays)
            ->line('Current Fine: ₱' . number_format($fineAmount, 2))
            ->line('Please return the book as soon as possible to avoid additional late fees.')
            ->action('View My Borrowed Books', url('/my-borrowed'))
            ->line('Thank you for your cooperation!')
            ->salutation('DCNHS Library');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Book Overdue Notice',
            'message' => "Your book '{$this->borrowing->book->title}' is overdue by {$this->borrowing->getOverdueDays()} days",
            'borrowing_id' => $this->borrowing->id,
            'book_id' => $this->borrowing->book_id,
            'due_date' => $this->borrowing->due_date,
            'fine_amount' => $this->borrowing->fine_amount,
            'overdue_days' => $this->borrowing->getOverdueDays(),
            'type' => 'overdue_notice'
        ];
    }
}
