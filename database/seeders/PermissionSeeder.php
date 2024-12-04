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

            ['category' => 'Deposit Management', 'name' => 'deposit-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-export'],
            ['category' => 'Deposit Management', 'name' => 'deposit-action'],

            ['category' => 'Withdraw Management', 'name' => 'withdraw-list'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-action'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-export'],
        
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

            ['category' => 'Company Settings', 'name' => 'company-setting'],
            ['category' => 'Company Settings', 'name' => 'company-edit'],
            ['category' => 'Company Settings', 'name' => 'misc-setting'],
            ['category' => 'Company Settings', 'name' => 'misc-edit'],
            ['category' => 'Company Settings', 'name' => 'company-permissions-setting'],
            ['category' => 'Company Settings', 'name' => 'company-permissions-edit'],

            ['category' => 'Company Departments Setting', 'name' => 'departments-list'],
            ['category' => 'Company Departments Setting', 'name' => 'department-create'],
            ['category' => 'Company Departments Setting', 'name' => 'department-edit'],
            ['category' => 'Company Departments Setting', 'name' => 'department-delete'],

            ['category' => 'Company Designations Setting', 'name' => 'designations-list'],
            ['category' => 'Company Designations Setting', 'name' => 'designation-create'],
            ['category' => 'Company Designations Setting', 'name' => 'designation-edit'],
            ['category' => 'Company Designations Setting', 'name' => 'designation-delete'],

            ['category' => 'Country Setting', 'name' => 'all-countries-list'],
            ['category' => 'Country Setting', 'name' => 'all-countries-status'],
            ['category' => 'Country Setting', 'name' => 'blacklist-countries-list'],
            ['category' => 'Country Setting', 'name' => 'add-blacklist-countries'],
            ['category' => 'Country Setting', 'name' => 'blacklist-countries-action'],
            
            ['category' => 'Document Links Setting', 'name' => 'document-link-list'],
            ['category' => 'Document Links Setting', 'name' => 'document-link-create'],
            ['category' => 'Document Links Setting', 'name' => 'document-link-edit'],
            ['category' => 'Document Links Setting', 'name' => 'document-link-delete'],

            ['category' => 'Platform Links Setting', 'name' => 'platform-link-list'],
            ['category' => 'Platform Links Setting', 'name' => 'platform-link-create'],
            ['category' => 'Platform Links Setting', 'name' => 'platform-link-edit'],
            ['category' => 'Platform Links Setting', 'name' => 'platform-link-delete'],

            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-create'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-list'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-edit'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-delete'],

            ['category' => 'System Tags Setting', 'name' => 'system-tag-create'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-list'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-edit'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-delete'],

            ['category' => 'IB Groups Setting', 'name' => 'ib-group-create'],
            ['category' => 'IB Groups Setting', 'name' => 'ib-group-list'],
            ['category' => 'IB Groups Setting', 'name' => 'ib-group-edit'],
            ['category' => 'IB Groups Setting', 'name' => 'ib-group-delete'], 

            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-create'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-list'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-edit'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-delete'],

            ['category' => 'KYC and Compliance Setting', 'name' => 'kyc-levels-list'],
            ['category' => 'KYC and Compliance Setting', 'name' => 'kyc-levels-edit'],

            ['category' => 'User Rankings Setting', 'name' => 'ranking-list'],
            ['category' => 'User Rankings Setting', 'name' => 'ranking-create'],
            ['category' => 'User Rankings Setting', 'name' => 'ranking-edit'],

            ['category' => 'Deposit Methods Setting', 'name' => 'automatic-gateway-manage'],
            ['category' => 'Deposit Methods Setting', 'name' => 'manual-gateway-manage'],
            ['category' => 'Deposit Methods Setting', 'name' => 'deposit-method-create'],
            ['category' => 'Deposit Methods Setting', 'name' => 'deposit-method-action'],

            ['category' => 'Withdraw Methods Setting', 'name' => 'automatic-withdraw-method'],
            ['category' => 'Withdraw Methods Setting', 'name' => 'manual-withdraw-method'],
            ['category' => 'Withdraw Methods Setting', 'name' => 'withdraw-schedule'],
            ['category' => 'Withdraw Methods Setting', 'name' => 'withdraw-method-create'],
            ['category' => 'Withdraw Methods Setting', 'name' => 'withdraw-method-action'],

            ['category' => 'Currency Setting', 'name' => 'currency-setting'],

            ['category' => 'Bonus Setting', 'name' => 'bonus-list'],
            ['category' => 'Bonus Setting', 'name' => 'bonus-create'],
            ['category' => 'Bonus Setting', 'name' => 'bonus-edit'],
            ['category' => 'Bonus Setting', 'name' => 'bonus-delete'],

            ['category' => 'Transfers Setting', 'name' => 'internal-transfer-display'],
            ['category' => 'Transfers Setting', 'name' => 'external-transfer-display'],
            ['category' => 'Transfers Setting', 'name' => 'transfers-action'],
    
            ['category' => 'Website Setting', 'name' => 'theme-settings'],
            ['category' => 'Website Setting', 'name' => 'branding-settings'],
            ['category' => 'Website Setting', 'name' => 'site-settings'],
            ['category' => 'Website Setting', 'name' => 'banner-settings'],
            ['category' => 'Website Setting', 'name' => 'gdpr-compliance-settings'],
            ['category' => 'Website Setting', 'name' => 'maintainance-settings'],

            ['category' => 'System Setting', 'name' => 'clear-cache-settings'],
            ['category' => 'System Setting', 'name' => 'application-details-settings'],
            ['category' => 'System Setting', 'name' => 'dev-mode-settings'],
            ['category' => 'System Setting', 'name' => 'changelog-settings'],
            ['category' => 'System Setting', 'name' => 'report-issue-settings'],

            ['category' => 'Support Center Ticket Status Setting', 'name' => 'ticket-status-list'],
            ['category' => 'Support Center Ticket Status Setting', 'name' => 'ticket-status-create'],
            ['category' => 'Support Center Ticket Status Setting', 'name' => 'ticket-status-edit'],
            ['category' => 'Support Center Ticket Status Setting', 'name' => 'ticket-status-delete'],

            ['category' => 'Support Center Ticket Priority Setting', 'name' => 'ticket-priority-list'],
            ['category' => 'Support Center Ticket Priority Setting', 'name' => 'ticket-priority-create'],
            ['category' => 'Support Center Ticket Priority Setting', 'name' => 'ticket-priority-edit'],
            ['category' => 'Support Center Ticket Priority Setting', 'name' => 'ticket-priority-delete'],

            ['category' => 'Security Setting', 'name' => 'all-sections-settings'],
            ['category' => 'Security Setting', 'name' => 'blocklist-ip-settings'],
            ['category' => 'Security Setting', 'name' => 'single-session-settings'],
            ['category' => 'Security Setting', 'name' => 'blocklist-email-settings'],
            ['category' => 'Security Setting', 'name' => 'login-expiry-settings'],

            ['category' => 'Customization Setting', 'name' => 'custom-colors-settings'],
            ['category' => 'Customization Setting', 'name' => 'custom-fonts-settings'],
            ['category' => 'Customization Setting', 'name' => 'routes-settings'],
            ['category' => 'Customization Setting', 'name' => 'dynamic-content-settings'],

            ['category' => 'Website Setting', 'name' => 'theme-settings'],
            ['category' => 'Website Setting', 'name' => 'branding-settings'],
            ['category' => 'Website Setting', 'name' => 'site-settings'],
            ['category' => 'Website Setting', 'name' => 'banner-settings'],
            ['category' => 'Website Setting', 'name' => 'gdpr-compliance-settings'],
            ['category' => 'Website Setting', 'name' => 'maintainance-settings'],

            ['category' => 'Platform API Setting', 'name' => 'meta-trader-display'],
            ['category' => 'Platform API Setting', 'name' => 'meta-trader-edit'],
            ['category' => 'Platform API Setting', 'name' => 'c-trader-display'],
            ['category' => 'Platform API Setting', 'name' => 'c-trader-edit'],
            ['category' => 'Platform API Setting', 'name' => 'x9-trader-display'],
            ['category' => 'Platform API Setting', 'name' => 'x9-trader-edit'],

            ['category' => 'DB Synchronization Setting', 'name' => 'db-synchronization-setting'],

            ['category' => 'Copy Trading Setting', 'name' => 'copy-trading-setting'],
            ['category' => 'Copy Trading Setting', 'name' => 'brokeree-edit'],

            ['category' => 'Platform Groups Setting', 'name' => 'mt5-group-list'],
            ['category' => 'Platform Groups Setting', 'name' => 'manual-group-list'],
            ['category' => 'Platform Groups Setting', 'name' => 'manual-group-create'],
            ['category' => 'Platform Groups Setting', 'name' => 'manual-group-edit'],
            ['category' => 'Platform Groups Setting', 'name' => 'manual-group-delete'],

            ['category' => 'Risk Book Setting', 'name' => 'risk-book-list'],
            ['category' => 'Risk Book Setting', 'name' => 'risk-book-action'],

            ['category' => 'Web Terminal Setting', 'name' => 'mt5-webterminal-display'],
            ['category' => 'Web Terminal Setting', 'name' => 'mt5-webterminal-edit'],
            ['category' => 'Web Terminal Setting', 'name' => 'x9-webterminal-display'],
            ['category' => 'Web Terminal Setting', 'name' => 'x9-webterminal-edit'],
                    
            ['category' => 'Data Management Setting', 'name' => 'import-settings'],
            ['category' => 'Data Management Setting', 'name' => 'export-settings'],
            ['category' => 'Data Management Setting', 'name' => 'data-encryption-settings'],

            ['category' => 'Payment Gateways Setting', 'name' => 'payment-gateways-list'],
            ['category' => 'Payment Gateways Setting', 'name' => 'payment-gateways-action'],

            ['category' => 'Plugins Setting', 'name' => 'plugins-list'],
            ['category' => 'Plugins Setting', 'name' => 'plugins-action'],

            ['category' => 'SMS Setting', 'name' => 'sms-list'],
            ['category' => 'SMS Setting', 'name' => 'sms-action'],

            ['category' => 'Notification Setting', 'name' => 'notification-list'],
            ['category' => 'Notification Setting', 'name' => 'notification-action'],

            ['category' => 'Integrations Setting', 'name' => 'api-access-setting'],
            ['category' => 'Integrations Setting', 'name' => 'web-hooks-setting'],


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
