<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserImport;
use Illuminate\Console\Command;

class KycVerifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kyc:verified';

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
        $users = UserImport::all();
        foreach ($users as $userOld){
            User::where('email',$userOld->email)->update(['kyc'=>1]);
        }
    }
}
