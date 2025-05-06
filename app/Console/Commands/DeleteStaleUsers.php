<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Scopes\ExcludeGracePeriodScope;
use Illuminate\Console\Command;

class DeleteStaleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-stale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::withoutGlobalScope(ExcludeGracePeriodScope::class)
//            ->where('in_grace_period', true)
//            ->whereNotNull('email_verified_at')
            ->update(['in_grace_period' => false]);

        // STEP 1: Remove grace period from users who are active (email verified + any activity)
        $activeUsers = \App\Models\User::withoutGlobalScope(ExcludeGracePeriodScope::class)
            ->where('in_grace_period', true)
            ->where(function ($query) {
                $query->whereNotNull('email_verified_at')
                    ->orWhereHas('transaction')
                    ->orWhereHas('ForexAccounts'); // forex_accounts
            })
            ->get();

        foreach ($activeUsers as $user) {
            $user->in_grace_period = false;
            $user->save();
            $this->info("Grace period removed from user: {$user->email}");
        }

        // STEP 2: Delete users who are stale (unverified + no activity)
        $staleUsers = \App\Models\User::withoutGlobalScope(ExcludeGracePeriodScope::class)
            ->where('in_grace_period', true)
            ->whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(setting('user_removal_grace_period', 'customer_misc', 1)))
            ->whereDoesntHave('transaction')
            ->whereDoesntHave('ForexAccounts') // forex_accounts
            ->get();

        foreach ($staleUsers as $user) {
            // Delete ReferralRelationships via ReferralLink
            $referralLinks = \App\Models\ReferralLink::where('user_id', $user->id)->get();

            foreach ($referralLinks as $link) {
                // Delete related referral relationships
                $link->relationships()->delete();
                $link->delete();
            }

            // Delete any referral relationships where this user was referred
            \App\Models\ReferralRelationship::where('user_id', $user->id)->delete();

            // Finally delete the user
            $user->delete();

            $this->info("Deleted stale user and associated referral data: {$user->email}");
        }

        return 0;
    }
}
