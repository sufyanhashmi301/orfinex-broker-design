<?php

namespace App\Console;

use App\Console\Commands\CreateForexAccountsFromMysqlToMT5;
use App\Console\Commands\DeleteStaleUsers;
use App\Console\Commands\IBProfitRecord;
use App\Console\Commands\MultiIbBonus;
use App\Console\Commands\MultiLevelRebateDistribution;
use App\Console\Commands\ResetData;
use App\Console\Commands\SyncForexAccountsViaEmail;
use App\Console\Commands\UpdateExchangeRates;
use App\Console\Commands\CreateIBTransactionsTable4Month;
use App\Console\Commands\CopyIBTransactions4Month;
use App\Console\Commands\ScheduleIBTransactions4Month;
use App\Console\Commands\TestIBTransactions4Month;
use App\Console\Commands\MigrateAllIBTransactions;
use App\Console\Commands\TestQuarterIntegration;
use App\Console\Commands\HighPerformanceIBMigration;
use App\Console\Commands\ParallelIBMigration;
use App\Console\Commands\MigrationWorker;
use App\Console\Commands\IBMigrationMenu;
use App\Console\Commands\FixIBTransactionDates;
use App\Console\Commands\AutoIBMigration;
use App\Console\Commands\TestIBQuarterSystem;
use App\Console\Commands\RecalculateIBBalances;
use App\Console\Commands\DebugUserIBTransactions;
use App\Console\Commands\SyncForexAccountsViaEmailForBanex;
use App\Console\Commands\RemoveDuplicateIBTransactions;

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

        // Dynamically schedule rebate distribution based on settings (in minutes)
        $rebateMinutes = (int) setting('ib_distribution_time', 'features', 1);
        $rebateMinutes = max(1, $rebateMinutes);
        $schedule->command('rebate:distribution')->cron("*/{$rebateMinutes} * * * *")->withoutOverlapping();
        // $schedule->command('rebate:distribution')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('ib:remove-duplicates')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('users:delete-stale')->daily();
        $schedule->command('exchange:update-rates')->everyThirtyMinutes();
        $schedule->command('tokens:update-rates')->everyThirtyMinutes();
//        $schedule->command('sync:forex-accounts-via-email')->everyFiveMinutes();
        
        // 4-month based IB transactions management (automatic)
        $schedule->command('ib:schedule-4month-tasks')->daily()->at('02:00')->withoutOverlapping();

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
        DeleteStaleUsers::class,
        ResetData::class,
        IBProfitRecord::class,
        MultiIbBonus::class,
        Commands\UpdateExchangeRates::class,
        Commands\UpdateTokenRates::class,
        SyncForexAccountsViaEmail::class,
        MultiLevelRebateDistribution::class,
        CreateIBTransactionsTable4Month::class,
        CopyIBTransactions4Month::class,
        ScheduleIBTransactions4Month::class,
        TestIBTransactions4Month::class,
        MigrateAllIBTransactions::class,
        TestQuarterIntegration::class,
        HighPerformanceIBMigration::class,
        ParallelIBMigration::class,
        MigrationWorker::class,
        IBMigrationMenu::class,
        FixIBTransactionDates::class,
        AutoIBMigration::class,
        TestIBQuarterSystem::class,
        RecalculateIBBalances::class,
        DebugUserIBTransactions::class,
        RemoveDuplicateIBTransactions::class,

    ];
}
