<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\KYC;
use Illuminate\Console\Command;

class CreateKYCforExistingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-kycs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to create the KYC fields for existing users without KYCs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersWithoutKYC = User::whereNotIn('id', KYC::pluck('user_id'))->get();

        foreach ($usersWithoutKYC as $user) {
            KYC::create([
                'user_id' => $user->id,
                'method' => '',
                'data' => null,
                'status' => 'unverified',
                'verified_at' => null,
            ]);
        }

        return Command::SUCCESS;
    }
}
