<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Models\MetaDeal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailBasedRebateDistribution extends MultiLevelRebateDistribution
{
    protected $signature = 'rebate:email-distribution {email} {start_date}';
    protected $description = 'Distribute rebate based on parent email and 3-level ref network from a start date';

    public function handle()
    {
        $email = $this->argument('email');
        $startDate = Carbon::parse($this->argument('start_date'))->startOfDay();

        $parent = User::where('email', $email)->first();

        if (!$parent) {
            $this->error("User with email $email not found.");
            return 1;
        }

        $maxLevel = Level::max('level_order');
        $network = $this->getReferralNetwork($parent->id, $maxLevel);

        $chunks = collect($network)->chunk(500);

        foreach ($chunks as $chunk) {
            DB::beginTransaction();
            try {
                foreach ($chunk as $childUser) {
                    $this->processUserDealsFromDate($childUser, $startDate);
                }
                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                Log::error("Rebate distribution error for chunk: " . $e->getMessage());
            }
        }

        $this->info("Rebate distribution completed for network under $email.");
        return 0;
    }

    protected function getReferralNetwork($parentId, $maxDepth = 0)
    {
        $result = [];
        $queue = [[$parentId, 0]];

        while (!empty($queue)) {
            [$currentId, $depth] = array_shift($queue);

            if ($depth >= $maxDepth) {
                continue;
            }

            $children = User::where('ref_id', $currentId)->get();

            foreach ($children as $child) {
                $result[] = $child;
                $queue[] = [$child->id, $depth + 1];
            }
        }

        return $result;
    }

    protected function processUserDealsFromDate($childUser, $startDate)
    {
        try {
            $referralRelationship = $childUser->referralRelationship;

            if (!$referralRelationship) return;

            $validParentData = $this->getValidParent($referralRelationship->user);
            if (!$validParentData) return;

            $notedParent = $validParentData['user'];
            $notedLevel = $validParentData['level'];

            $forexSchemas = $notedParent->ibGroup->rebateRules()
                ->with('forexSchemas')
                ->get()
                ->flatMap(fn($r) => $r->forexSchemas)
                ->pluck('id')
                ->unique();

            $realForexAccounts = \App\Models\ForexAccount::realActiveAccount($childUser->id)
                ->whereIn('forex_schema_id', $forexSchemas)
                ->get();

            foreach ($realForexAccounts as $realForexAccount) {
                $symbols = $this->getUserAssignedSymbols($notedParent, $realForexAccount);
                $deals = $this->getMT5Deals($realForexAccount->login, $startDate, $symbols);

                if (!$deals->isEmpty()) {
                    DB::beginTransaction();
                    try {
                        $this->saveAndDistributeDeals($deals, $childUser->id, $referralRelationship, $notedParent, $notedLevel, $realForexAccount);
                        DB::commit();
                    } catch (Throwable $e) {
                        DB::rollBack();
                        Log::error("Deal distribution failed for user {$childUser->id}: {$e->getMessage()}");
                    }
                }
            }
        } catch (Throwable $e) {
            Log::error("User processing error for user {$childUser->id}: {$e->getMessage()}");
        }
    }
}
