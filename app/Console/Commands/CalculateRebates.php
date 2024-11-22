<?php

namespace App\Console\Commands;

use App\Events\IBDistributionEvent;
use App\Models\ForexAccount;
use App\Models\ReferralRelationship;
use App\Models\Symbol;
use App\Models\User;
use App\Models\RebateRecord; // Model for rebate records
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateRebates extends Command
{
    protected $signature = 'rebates:calculate';
    protected $description = 'Calculate and distribute rebates based on multi-level relationships and rebate rules';

    public function handle()
    {
        // Step 1: Get all users with a multi_level_id from ReferralRelationship
        $users = User::whereHas('referralRelationship', function ($query) {
            $query->whereNotNull('multi_level_id');
        })->get();

        foreach ($users as $user) {
            // Step 2: Get unique symbols for this user's multi_level_id
            $symbols = $this->getUserUniqueSymbols($user);
//            dd($symbols);

            // Process each account's deals and rebate calculations
            $this->processUserDeals($user, $symbols);
        }

        $this->info("Rebate calculation and distribution completed.");
    }

    private function getUserUniqueSymbols($user)
    {
        $multiLevelIds = ReferralRelationship::where('user_id', $user->id)
            ->pluck('multi_level_id')
            ->unique();

        return Symbol::whereHas('symbolGroups.rebateRule.multiLevels', function ($query) use ($multiLevelIds) {
            $query->whereIn('multi_levels.id', $multiLevelIds);
        })->pluck('symbol')->unique('symbol')->toArray();
    }


    private function processUserDeals($user, $symbols)
    {
        // Step 3: Get real accounts of the user from forex_accounts
        $forexAccounts = ForexAccount::realActiveAccount($user->id)->traderType()->get();
//        dd($forexAccounts);

        foreach ($forexAccounts as $account) {
            $login = $account->login;
            // Step 4: Check for the last saved record for this login and symbol
            $lastSavedDate = RebateRecord::where('login', $login)
                ->orderByDesc('record_at')
                ->value('record_at');

            // If no records found, start from a default date, else use last saved date + 1 day
            $startDate = $lastSavedDate ? Carbon::parse($lastSavedDate)->addDay() : Carbon::now()->subDays(1);
//dd($startDate);
            while ($startDate->lessThanOrEqualTo(Carbon::now())) {
                // Step 5: Call the API to get deals
                $deals = $this->getDeals($login, $startDate->toDateString());
//                dd($deals);


                foreach ($deals as $deal) {
                    $symbol = $deal['symbol'];
                    $profit = $deal['profit'];

                    if (in_array($symbol,$symbols)) {
//                        dd($symbol,$symbols);
                        // Step 6: Dispatch the IBDistributionEvent
                        IBDistributionEvent::dispatch($profit, $user->id, $login);

                        // Step 7: Save the record in RebateRecord
                        $this->saveRebateRecord($user->id, $login, $deal, $profit);
                    }
                }

                $startDate->addDay();
            }
        }
    }

    private function saveRebateRecord($userId, $login, $deal, $profit)
    {
        RebateRecord::create([
            'user_id' => $userId,
            'login' => $login,
            'deal' => $deal['deal_id'],
            'symbol' => $deal['symbol'],
            'amount' => $profit,
            'rebate_amount' => $profit * 0.1, // Example calculation, adjust as needed
            'final_amount' => $profit * 1.1, // Example calculation, adjust as needed
            'currency' => base_currency(),
            'record_at' => Carbon::now(),
            'cleared_at' => null,
        ]);
    }

    private function getDeals($login, $date)
    {
        // Simulating API call; replace with actual API call logic
        // Example: return Http::get("https://api.example.com/getdeals?login=$login&date=$date")->json();
        return [
            [
                'deal_id' => 1234,
                'symbol' => 'AUDCAD',
                'profit' => 100.5,
            ],
            // Add more deals as necessary
        ];
    }
}
