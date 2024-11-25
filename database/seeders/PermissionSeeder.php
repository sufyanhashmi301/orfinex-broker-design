<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Disable foreign key checks
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');

       // Truncate the permissions table
       DB::table('permissions')->truncate();

       // Re-enable foreign key checks
       DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [

            ['category' => 'Customer Management', 'name' => 'customer-list'],
            ['category' => 'Customer Management', 'name' => 'customer-create'],
            ['category' => 'Customer Management', 'name' => 'customer-edit'],
            ['category' => 'Customer Management', 'name' => 'customer-export'],
            ['category' => 'Customer Management', 'name' => 'customer-login'],
            ['category' => 'Customer Management', 'name' => 'customer-mail-send'],
            ['category' => 'Customer Management', 'name' => 'customer-basic-manage'],
            ['category' => 'Customer Management', 'name' => 'customer-balance-add-or-subtract'],
            ['category' => 'Customer Management', 'name' => 'customer-change-password'],
            ['category' => 'Customer Management', 'name' => 'all-type-status'],

            ['category' => 'Kyc Management', 'name' => 'kyc-list'],
            ['category' => 'Kyc Management', 'name' => 'kyc-action'],
            ['category' => 'Kyc Management', 'name' => 'kyc-export'],
            ['category' => 'Kyc Management', 'name' => 'kyc-form-manage'],

            ['category' => 'Role Management', 'name' => 'role-list'],
            ['category' => 'Role Management', 'name' => 'role-create'],
            ['category' => 'Role Management', 'name' => 'role-edit'],
            ['category' => 'Role Management', 'name' => 'role-delete'],

            ['category' => 'Staff Management', 'name' => 'staff-list'],
            ['category' => 'Staff Management', 'name' => 'staff-create'],
            ['category' => 'Staff Management', 'name' => 'staff-edit'],
            ['category' => 'Staff Management', 'name' => 'staff-delete'],

            ['category' => 'Account Type Management', 'name' => 'schema-list'],
            ['category' => 'Account Type Management', 'name' => 'schema-create'],
            ['category' => 'Account Type Management', 'name' => 'schema-edit'],
            ['category' => 'Account Type Management', 'name' => 'schema-delete'],

            ['category' => 'Accounts Management', 'name' => 'accounts-list'],
            ['category' => 'Accounts Management', 'name' => 'leverage-list'],
            ['category' => 'Accounts Management', 'name' => 'accounts-action'],
            ['category' => 'Accounts Management', 'name' => 'leverage-action'],
            ['category' => 'Accounts Management', 'name' => 'accounts-export'],

            ['category' => 'Transaction Management', 'name' => 'transaction-list'],
            ['category' => 'Transaction Management', 'name' => 'transaction-action'],
            ['category' => 'Transaction Management', 'name' => 'transaction-export'],

            ['category' => 'Partnership Levels Management', 'name' => 'levels-list'],

            ['category' => 'Partnership Symbols', 'name' => 'symbols-list'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-create'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-edit'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-delete'],

            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-list'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-create'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-edit'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-delete'],

            ['category' => 'Deposit Management', 'name' => 'automatic-gateway-manage'],
            ['category' => 'Deposit Management', 'name' => 'manual-gateway-manage'],
            ['category' => 'Deposit Management', 'name' => 'deposit-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-export'],
            ['category' => 'Deposit Management', 'name' => 'deposit-action'],

            ['category' => 'Withdraw Management', 'name' => 'withdraw-list'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-method-manage'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-action'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-export'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-schedule'],

            ['category' => 'Referral Management', 'name' => 'target-manage'],
            ['category' => 'Referral Management', 'name' => 'referral-create'],
            ['category' => 'Referral Management', 'name' => 'referral-list'],
            ['category' => 'Referral Management', 'name' => 'referral-edit'],
            ['category' => 'Referral Management', 'name' => 'referral-delete'],

            ['category' => 'IB Management', 'name' => 'ib-list'],
            ['category' => 'IB Management', 'name' => 'ib-action'],
            ['category' => 'IB Management', 'name' => 'ib-export'],
            ['category' => 'IB Management', 'name' => 'ib-form-manage'],
            ['category' => 'IB Management', 'name' => 'advertisement-material-edit'],

            ['category' => 'Ranking Management', 'name' => 'ranking-list'],
            ['category' => 'Ranking Management', 'name' => 'ranking-create'],
            ['category' => 'Ranking Management', 'name' => 'ranking-edit'],

            ['category' => 'Frontend Management', 'name' => 'landing-page-manage'],
            ['category' => 'Frontend Management', 'name' => 'page-manage'],
            ['category' => 'Frontend Management', 'name' => 'footer-manage'],
            ['category' => 'Frontend Management', 'name' => 'navigation-manage'],

            ['category' => 'Subscriber Management', 'name' => 'subscriber-list'],
            ['category' => 'Subscriber Management', 'name' => 'subscriber-mail-send'],

            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-list'],
            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-action'],

            ['category' => 'Setting Management', 'name' => 'site-setting'],
            ['category' => 'Setting Management', 'name' => 'email-setting'],
            ['category' => 'Setting Management', 'name' => 'plugin-setting'],
            ['category' => 'Setting Management', 'name' => 'language-setting'],
            ['category' => 'Setting Management', 'name' => 'page-setting'],
            ['category' => 'Setting Management', 'name' => 'custom-css'],
            ['category' => 'Setting Management', 'name' => 'email-template'],

            ['category' => 'Risk Profile Tags', 'name' => 'risk-profile-create'],
            ['category' => 'Risk Profile Tags', 'name' => 'risk-profile-list'],
            ['category' => 'Risk Profile Tags', 'name' => 'risk-profile-edit'],
            ['category' => 'Risk Profile Tags', 'name' => 'risk-profile-delete'],

            ['category' => 'System Tags', 'name' => 'system-tag-create'],
            ['category' => 'System Tags', 'name' => 'system-tag-list'],
            ['category' => 'System Tags', 'name' => 'system-tag-edit'],
            ['category' => 'System Tags', 'name' => 'system-tag-delete'],

            ['category' => 'IB Groups', 'name' => 'ib-group-create'],
            ['category' => 'IB Groups', 'name' => 'ib-group-list'],
            ['category' => 'IB Groups', 'name' => 'ib-group-edit'],
            ['category' => 'IB Groups', 'name' => 'ib-group-delete'],

            ['category' => 'Customer Groups', 'name' => 'customer-group-create'],
            ['category' => 'Customer Groups', 'name' => 'customer-group-list'],
            ['category' => 'Customer Groups', 'name' => 'customer-group-edit'],
            ['category' => 'Customer Groups', 'name' => 'customer-group-delete'],


        ];

        foreach ($permissions as $permission) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission['name'], 'guard_name' => 'admin'],
                    ['category' => $permission['category']]
                );
            }
        }
    }
}
