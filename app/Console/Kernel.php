<?php

namespace App\Console;

use App\Console\Commands\IBProfitRecord;
use App\Console\Commands\MultiIbBonus;
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
        if(url('/') == 'http://brokerdemo.brokeret.com') {
            $schedule->command('reset:data')->daily();
        }

        $schedule->command('ib:record')->dailyAt('03:10');
        $schedule->command('multiIB:Bonus')->dailyAt('03:30');

//        $schedule->command('ib:record')->everyMinute();
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
        ResetData::class,
        IBProfitRecord::class,
        MultiIbBonus::class,
    ];
}
