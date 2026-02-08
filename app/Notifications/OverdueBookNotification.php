<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Borrowing;

class OverdueBookNotification extends Notification
{
    use Queueable;

    protected $borrowing;

    /**
     * Create a new notification instance.
     */
    public function __construct(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $fineAmount = $this->borrowing->calculateFine();
        $daysOverdue = now()->diffInDays($this->borrowing->due_date);

        return (new MailMessage)
            ->subject('Overdue Book Reminder - ' . $this->borrowing->book->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that you have an overdue book:')
            ->line('**Book:** ' . $this->borrowing->book->title)
            ->line('**Author:** ' . $this->borrowing->book->author->name)
            ->line('**Due Date:** ' . $this->borrowing->due_date->format('M d, Y'))
            ->line('**Days Overdue:** ' . $daysOverdue . ' days')
            ->line('**Fine Amount:** ₱' . number_format($fineAmount, 2))
            ->line('Please return this book as soon as possible to avoid additional fines.')
            ->action('View My Borrowings', url('/my-borrowings'))
            ->line('Thank you for using the library system!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'book_title' => $this->borrowing->book->title,
            'due_date' => $this->borrowing->due_date,
            'fine_amount' => $this->borrowing->calculateFine(),
        ];
    }
}
