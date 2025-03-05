<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ForexAccount;
use App\Models\ForexSchema;

class UpdateForexAccounts extends Command
{
    protected $signature = 'forex:update-schemas';
    protected $description = 'Update forex_accounts with the correct forex_schema_id based on group matching forex_schemas->real_swap_free';

    public function handle()
    {
        $accounts = ForexAccount::where('account_type', 'real')->get();

        foreach ($accounts as $account) {
            $schema = ForexSchema::where('real_swap_free', $account->group)->first();

            if ($schema) {
                $account->forex_schema_id = $schema->id;
                $account->save();
                $this->info("Updated account ID {$account->id} with schema ID {$schema->id}");
            } else {
                $this->warn("No matching schema found for group: {$account->group}");
            }
        }
    }
}
