<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\ForexAccount;
use App\Models\Notification;
use App\Models\Ranking;
use App\Models\User;
use Carbon\Carbon;
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
        DB::table('account_types')->truncate();
        DB::table('	account_type_investments')->truncate();
        DB::table('account_type_investment_hourly_stats_records')->truncate();
        DB::table('account_type_investment_snapshots')->truncate();
        DB::table('account_type_investment_stats')->truncate();
        DB::table('account_type_phases')->truncate();
        DB::table('account_type_phase_rules')->truncate();
        DB::table('risk_profile_tags_users')->truncate();
        DB::table('admins')->truncate();
        DB::table('risk_profile_tags_users')->truncate();
        DB::table('accounts')->truncate();
        DB::table('ledgers')->truncate();
        DB::table('users')->truncate();
        DB::table('transactions')->truncate();
        DB::table('old_transactions')->truncate();
        DB::table('meta_transactions')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('forex_schemas')->truncate();
        DB::table('forex_schema_phases')->truncate();
        DB::table('forex_schema_phases')->truncate();
        DB::table('forex_schema_phase_rules')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('messages')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();
        DB::table('withdraw_accounts')->truncate();
        DB::table('admin_login_activities')->truncate();
        DB::table('login_activities')->truncate();
        DB::table('meta_deals')->truncate();
        DB::table('multi_levels')->truncate();
        DB::table('multi_level_rebate_rule')->truncate();
        DB::table('rebate_rules')->truncate();
        DB::table('rebate_rule_symbol_group')->truncate();
        DB::table('symbols')->truncate();
        DB::table('symbol_groups')->truncate();
        DB::table('symbol_symbol_group')->truncate();
        DB::table('black_list_countries')->truncate();
        DB::table('customer_group_has_customers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rank = Ranking::find(1);
        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => 'user',
            'last_name' => 'growmorefund',
            'username' =>  'user' . rand(10000, 99999),
            'country' => 'United Arab Emirates',
            'phone' =>  '+971',
            'email' => 'user@growmorefund.com',
            'password' => Hash::make(12345678),
            'kyc' => 0,
            'email_verified_at' => Carbon::now(),
        ];
        $user = User::create($dataUser);

        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => 'sufyan',
            'last_name' => 'hashmi',
            'username' =>  'user' . rand(10000, 99999),
            'country' => 'United Arab Emirates',
            'phone' =>  '+971',
            'email' => 'sufyanhashmi301@gmail.com',
            'password' => Hash::make(12345678),
            'kyc' => 0,
            'email_verified_at' => Carbon::now(),
        ];
        $user = User::create($dataUser);

        $superAdmin = Admin::create([
            'avatar' => 'global/images/3C12ROYcX34e8dcSmzdO.png',
            'name' => 'Super Admin',
            'first_name' => 'Super Admin',
            'last_name' => 'Super Admin',
            'email' => 'admin@growmorefund.com',
            'password' => Hash::make(12345678),
            'role' => 1,
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
