<?php

namespace App\Console;

use App\Console\Commands\CreateForexAccountsFromMysqlToMT5;
use App\Console\Commands\IBProfitRecord;
use App\Console\Commands\MultiIbBonus;
use App\Console\Commands\MultiLevelRebateDistribution;
use App\Console\Commands\ResetData;
use App\Console\Commands\SyncForexAccountsViaEmail;
use App\Console\Commands\UpdateExchangeRates;

use App\Console\Commands\SyncForexAccountsViaEmailForBanex;

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
//        $schedule->command('rebate:distribution')->everyFiveMinutes();
        $schedule->command('exchange:update-rates')->everyThirtyMinutes();
        $schedule->command('tokens:update-rates')->everyThirtyMinutes();
//        $schedule->command('sync:forex-accounts-via-email')->everyFiveMinutes();

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
        SynchroniseMissingTranslationKeys::class,
        CreateForexAccountsFromMysqlToMT5::class,
        ResetData::class,
        IBProfitRecord::class,
        MultiIbBonus::class,
        Commands\UpdateExchangeRates::class,
        Commands\UpdateTokenRates::class,
        SyncForexAccountsViaEmail::class,
        MultiLevelRebateDistribution::class,

    ];
}
