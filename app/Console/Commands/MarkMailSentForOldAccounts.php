<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Models\AccountTypeInvestment;
use Illuminate\Console\Command;

class MarkMailSentForOldAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail-sent:old-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command to mark all the existing Passed and Violated accounts as sent.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        AccountTypeInvestment::whereIn('status', [
            InvestmentStatus::PASSED,
            InvestmentStatus::VIOLATED,
        ])->update(['mail_sent' => 1]);

        return Command::SUCCESS;
    }
}
