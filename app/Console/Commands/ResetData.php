<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\ForexAccount;
use App\Models\Notification;
use App\Models\Ranking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
//        dd(hash::make('Bestofluck@321'));
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        DB::table('accounts')->truncate();
        DB::table('countries')->truncate();
        DB::table('advertisement_materials')->truncate();
        DB::table('banners')->truncate();
        DB::table('blogs')->truncate();
        DB::table('groups')->truncate();
        DB::table('customer_groups')->truncate();
        DB::table('customer_group_has_customers')->truncate();
        DB::table('departments')->truncate();
        DB::table('department_has_staff')->truncate();
        DB::table('designations')->truncate();
        DB::table('designation_has_staff')->truncate();
        DB::table('employments')->truncate();
        DB::table('ledgers')->truncate();
        DB::table('ib_questions')->truncate();
        DB::table('ib_question_answers')->truncate();
        DB::table('ib_schemas')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('kyc_levels')->truncate();
        DB::table('kyc_sub_levels')->truncate();
        DB::table('kyc_user')->truncate();
        DB::table('kycs')->truncate();
        DB::table('black_list_countries')->truncate();
        DB::table('admin_login_activities')->truncate();
        DB::table('login_activities')->truncate();
        DB::table('transactions')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('meta_deals')->truncate();
        DB::table('meta_transactions')->truncate();
        DB::table('multi_levels')->truncate();
        DB::table('multi_level_rebate_rule')->truncate();
        DB::table('rebate_rules')->truncate();
        DB::table('rebate_rule_symbol_group')->truncate();
        DB::table('risk_profile_tags_users')->truncate();
        DB::table('risk_profile_tag_user')->truncate();
        DB::table('scheduled_task')->truncate();
        DB::table('schemas')->truncate();
        DB::table('subscriptions')->truncate();
        DB::table('swap_based_accounts')->truncate();
        DB::table('swap_free_accounts')->truncate();
        DB::table('symbols')->truncate();
        DB::table('symbol_groups')->truncate();
        DB::table('symbol_symbol_group')->truncate();
        DB::table('invests')->truncate();
        DB::table('messages')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();
        DB::table('leverage_updates')->truncate();
        DB::table('withdraw_accounts')->truncate();
        DB::table('user_ib_rules')->truncate();
        DB::table('risk_profile_tag_user')->truncate();
        DB::table('risk_books')->truncate();
        DB::table('groups')->truncate();
        DB::table('notes')->truncate();
        DB::table('system_tags')->truncate();
        DB::table('x9_client_group_types')->truncate();
        DB::table('x9_client_groups')->truncate();
        DB::table('x9_operation_types')->truncate();
        DB::table('ib_groups')->truncate();
        DB::table('ib_group_multi_level')->truncate();
        DB::table('bonuses')->truncate();
        DB::table('leverage_updates')->truncate();
        DB::table('bonus_forex_schema')->truncate();
        DB::table('bonus_transactions')->truncate();
        DB::table('bonus_deductions')->truncate();
        DB::table('user_languages')->truncate();
        DB::table('rebate_records')->truncate();
        DB::table('document_links')->truncate();
        DB::table('platform_links')->truncate();
        DB::table('ib_group_rebate_rule')->truncate();
        DB::table('social_links')->truncate();
        DB::table('socials')->truncate();
        DB::table('forex_schema_rebate_rule')->truncate();
        DB::table('staff_user')->truncate();
        DB::table('lead_sources')->truncate();
        DB::table('lead_stages')->truncate();
        DB::table('leads')->truncate();
        DB::table('levels')->truncate();
        DB::table('user_ib_rule_levels')->truncate();
        DB::table('user_ib_rule_level_shares')->truncate();
        DB::table('category_ticket')->truncate();
        DB::table('label_ticket')->truncate();
        DB::table('email_templates')->truncate();
        DB::table('deposit_methods')->truncate();
        DB::table('withdraw_methods')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Artisan::call('db:seed');

        $rank = Ranking::find(1);
        $sitename = 'qorvamarkets';

        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => 'user',
            'last_name' => $sitename,
            'username' =>  'user' . rand(10000, 99999),
            'country' => 'United Arab Emirates',
            'phone' =>  '+971',
            'email' => 'user@'.$sitename.'.com',
            'password' => Hash::make('user@12345'),
            'kyc' => 0,
            'email_verified_at' => Carbon::now(),
        ];
        $user = User::create($dataUser);

        $superAdmin = Admin::create([
            'avatar' => 'global/images/3C12ROYcX34e8dcSmzdO.png',
            'name' => 'Super Admin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@'.$sitename.'.com',
            'password' => Hash::make('qorvamarkets@2025'),
            'role' => 1,
        ]);
        $data = [
            'icon' => 'user-plus',
            'user_id' => 1,
            'for' => 'admin',
            'title' => 'Welcome to admin',
            'notice' => 'Thanks for joining us',
            'action_url' => route('admin.user.edit',$superAdmin->id),
        ];
        Notification::create($data);

    }
}
