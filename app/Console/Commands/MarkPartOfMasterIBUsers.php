<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\UserIbNetworkService;

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

            $service = app(UserIbNetworkService::class);

            $users = User::whereNotNull('ib_group_id')
                ->where('ib_status', 'approved')
                ->get();

            $totalUpdated = 0;

            foreach ($users as $user) {
                $count = $service->syncMeta($user, 'is_part_of_master_ib', $user->ib_group_id);
                $totalUpdated += $count;

                $this->info("User {$user->id} and {$count} in network updated.");
            }

            $this->info("Sync complete. Total users updated: $totalUpdated");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
