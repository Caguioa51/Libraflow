<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDueDateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-due-date-reminders {--test : Run in test mode without sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due date reminder and overdue notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting due date notification process...');

        $testMode = $this->option('test');
        if ($testMode) {
            $this->warn('Running in TEST MODE - No emails will be sent');
        }

        // Get settings
        $reminderDays = (int) SystemSetting::get('due_date_reminder_days', 3);
        $overdueNotificationDelay = (int) SystemSetting::get('overdue_notification_days', 1);
        $emailEnabled = SystemSetting::get('email_notifications_enabled', 'true') === 'true';

        $this->info("Settings: Reminder days = {$reminderDays}, Overdue delay = {$overdueNotificationDelay}, Email enabled = " . ($emailEnabled ? 'Yes' : 'No'));

        $remindersSent = 0;
        $overdueSent = 0;

        // 1. Send due date reminders
        $this->info('Checking for books due soon...');
        $reminderDate = now()->addDays($reminderDays)->endOfDay();

        $borrowingsDueSoon = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->where('due_date', '<=', $reminderDate)
            ->where('due_date', '>', now())
            ->get();

        foreach ($borrowingsDueSoon as $borrowing) {
            // Check if we already sent a reminder for this borrowing
            $existingReminder = Notification::where('user_id', $borrowing->user_id)
                ->where('type', 'reminder')
                ->whereJsonContains('data->borrowing_id', $borrowing->id)
                ->first();

            if (!$existingReminder) {
                $daysUntilDue = now()->diffInDays($borrowing->due_date, false);

                $notification = Notification::createReminder($borrowing->user, $borrowing, abs($daysUntilDue));

                if ($emailEnabled && !$testMode) {
                    $this->sendEmailNotification($borrowing->user, $notification);
                }

                $notification->markAsSent();
                $remindersSent++;

                $this->line("Sent reminder to {$borrowing->user->name} for '{$borrowing->book->title}' ({$daysUntilDue} days)");
            }
        }

        // 2. Send overdue notifications
        $this->info('Checking for overdue books...');
        $overdueThreshold = now()->subDays($overdueNotificationDelay);

        $overdueBorrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', $overdueThreshold)
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            // Check if we already sent an overdue notification for this borrowing
            $existingOverdue = Notification::where('user_id', $borrowing->user_id)
                ->where('type', 'overdue')
                ->whereJsonContains('data->borrowing_id', $borrowing->id)
                ->first();

            if (!$existingOverdue) {
                $daysOverdue = $borrowing->due_date->diffInDays(now());

                $notification = Notification::createOverdue($borrowing->user, $borrowing, $daysOverdue);

                if ($emailEnabled && !$testMode) {
                    $this->sendEmailNotification($borrowing->user, $notification);
                }

                $notification->markAsSent();
                $overdueSent++;

                $this->line("Sent overdue notice to {$borrowing->user->name} for '{$borrowing->book->title}' ({$daysOverdue} days overdue)");
            }
        }

        // Summary
        $this->info('Notification process completed!');
        $this->info("Reminders sent: {$remindersSent}");
        $this->info("Overdue notices sent: {$overdueSent}");

        if ($testMode) {
            $this->warn('Test mode - no actual emails sent');
        }

        return Command::SUCCESS;
    }

    /**
     * Send email notification to user
     */
    private function sendEmailNotification(User $user, Notification $notification)
    {
        try {
            // For now, we'll just log the email that would be sent
            // In a real implementation, you'd send an actual email
            $this->line("Would send email to: {$user->email} - {$notification->title}");

            // Example of how you might send an email:
            /*
            Mail::to($user->email)->send(new DueDateNotification($notification));
            */

        } catch (\Exception $e) {
            $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
        }
    }
}
