<?php

namespace App\Services;

use App\Models\SystemSetting;
use Carbon\Carbon;

class DueDateCalculator
{
    /**
     * Calculate due date based on system settings
     */
    public static function calculateDueDate(Carbon $startDate = null): Carbon
    {
        $startDate = $startDate ?? now();
        $borrowingDuration = (int) SystemSetting::get('borrowing_duration_days', 14);
        
        $dueDate = $startDate->copy()->addDays($borrowingDuration);
        
        // Apply weekend handling
        $dueDate = self::handleWeekendDueDate($dueDate);
        
        // Apply holiday handling (simplified - extend past holidays)
        $dueDate = self::handleHolidayDueDate($dueDate);
        
        return $dueDate;
    }
    
    /**
     * Handle weekend due dates based on system settings
     */
    private static function handleWeekendDueDate(Carbon $dueDate): Carbon
    {
        $weekendHandling = SystemSetting::get('weekend_due_dates', 'move_to_monday');
        
        if ($weekendHandling === 'allow') {
            return $dueDate;
        }
        
        // Check if due date falls on weekend
        if ($dueDate->isSaturday() || $dueDate->isSunday()) {
            if ($weekendHandling === 'move_to_monday') {
                return $dueDate->next(Carbon::MONDAY);
            } elseif ($weekendHandling === 'move_to_friday') {
                return $dueDate->previous(Carbon::FRIDAY);
            }
        }
        
        return $dueDate;
    }
    
    /**
     * Handle holiday due dates based on system settings
     */
    private static function handleHolidayDueDate(Carbon $dueDate): Carbon
    {
        $holidayHandling = SystemSetting::get('holiday_handling', 'extend');
        
        if ($holidayHandling === 'strict') {
            return $dueDate;
        }
        
        // For simplicity, we'll extend by 1 day if it falls on a common holiday
        // In a real implementation, you'd have a holidays database table
        $holidays = self::getHolidays();
        
        while (in_array($dueDate->format('m-d'), $holidays)) {
            $dueDate->addDay();
        }
        
        return $dueDate;
    }
    
    /**
     * Get list of holidays (simplified for demo)
     */
    private static function getHolidays(): array
    {
        return [
            '01-01', // New Year's Day
            '04-09', // Day of Valor
            '05-01', // Labor Day
            '06-12', // Independence Day
            '08-26', // National Heroes Day
            '11-30', // Bonifacio Day
            '12-25', // Christmas Day
            '12-30', // Rizal Day
        ];
    }
    
    /**
     * Check if user is within grace period for overdue
     */
    public static function isWithinGracePeriod(Carbon $dueDate): bool
    {
        $gracePeriodDays = (int) SystemSetting::get('grace_period_days', 3);
        $gracePeriodEnd = $dueDate->copy()->addDays($gracePeriodDays);
        
        return now()->lte($gracePeriodEnd);
    }
    
    /**
     * Calculate overdue days considering grace period
     */
    public static function calculateOverdueDays(Carbon $dueDate): int
    {
        if (now()->lte($dueDate)) {
            return 0;
        }
        
        $gracePeriodDays = (int) SystemSetting::get('grace_period_days', 3);
        $gracePeriodEnd = $dueDate->copy()->addDays($gracePeriodDays);
        
        if (now()->lte($gracePeriodEnd)) {
            return 0; // Still within grace period
        }
        
        return now()->diffInDays($gracePeriodEnd);
    }
    
    /**
     * Calculate fine amount with grace period consideration
     */
    public static function calculateFine(Carbon $dueDate): float
    {
        $overdueDays = self::calculateOverdueDays($dueDate);
        
        if ($overdueDays <= 0) {
            return 0;
        }
        
        $finePerDay = (float) SystemSetting::get('fine_per_day', 5.00);
        $maxOverdueDays = (int) SystemSetting::get('max_overdue_days', 30);
        
        // Cap the fine calculation at max overdue days
        $chargeableDays = min($overdueDays, $maxOverdueDays);
        
        return $chargeableDays * $finePerDay;
    }
}
