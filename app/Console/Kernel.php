<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // Account Stats Updation
        $schedule->command('update:investment-stats')->everyThreeMinutes();
        
        $schedule->command('update:investment-stats --save-record')->hourly();

        // Account open positions
        $schedule->command('update:accounts-open-positions')->hourly();

        // Fetch trading stats of newly active accounts
        // $schedule->command('update:recent-approved-accounts-stats')->everyThreeMinutes();

        // Promotion and Violation
        // $schedule->command('accounts:promote-or-violate')->everyse();
        $schedule->command('send:promote-violate-accounts-mails')->everyTwoMinutes();

        // Contracts Expiry Scheduler
        $schedule->command('check:contract-expiry')->daily();

        // Trial Active and Expiry
        $schedule->command('trial-accounts:active-or-expire')->everyThirtyMinutes();

        // Delete test uploads from S3
        $schedule->command('aws-s3:delete-test-uploads')->daily();

        // Expire Old Offers
        $schedule->command('offers:expire-old')->everySixHours();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
    ];
}
