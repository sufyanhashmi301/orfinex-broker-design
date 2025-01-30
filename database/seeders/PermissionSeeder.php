<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [

            ['category' => 'Customer Management', 'name' => 'customer-list'],
            ['category' => 'Customer Management', 'name' => 'customer-login'],
            ['category' => 'Customer Management', 'name' => 'customer-mail-send'],
            ['category' => 'Customer Management', 'name' => 'customer-basic-manage'],
            // ['category' => 'Customer Management', 'name' => 'customer-balance-add-or-subtract'],
            ['category' => 'Customer Management', 'name' => 'customer-change-password'],
            ['category' => 'Customer Management', 'name' => 'all-type-status'],

            ['category' => 'Kyc Management', 'name' => 'kyc-list'],
            ['category' => 'Kyc Management', 'name' => 'kyc-action'],
            ['category' => 'Kyc Management', 'name' => 'kyc-form-manage'],

            ['category' => 'Account Type Management', 'name' => 'account-type-list'],
            ['category' => 'Account Type Management', 'name' => 'account-type-create'],
            ['category' => 'Account Type Management', 'name' => 'account-type-edit'],
            ['category' => 'Account Type Management', 'name' => 'account-type-delete'],

            ['category' => 'Account Management', 'name' => 'account-list'],
            ['category' => 'Account Management', 'name' => 'account-trading-history'],

            ['category' => 'Account Activity Management', 'name' => 'account-activity-list'],
            ['category' => 'Account Activity Management', 'name' => 'account-activity-approval'],

            ['category' => 'Risk Hub Management', 'name' => 'risk-hub-view'],

            ['category' => 'Certificate Management', 'name' => 'certificate-manage'],
            ['category' => 'Certificate Management', 'name' => 'certificate-config'],
            ['category' => 'Certificate Management', 'name' => 'certificate-edit'],
            ['category' => 'Certificate Management', 'name' => 'certificate-awarded-list'],
            ['category' => 'Certificate Management', 'name' => 'certificate-awarded-view'],

            ['category' => 'Contract Management', 'name' => 'contract-list'],
            ['category' => 'Contract Management', 'name' => 'contract-view'],
            ['category' => 'Contract Management', 'name' => 'contract-edit'],

            ['category' => 'Addon Management', 'name' => 'addon-list'],
            ['category' => 'Addon Management', 'name' => 'addon-edit'],

            ['category' => 'Affiliate Management', 'name' => 'affiliate-list'],
            ['category' => 'Affiliate Management', 'name' => 'affiliate-config'],

            ['category' => 'Discount Code Management', 'name' => 'discount-code-list'],
            ['category' => 'Discount Code Management', 'name' => 'discount-code-create'],
            ['category' => 'Discount Code Management', 'name' => 'discount-code-edit'],
            ['category' => 'Discount Code Management', 'name' => 'discount-code-delete'],

            ['category' => 'Leaderboard Management', 'name' => 'leaderboard-view'],
            ['category' => 'Leaderboard Management', 'name' => 'leaderboard-badge-edit'],
            ['category' => 'Leaderboard Management', 'name' => 'leaderboard-ranking-create'],
            ['category' => 'Leaderboard Management', 'name' => 'leaderboard-ranking-edit'],
            ['category' => 'Leaderboard Management', 'name' => 'leaderboard-ranking-delete'],

            ['category' => 'Role Management', 'name' => 'role-list'],
            ['category' => 'Role Management', 'name' => 'role-create'],
            ['category' => 'Role Management', 'name' => 'role-edit'],
            ['category' => 'Role Management', 'name' => 'role-delete'],

            ['category' => 'Staff Management', 'name' => 'staff-list'],
            ['category' => 'Staff Management', 'name' => 'staff-create'],
            ['category' => 'Staff Management', 'name' => 'staff-edit'],
            ['category' => 'Staff Management', 'name' => 'staff-delete'],

            // ['category' => 'Transaction Management', 'name' => 'transaction-list'],
            // ['category' => 'Transaction Management', 'name' => 'investment-list'],
            // ['category' => 'Transaction Management', 'name' => 'profit-list'],

            ['category' => 'Deposit Management', 'name' => 'automatic-gateway-manage'],
            ['category' => 'Deposit Management', 'name' => 'manual-gateway-manage'],
            ['category' => 'Deposit Management', 'name' => 'deposit-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-action'],

            ['category' => 'Withdraw Management', 'name' => 'withdraw-list'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-method-manage'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-action'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-schedule'],

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
