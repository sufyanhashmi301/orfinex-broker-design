<?php

namespace App\Console\Commands;

use App\Models\ForexAccount;
use App\Models\User;
use Illuminate\Console\Command;

class ResetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:data';

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
        $user = User::find(3007);
        $user->city = 'phalia';
        $user->save();
//        ForexAccount::whereNotIn('user_id',[1])->delete();
    }
}
