<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateAccountTypeInvestmentSnapshots extends Command
{
    protected $signature = 'generate:investment-snapshots';
    protected $description = 'Generate records in account_type_investment_snapshots table from account_type_investments';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Connect to the database
        $growmore = DB::connection('growmore'); // Custom growmore connection
        $growmoreoriginal = DB::connection('growmoreoriginal'); // growmoreoriginal connection

        // Fetch all records from the account_type_investments table
        $investments = $growmoreoriginal->table('account_type_investments')->get();

        foreach ($investments as $investment) {
            // Fetch data for account_types_data
            $accountTypePhaseId = $investment->account_type_phase_id;
            $accountTypeId = $growmoreoriginal->table('account_type_phases')
                ->where('id', $accountTypePhaseId)
                ->value('account_type_id');
            $accountTypeData = $growmoreoriginal->table('account_types')
                ->where('id', $accountTypeId)
                ->first();
                $accountTypesData = $accountTypeData ? json_encode((array) $accountTypeData) : null;

            // Fetch data for account_types_phases_data
            $accountTypePhaseData = $growmoreoriginal->table('account_type_phases')
                ->where('account_type_id', $accountTypeId)
                ->get();
            $accountTypesPhasesData = $accountTypePhaseData ? json_encode($accountTypePhaseData->toArray()) : null;

            // Fetch data for account_types_phases_rules_data
            $accountTypePhaseIds = $growmoreoriginal->table('account_type_phases')
                                    ->where('account_type_id', $accountTypeId)
                                    ->pluck('id');
            $accountTypePhaseRuleData = $growmoreoriginal->table('account_type_phase_rules')
                ->whereIn('account_type_phase_id', $accountTypePhaseIds)
                ->get();
            $accountTypesPhasesRulesData = $accountTypePhaseRuleData ? json_encode($accountTypePhaseRuleData->toArray()) : null;

            // Prepare data for insertion
            $data = [
                'account_type_investment_id' => $investment->id,
                'account_types_data' => $accountTypesData,
                'account_types_phases_data' => $accountTypesPhasesData,
                'account_types_phases_rules_data' => $accountTypesPhasesRulesData,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Insert data into account_type_investment_snapshots
            $growmoreoriginal->table('account_type_investment_snapshots')->insert($data);

            $this->info('Snapshot created for account_type_investment_id: ' . $investment->id);
        }

        $this->info('All snapshots have been generated successfully.');
    }
}
