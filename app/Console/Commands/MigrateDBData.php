<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDBData extends Command
{
    protected $signature = 'migrate:dbdata';
    protected $description = 'Migrate data from growmore to growmoreoriginal database';

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
        // Connect to both databases using the custom connections
        $growmore = DB::connection('growmore'); // growmore DB connection
        $growmoreoriginal = DB::connection('growmoreoriginal'); // growmoreoriginal DB connection

        // Fetch data from growmore
        $records = $growmore->table('forex_schema_investments')->where('group', 'real\\plan1a-100k')->where('id', '>', 1202)->get();
        // dd($records);
        foreach ($records as $record) {
            // Check account_type_phase_id from account_type_phase_rules table
            $accountTypePhaseId = $growmore->table('forex_schema_phase_rules')
                ->where('id', $record->forex_schema_phase_rule_id)
                ->value('forex_schema_phase_id');

            // Prepare data for insertion
            $data = [
                'unique_id' => Str::random(8), // Generate 8 characters random string
                'user_id' => $record->user_id,
                'account_name' => $record->account_name,
                'account_type_phase_rule_id' => $record->forex_schema_phase_rule_id,
                'account_type_phase_id' => $accountTypePhaseId,
                'trader_type' => $record->trader_type,
                'login' => $record->login,
                'platform_group' => $record->group,
                'total' => $record->total,
                'phase_started_at' => $record->term_start,
                'phase_ended_at' => null, // No data to map, setting to null
                'currency' => $record->currency,
                'main_password' => $record->main_password,
                'status' => $record->status,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ];

            // Insert data into growmoreoriginal
            $growmoreoriginal->table('account_type_investments')->insert($data);

            $this->info('Data transferred for login: ' . $record->login);
        }

        $this->info('Data migration completed successfully.');
    }
}
