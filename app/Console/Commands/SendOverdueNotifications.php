<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Models\SystemSetting;
use App\Notifications\OverdueBookNotification;

class SendOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:send-overdue-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for overdue books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!SystemSetting::get('email_notifications_enabled', true)) {
            $this->info('Email notifications are disabled.');
            return;
        }

        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->with(['user', 'book.author'])
            ->get();

        $sentCount = 0;

        foreach ($overdueBorrowings as $borrowing) {
            // Update fine amount
            $borrowing->updateFine();

            // Send notification to user
            $borrowing->user->notify(new OverdueBookNotification($borrowing));
            $sentCount++;

            $this->line("Sent overdue notification to {$borrowing->user->name} for '{$borrowing->book->title}'");
        }

        $this->info("Sent {$sentCount} overdue notifications.");
    }
}
