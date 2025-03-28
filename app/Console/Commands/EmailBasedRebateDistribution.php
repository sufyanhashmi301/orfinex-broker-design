<?php

namespace App\Console\Commands;

use App\Models\ReferralRelationship;
use App\Models\User;
use App\Models\MetaDeal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $network = $this->getReferralNetwork($parent->id, 3);
//        dd(count($network));

        DB::beginTransaction();
        try {
            foreach ($network as $childUser) {
//                dd($childUser);
                $this->processUserDealsFromDate($childUser, $startDate);
            }

            DB::commit();
            $this->info("Rebate distribution completed for network under $email.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmailBasedRebateDistribution Error: ' . $e->getMessage());
            $this->error("Rebate distribution failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function getReferralNetwork($parentId, $maxDepth = 3)
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
        $referralRelationship = $childUser->referralRelationship->referralLink;
//        dd($referralRelationship->user);

        if (!$referralRelationship) {
            return;
        }

        $validParentData = $this->getValidParent($referralRelationship->user);
//        dd($validParentData);

        if (!$validParentData) {
            return;
        }

        $notedParent = $validParentData['user'];
        $notedLevel = $validParentData['level'];

        $forexSchemas = $notedParent->ibGroup->rebateRules()
            ->with('forexSchemas')
            ->get()
            ->flatMap(fn($rebateRule) => $rebateRule->forexSchemas)
            ->pluck('id')
            ->unique();

        $realForexAccounts = \App\Models\ForexAccount::realActiveAccount($childUser->id)
            ->whereIn('forex_schema_id', $forexSchemas)
            ->get();

        foreach ($realForexAccounts as $realForexAccount) {
            $symbols = $this->getUserAssignedSymbols($notedParent, $realForexAccount);
            $deals = $this->getMT5Deals($realForexAccount->login, $startDate, $symbols);

            if (!$deals->isEmpty()) {
                $this->saveMT5Deals($deals, $childUser->id, $referralRelationship, $notedParent, $notedLevel, $realForexAccount);
            }
        }
    }
}
