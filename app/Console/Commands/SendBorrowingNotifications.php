<?php

namespace App\Console\Commands;

use App\Services\BorrowingNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBorrowingNotifications extends Command
{
    protected $signature = 'borrowings:send-notifications {--test : Run in test mode without sending actual notifications}';
    protected $description = 'Send due date reminders and overdue notices based on system settings';

    public function handle()
    {
        $this->info('Starting borrowing notifications process...');
        
        try {
            // Send due date reminders
            $this->info('Sending due date reminders...');
            $reminderCount = BorrowingNotificationService::sendDueDateReminders();
            $this->info("Due date reminders sent: {$reminderCount}");
            
            // Send overdue notices
            $this->info('Sending overdue notices...');
            $overdueCount = BorrowingNotificationService::sendOverdueNotices();
            $this->info("Overdue notices sent: {$overdueCount}");
            
            // Update overdue fines
            $this->info('Updating overdue fines...');
            $fineUpdateCount = BorrowingNotificationService::updateOverdueFines();
            $this->info("Fines updated: {$fineUpdateCount}");
            
            // Display statistics
            $stats = BorrowingNotificationService::getBorrowingStats();
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Borrowed', $stats['total_borrowed']],
                    ['Due Today', $stats['due_today']],
                    ['Overdue', $stats['overdue']],
                    ['Due Soon', $stats['due_soon']],
                    ['Total Fines', '₱' . number_format($stats['total_fines'], 2)],
                ]
            );
            
            $this->info('Borrowing notifications process completed successfully!');
            Log::info('Borrowing notifications completed', [
                'reminders_sent' => $reminderCount,
                'overdue_notices_sent' => $overdueCount,
                'fines_updated' => $fineUpdateCount
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error in borrowing notifications process: ' . $e->getMessage());
            Log::error('Borrowing notifications error', ['error' => $e->getMessage()]);
            return 1;
        }
    }
}
