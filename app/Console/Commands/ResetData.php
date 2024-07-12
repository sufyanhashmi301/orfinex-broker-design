<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\ForexAccount;
use App\Models\Notification;
use App\Models\Ranking;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('risk_profile_tags_users')->truncate();
        DB::table('users')->truncate();
        DB::table('admin_login_activities')->truncate();
        DB::table('login_activities')->truncate();
        DB::table('transactions')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('invests')->truncate();
        DB::table('messages')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();
        DB::table('withdraw_accounts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rank = Ranking::find(1);
        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => 'user_f',
            'last_name' => 'user_l',
            'username' =>  'user' . rand(10000, 99999),
            'country' => 'United Arab Emirates',
            'phone' =>  '+971',
            'email' => 'user@banexcapital.com',
            'password' => Hash::make(12345678),
            'kyc' => 0,
        ];
        $user = User::create($dataUser);

        $superAdmin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@banexcapital.com',
            'password' => Hash::make(12345678),
        ]);
        $data = [
            'icon' => 'user-plus',
            'user_id' => 1,
            'for' => 'admin',
            'title' => 'Wellcome to admin',
            'notice' => 'Thanks for joining us',
            'action_url' => 'https://banexcapital.com/admin/user/1/edit',
        ];
        Notification::create($data);



    }
}
