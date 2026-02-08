<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\SystemSetting;
use App\Notifications\DueDateReminder;
use App\Notifications\OverdueNotice;
use Carbon\Carbon;

class BorrowingNotificationService
{
    /**
     * Send due date reminders for books due soon
     */
    public static function sendDueDateReminders()
    {
        $reminderDays = (int) SystemSetting::get('due_date_reminder_days', 3);
        $targetDate = now()->addDays($reminderDays);
        
        // Find borrowings due on the target date
        $borrowings = Borrowing::with('user')
            ->where('status', 'borrowed')
            ->whereDate('due_date', $targetDate->format('Y-m-d'))
            ->get();
        
        foreach ($borrowings as $borrowing) {
            try {
                $borrowing->user->notify(new DueDateReminder($borrowing));
                \Log::info("Due date reminder sent to user {$borrowing->user->email} for borrowing {$borrowing->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send due date reminder: " . $e->getMessage());
            }
        }
        
        return $borrowings->count();
    }
    
    /**
     * Send overdue notices for books that are overdue
     */
    public static function sendOverdueNotices()
    {
        $delayDays = (int) SystemSetting::get('overdue_notification_days', 1);
        $targetDate = now()->subDays($delayDays);
        
        // Find borrowings that became overdue on the target date
        $borrowings = Borrowing::with('user')
            ->where('status', 'borrowed')
            ->whereDate('due_date', $targetDate->format('Y-m-d'))
            ->where('due_date', '<', now())
            ->get();
        
        foreach ($borrowings as $borrowing) {
            try {
                $borrowing->user->notify(new OverdueNotice($borrowing));
                \Log::info("Overdue notice sent to user {$borrowing->user->email} for borrowing {$borrowing->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to send overdue notice: " . $e->getMessage());
            }
        }
        
        return $borrowings->count();
    }
    
    /**
     * Update all overdue fines
     */
    public static function updateOverdueFines()
    {
        $borrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();
        
        $updatedCount = 0;
        foreach ($borrowings as $borrowing) {
            $oldFine = $borrowing->fine_amount;
            $borrowing->updateFine();
            
            if ($oldFine != $borrowing->fine_amount) {
                $updatedCount++;
                \Log::info("Updated fine for borrowing {$borrowing->id}: {$oldFine} -> {$borrowing->fine_amount}");
            }
        }
        
        return $updatedCount;
    }
    
    /**
     * Get borrowing statistics
     */
    public static function getBorrowingStats()
    {
        $today = now();
        
        return [
            'total_borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'due_today' => Borrowing::where('status', 'borrowed')->whereDate('due_date', $today->format('Y-m-d'))->count(),
            'overdue' => Borrowing::where('status', 'borrowed')->where('due_date', '<', $today)->count(),
            'due_soon' => self::getDueSoonCount(),
            'total_fines' => Borrowing::where('status', 'borrowed')->sum('fine_amount'),
        ];
    }
    
    /**
     * Get count of books due soon (within reminder period)
     */
    private static function getDueSoonCount()
    {
        $reminderDays = (int) SystemSetting::get('due_date_reminder_days', 3);
        $startDate = now()->addDay();
        $endDate = now()->addDays($reminderDays);
        
        return Borrowing::where('status', 'borrowed')
            ->whereBetween('due_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();
    }
    
    /**
     * Check if borrowing is within grace period
     */
    public static function isWithinGracePeriod(Borrowing $borrowing): bool
    {
        if (!$borrowing->due_date || $borrowing->status !== 'borrowed') {
            return false;
        }
        
        return DueDateCalculator::isWithinGracePeriod($borrowing->due_date);
    }
    
    /**
     * Get borrowing status with grace period consideration
     */
    public static function getBorrowingStatus(Borrowing $borrowing): string
    {
        if ($borrowing->status === 'returned') {
            return 'returned';
        }
        
        if (!$borrowing->due_date) {
            return 'borrowed';
        }
        
        if (now()->gt($borrowing->due_date)) {
            if (self::isWithinGracePeriod($borrowing)) {
                return 'grace_period';
            }
            return 'overdue';
        }
        
        return 'borrowed';
    }
}
