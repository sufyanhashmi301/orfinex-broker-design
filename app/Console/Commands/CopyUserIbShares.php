<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserIbRule;
use App\Models\UserIbRuleLevel;
use App\Models\UserIbRuleLevelShare;

class CopyUserIbShares extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:user-ib-shares';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy users having sub_ib_share > 0 and create UserIbRuleLevelShare for level_id = 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch all UserIbRule where sub_ib_share > 0
        $userIbRules = UserIbRule::where('sub_ib_share', '>', 0)->get();
//        dd($userIbRules->count());

        if ($userIbRules->isEmpty()) {
            $this->info('No users found with sub_ib_share greater than 0.');
            return;
        }

        foreach ($userIbRules as $userIbRule) {
            // Find or create UserIbRuleLevel for level_id = 1
            $userIbRuleLevel = UserIbRuleLevel::firstOrCreate([
                'user_ib_rule_id' => $userIbRule->id,
                'level_id' => 1,
            ]);
//            dd($userIbRuleLevel);

            // Create or update UserIbRuleLevelShare for level_id = 1
            UserIbRuleLevelShare::updateOrCreate(
                [
                    'user_ib_rule_level_id' => $userIbRuleLevel->id,
                    'level_id' => 1,
                ],
                [
                    'share' => $userIbRule->sub_ib_share,
                ]
            );

            $this->info("Processed UserIbRule ID: {$userIbRule->id}, Share: {$userIbRule->sub_ib_share}");
        }

        $this->info('UserIbRuleLevelShares updated successfully for level_id = 1.');
    }
}
