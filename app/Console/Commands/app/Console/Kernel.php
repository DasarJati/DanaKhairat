<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Update expired subscriptions daily at 1:00 AM
        $schedule->command('subscriptions:update-expired')
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscription-update.log'));
            
        // Check for expiring subscriptions daily at 8:00 AM
        $schedule->command('subscriptions:check-expiring --days=30')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscription-check.log'));

        // Send reminder 7 days before
        $schedule->command('subscriptions:check-expiring --days=7')
            ->dailyAt('08:30')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscription-check-7days.log'));

        // Send reminder 3 days before
        $schedule->command('subscriptions:check-expiring --days=3')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscription-check-3days.log'));

        // Send reminder 1 day before
        $schedule->command('subscriptions:check-expiring --days=1')
            ->dailyAt('09:30')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscription-check-1day.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}