<?php

namespace App\Console;

use App\Console\Commands\CreateForexAccountsFromMysqlToMT5;
use App\Console\Commands\IBProfitRecord;
use App\Console\Commands\MultiIbBonus;
use App\Console\Commands\MultiLevelRebateDistribution;
use App\Console\Commands\ResetData;
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
        $schedule->command('exchange:update-rates')->everyThirtyMinutes();
        $schedule->command('tokens:update-rates')->everyThirtyMinutes();
//        $schedule->command('rebate:distribution')->everyFiveMinutes();
//        if(url('/') == 'http://brokerdemo.brokeret.com') {
//            $schedule->command('reset:data')->daily();
//        }
//        $schedule->command('ib:record')->dailyAt('00:10');
//        $schedule->command('multiIB:Bonus')->dailyAt('00:30');

//        $schedule->command('sync:forex-accounts-via-email-banex')->everyFiveMinutes();
//        $schedule->command('ib:record')->everyMinute();
//        $schedule->command('forex:create-accounts-from-mysql-to-mt5')->everyTwoMinutes();
//        $schedule->command('multiIB:Bonus')->everyMinute();
//        $schedule->command('queue:work --stop-when-empty')
//            ->everyMinute()
//            ->withoutOverlapping();
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
        SyncForexAccountsViaEmailForBanex::class,
        MultiLevelRebateDistribution::class,

    ];
}
