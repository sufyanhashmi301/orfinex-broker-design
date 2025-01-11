<?php

namespace App\Console\Commands;

use App\Models\AccountType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTradingDaysAtRulesLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:trading-days-at-rule-level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update trading_days from AccountType to all AccountTypePhaseRules descendants';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to update trading_days for AccountTypePhaseRules...');

        // Loop through all AccountTypes
        $accountTypes = AccountType::where('trading_days', '!=', null)->get();

        foreach ($accountTypes as $accountType) {
            $tradingDays = $accountType->trading_days;

            $this->info("Processing AccountType ID: {$accountType->id} with trading_days: {$tradingDays}");

            // Loop through AccountTypePhases related to the AccountType
            foreach ($accountType->accountTypePhases as $phase) {
                $this->info("Processing AccountTypePhase ID: {$phase->id}");

                // Update trading_days for each AccountTypePhaseRules
                foreach ($phase->accountTypePhaseRules as $rule) {
                    $rule->trading_days = $tradingDays;
                    $rule->save();

                    $this->info("Updated AccountTypePhaseRule ID: {$rule->id} with trading_days: {$tradingDays}");
                }
            }
        }

        $this->info('Trading days updated successfully for all AccountTypePhaseRules.');
        DB::statement('ALTER TABLE account_types DROP COLUMN trading_days');
        return Command::SUCCESS;
    }
}
