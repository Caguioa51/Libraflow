<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SmokeBorrowTest;
use App\Console\Commands\SendOverdueNotifications;
use App\Console\Commands\SendDueDateNotifications;
use App\Console\Commands\ExpireBorrowingRequests;
use App\Console\Commands\SendBorrowingNotifications;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SmokeBorrowTest::class,
        SendOverdueNotifications::class,
        SendDueDateNotifications::class,
        ExpireBorrowingRequests::class,
        SendBorrowingNotifications::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Expire borrowing requests every minute
        $schedule->command('borrowing-requests:expire')
            ->everyMinute()
            ->timezone('Asia/Shanghai');

        // Send borrowing notifications daily at 8:00 AM
        $schedule->command('borrowings:send-notifications')
            ->dailyAt('08:00')
            ->timezone('Asia/Shanghai');

        // Send overdue notifications daily at 9:00 AM
        $schedule->command('library:send-overdue-notifications')
            ->dailyAt('09:00')
            ->timezone('Asia/Shanghai');

        // Send due date reminder notifications daily at 8:00 AM
        $schedule->command('notifications:send-due-date-reminders')
            ->dailyAt('08:00')
            ->timezone('Asia/Shanghai');
    }
}
