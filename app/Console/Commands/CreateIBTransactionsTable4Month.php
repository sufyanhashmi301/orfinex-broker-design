<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;

class CreateIBTransactionsTable4Month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:create-transactions-table-4month {period?} {--auto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create IB transactions table for a given 4-month period (e.g., 2025_q1, 2025_q2, 2025_q3)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $period = $this->argument('period');
            $isAuto = $this->option('auto');

            // If no period provided, use current period
            if (!$period) {
                $period = IBTransactionPeriodService::getCurrentPeriod();
                $this->info("No period specified, using current period: {$period}");
            }

            // Validate period format
            if (!$this->isValidPeriod($period)) {
                $this->error('Invalid period format. Use format like: 2025_q1, 2025_q2, or 2025_q3');
                return Command::FAILURE;
            }

            // Parse period to validate year
            $parsed = IBTransactionPeriodService::parsePeriod($period);
            if ($parsed['year'] < 2025) {
                $this->error('Year must be 2025 or later');
                return Command::FAILURE;
            }

            // Create the table
            $result = IBTransactionService::createIBTransactionTable4Month($period);
            
            if ($result) {
                $periodName = IBTransactionPeriodService::getPeriodName($period);
                $this->info("IB transactions table for {$periodName} created successfully.");
                
                // If auto mode and we should create next period table, create it
                if ($isAuto && IBTransactionPeriodService::shouldCreateNextPeriodTable()) {
                    $nextPeriod = IBTransactionPeriodService::getNextPeriod($period);
                    $nextResult = IBTransactionService::createIBTransactionTable4Month($nextPeriod);
                    
                    if ($nextResult) {
                        $nextPeriodName = IBTransactionPeriodService::getPeriodName($nextPeriod);
                        $this->info("Next period table for {$nextPeriodName} also created successfully.");
                    } else {
                        $this->warn("Failed to create next period table for {$nextPeriod}.");
                    }
                }
            } else {
                $this->error("Failed to create IB transactions table for {$period}.");
                return Command::FAILURE;
            }

            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error creating IB transactions table: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Validate period format
     *
     * @param string $period
     * @return bool
     */
    private function isValidPeriod($period)
    {
        return preg_match('/^\d{4}_q[1-3]$/', $period);
    }
}

