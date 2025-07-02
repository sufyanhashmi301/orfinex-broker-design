<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionService;

class CreateIBTransactionsTableYearly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:create-transactions-table {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create IB transactions table for a given year';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');

        if (!is_numeric($year) || strlen($year) != 4) {
            $this->error('Invalid year provided. Must be a 4-digit number like 2025.');
            return Command::FAILURE;
        }

        if ($year < 2025) {
            $this->error('Year must be greater than 2025');
            return Command::FAILURE;
        }

        $result = IBTransactionService::createIBTransactionTable($year);
        
        if ($result) {
            $this->info("IB transactions table for {$year} created successfully.");
        } else {
            $this->error("Failed to create IB transactions table for {$year}.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
