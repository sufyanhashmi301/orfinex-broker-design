<?php

namespace App\Console\Commands;

use App\Services\ContractService;
use Illuminate\Console\Command;

class CheckContractExpiry extends Command
{

    protected $contract;

    public function __construct(ContractService $contract)
    {
        parent::__construct();
        $this->contract = $contract;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:contract-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use to check the expiry of the contracts.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->contract->checkExpired();

        $this->info('Contract Expiry Checked!');
        return Command::SUCCESS;
    }
}
