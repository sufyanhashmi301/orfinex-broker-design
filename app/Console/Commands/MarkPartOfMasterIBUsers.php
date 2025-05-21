<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MarkPartOfMasterIBUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:mark-part-of-master-ib';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users and their network as part of Master IB if IB is approved and IB group ID exists';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Starting Part of Master IB sync...");

            $users = User::whereNotNull('ib_group_id')
                ->where('ib_status', 'approved')
                ->with(['user_metas', 'referrals'])
                ->get();

            foreach ($users as $user) {
                if (!$user->ib_group_id) {
                    $this->warn("User {$user->id} skipped — no ib_group_id.");
                    continue;
                }

                $user->user_metas()->updateOrCreate(
                    ['meta_key' => 'is_part_of_master_ib'],
                    ['meta_value' => $user->ib_group_id]
                );

                foreach ($user->referrals as $referral) {
                    if (!$referral->ib_group_id || $referral->ib_status !== 'approved') {
                        $this->warn("Referral {$referral->id} skipped — no ib_group_id or not approved.");
                        continue;
                    }

                    $referral->user_metas()->updateOrCreate(
                        ['meta_key' => 'is_part_of_master_ib'],
                        ['meta_value' => $referral->ib_group_id]
                    );
                }
            }

            $this->info("Part of Master IB sync complete.");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
