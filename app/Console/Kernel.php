<?php

namespace App\Console;

use App\Console\Commands\CreateForexAccountsFromMysqlToMT5;
use App\Console\Commands\FetchInvestmentDailyScore;
use App\Console\Commands\FetchWeeklyTradeStats;
use App\Console\Commands\GenerateAccountTypeInvestmentSnapshots;
use App\Console\Commands\IBProfitRecord;
use App\Console\Commands\MigrateDBData;
use App\Console\Commands\MultiIbBonus;
use App\Console\Commands\PromoteOrViolateAccount;
use App\Console\Commands\ResetData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use JoeDixon\Translation\Console\Commands\SynchroniseMissingTranslationKeys;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('investments:fetch-daily-score')->dailyAt('23:50');
//        $schedule->command('trade:fetch-weekly-stats')->weeklyOn(1, '23:50');

        // $schedule->command('daily:drawdown')->everyFiveMinutes();

        $schedule->command('update:investment-stats')->everyMinute();
        $schedule->command('update:investment-stats --save-record')->hourly();
        $schedule->command('update:accounts-open-positions')->hourly();
        $schedule->command('update:recent-approved-accounts-stats')->everyFiveMinutes();

        // Promotion and Violation
        $schedule->command('accounts:promote-or-violate')->everyThreeMinutes();
        $schedule->command('send:promote-violate-accounts-mails')->everyThreeMinutes()->delay(1);

        // Detect IP Adresses
        
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

        FetchWeeklyTradeStats::class,
        FetchWeeklyTradeStats::class,
        FetchInvestmentDailyScore::class,
        PromoteOrViolateAccount::class,
        MigrateDBData::class,
        GenerateAccountTypeInvestmentSnapshots::class
    ];
}
