<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;

class ScheduleIBTransactions4Month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:schedule-4month-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically handle 4-month IB transactions table creation and data migration based on current date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $now = Carbon::now();
            $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
            
            $this->info("Current period: " . IBTransactionPeriodService::getPeriodName($currentPeriod));
            
            // Always ensure current period table exists
            $this->call('ib:create-transactions-table-4month', ['period' => $currentPeriod]);
            
            // Check if we should create next period table (in the last month of current period)
            if (IBTransactionPeriodService::shouldCreateNextPeriodTable()) {
                $nextPeriod = IBTransactionPeriodService::getNextPeriod($currentPeriod);
                $this->info("Creating next period table: " . IBTransactionPeriodService::getPeriodName($nextPeriod));
                $this->call('ib:create-transactions-table-4month', ['period' => $nextPeriod]);
            }
            
            // Copy transactions for current period
            $this->info("Copying transactions for current period...");
            $this->call('copy:ib-transactions-4month', ['period' => $currentPeriod]);
            
            // If we're at the end of a period, also copy any remaining transactions from previous period
            if ($this->isEndOfPeriod($now)) {
                $previousPeriod = IBTransactionPeriodService::getPreviousPeriod($currentPeriod);
                $this->info("End of period detected. Copying remaining transactions from: " . IBTransactionPeriodService::getPeriodName($previousPeriod));
                $this->call('copy:ib-transactions-4month', ['period' => $previousPeriod]);
            }
            
            $this->info("4-month IB transactions scheduling completed successfully.");
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error in 4-month IB transactions scheduling: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Check if we're at the end of a 4-month period
     *
     * @param Carbon $date
     * @return bool
     */
    private function isEndOfPeriod(Carbon $date)
    {
        $month = $date->month;
        $day = $date->day;
        
        // Check if we're in the last few days of a period-ending month
        return (
            ($month === 4 && $day >= 28) ||  // End of Q1 (Jan-Apr)
            ($month === 8 && $day >= 28) ||  // End of Q2 (May-Aug)
            ($month === 12 && $day >= 28)    // End of Q3 (Sep-Dec)
        );
    }
}

