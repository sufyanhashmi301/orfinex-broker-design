<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\ReferralRelationship;
use App\Models\User;
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
        ini_set('max_execution_time', 0); // No timeout
        ini_set('memory_limit', -1); // No memory limit

        $email = $this->argument('email');
        $startDate = Carbon::parse($this->argument('start_date'))->startOfDay();

        $parent = User::where('email', $email)->first();

        if (!$parent) {
            $this->error("User with email $email not found.");
            return 1;
        }

        $maxLevel = Level::max('level_order');
        $network = $this->getReferralNetwork($parent->id, $maxLevel);

        $this->info("Processing " . count($network) . " users in network of {$parent->email}");

        $chunks = collect($network)->chunk(500);

        foreach ($chunks as $index => $chunk) {
            DB::beginTransaction();
            try {
                foreach ($chunk as $childUser) {
                    $this->processUserDealsFromDate($childUser, $startDate);

                    // Free memory after processing each user
                    unset($childUser);
                    gc_collect_cycles();
                }
                DB::commit();
                Log::info("Chunk #$index processed successfully.");
            } catch (Throwable $e) {
                DB::rollBack();
                Log::error("Rebate distribution error in chunk #$index: " . $e->getMessage());
                $this->error("Chunk #$index failed: " . $e->getMessage());
            }
        }

        $this->info("Rebate distribution completed for network under {$email}.");
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

            $children = User::select(['id', 'ref_id', 'email', 'ib_group_id', 'ib_status'])
                ->where('ref_id', $currentId)
                ->get();

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
                        Log::info("Deals processed for User ID: {$childUser->id}");
                    } catch (Throwable $e) {
                        DB::rollBack();
                        Log::error("Failed to distribute deals for user {$childUser->id}: " . $e->getMessage());
                    }
                }
            }
        } catch (Throwable $e) {
            Log::error("Failed to process user {$childUser->id}: " . $e->getMessage());
        }
    }
}
