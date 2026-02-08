<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DueDateReminder extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Library Book Due Date Reminder')
            ->greeting('Hello ' . $notifiablename . ',')
            ->line('This is a friendly reminder that the book you borrowed is due soon.')
            ->line('Book Details:')
            ->line('Title: ' . $this->borrowing->book->title)
            ->line('Author: ' . ($this->borrowing->book->author->name ?? 'Unknown'))
            ->line('Due Date: ' . $this->borrowing->due_date->format('M d, Y'))
            ->line('Please return the book on or before the due date to avoid late fees.')
            ->action('View My Borrowed Books', url('/my-borrowed'))
            ->line('Thank you for using our library system!')
            ->salutation('DCNHS Library');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Book Due Date Reminder',
            'message' => "Your book '{$this->borrowing->book->title}' is due on {$this->borrowing->due_date->format('M d, Y')}",
            'borrowing_id' => $this->borrowing->id,
            'book_id' => $this->borrowing->book_id,
            'due_date' => $this->borrowing->due_date,
            'type' => 'due_date_reminder'
        ];
    }
}
