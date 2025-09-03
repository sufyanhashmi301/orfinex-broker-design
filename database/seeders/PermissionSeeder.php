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
//       DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//
//       // Truncate the permissions table
//       DB::table('permissions')->truncate();
//
//       // Re-enable foreign key checks
//       DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [

            ['category' => 'Customer Management', 'name' => 'customer-list'],
            ['category' => 'Customer Management', 'name' => 'show-all-users-by-default-to-staff'],
            ['category' => 'Customer Management', 'name' => 'customer-create'],
            ['category' => 'Customer Management', 'name' => 'customer-edit'],
            ['category' => 'Customer Management', 'name' => 'customer-export'],
            ['category' => 'Customer Management', 'name' => 'customer-mail-send'],
            ['category' => 'Customer Management', 'name' => 'customer-change-password'],

            ['category' => 'Customer Profile Management', 'name' => 'customer-login'],
            ['category' => 'Customer Profile Management', 'name' => 'customer-bonus'],
            ['category' => 'Customer Profile Management', 'name' => 'customer-funds'],
            ['category' => 'Customer Profile Management', 'name' => 'customer-delete'],
            ['category' => 'Customer Profile Management', 'name' => 'customer-profile-toggles'],

            ['category' => 'Customer festures Management', 'name' => 'customer-add-tag'],
            ['category' => 'Customer festures Management', 'name' => 'customer-overview-update'],
            ['category' => 'Customer festures Management', 'name' => 'customer-accounts-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-account-mapping'],
            ['category' => 'Customer festures Management', 'name' => 'customer-account-create'],
            ['category' => 'Customer festures Management', 'name' => 'customer-kyc-manage'],
            ['category' => 'Customer festures Management', 'name' => 'customer-ib-partner-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-approve-ib-member'],
            ['category' => 'Customer festures Management', 'name' => 'customer-transactions-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-transactions-stats'],
            ['category' => 'Customer festures Management', 'name' => 'customer-transactions-export'],
            ['category' => 'Customer festures Management', 'name' => 'customer-ib-bonus-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-ib-bonus-export'],
            ['category' => 'Customer festures Management', 'name' => 'customer-master-ib-network-distribution'],
            ['category' => 'Customer festures Management', 'name' => 'customer-direct-referrals-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-direct-referrals-create'],
            ['category' => 'Customer festures Management', 'name' => 'customer-direct-referrals-export'],
            ['category' => 'Customer festures Management', 'name' => 'customer-child-ib-distribution'],
            ['category' => 'Customer festures Management', 'name' => 'customer-network-tree'],
            ['category' => 'Customer festures Management', 'name' => 'customer-network-stats'],
            ['category' => 'Customer festures Management', 'name' => 'customer-tickets-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-notes-list'],
            ['category' => 'Customer festures Management', 'name' => 'customer-notes-create'],
            ['category' => 'Customer festures Management', 'name' => 'customer-notes-edit'],
            ['category' => 'Customer festures Management', 'name' => 'customer-notes-delete'],

            ['category' => 'leads Management', 'name' => 'lead-list'],
            ['category' => 'leads Management', 'name' => 'lead-action'],
            ['category' => 'leads Management', 'name' => 'lead-create'],
            ['category' => 'leads Management', 'name' => 'deal-list'],
            ['category' => 'leads Management', 'name' => 'deal-action'],
            ['category' => 'leads Management', 'name' => 'deal-create'],

            ['category' => 'Deposit Management', 'name' => 'deposit-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-add'],
            ['category' => 'Deposit Management', 'name' => 'deposit-approve'],
            ['category' => 'Deposit Management', 'name' => 'deposit-reject'],
            ['category' => 'Deposit Management', 'name' => 'deposit-auto-approve'],
            ['category' => 'Deposit Management', 'name' => 'deposit-export'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-create'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-edit'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-delete'],

            ['category' => 'Withdraw Management', 'name' => 'withdraw-list'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-add'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-approve'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-reject'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-auto-approve'],
            ['category' => 'Withdraw Management', 'name' => 'withdraw-export'],
            
            ['category' => 'Withdraw Accounts Management', 'name' => 'withdraw-account-pending'],
            ['category' => 'Withdraw Accounts Management', 'name' => 'withdraw-account-approve'],
            ['category' => 'Withdraw Accounts Management', 'name' => 'withdraw-account-rejected'],
            ['category' => 'Withdraw Accounts Management', 'name' => 'withdraw-account-action'],
            ['category' => 'Withdraw Accounts Management', 'name' => 'withdraw-account-export'],

            ['category' => 'Kyc Management', 'name' => 'kyc-list'],
            ['category' => 'Kyc Management', 'name' => 'kyc-action'],
            ['category' => 'Kyc Management', 'name' => 'kyc-export'],

            ['category' => 'Staff Management', 'name' => 'staff-list'],
            ['category' => 'Staff Management', 'name' => 'staff-create'],
            ['category' => 'Staff Management', 'name' => 'staff-edit'],
            ['category' => 'Staff Management', 'name' => 'staff-edit-role'],
            ['category' => 'Staff Management', 'name' => 'staff-delete'],
            ['category' => 'Staff Management', 'name' => 'staff-login'],
            ['category' => 'Staff Management', 'name' => 'staff-attach-users-list'],
            ['category' => 'Staff Management', 'name' => 'staff-attach-users-create'],
            ['category' => 'Staff Management', 'name' => 'staff-attach-users-delete'],
             ['category' => 'Staff Management', 'name' => 'staff-team-list'],
            ['category' => 'Staff Management', 'name' => 'staff-team-create'],
            ['category' => 'Staff Management', 'name' => 'staff-team-delete'],

            ['category' => 'Account Type Management', 'name' => 'account-type-list'],
            ['category' => 'Account Type Management', 'name' => 'account-type-create'],
            ['category' => 'Account Type Management', 'name' => 'account-type-edit'],
            ['category' => 'Account Type Management', 'name' => 'account-type-delete'],
            ['category' => 'Account Type Management', 'name' => 'account-type-export'],

            ['category' => 'Accounts Management', 'name' => 'accounts-list'],
            ['category' => 'Accounts Management', 'name' => 'leverage-list'],
            ['category' => 'Accounts Management', 'name' => 'accounts-action'],
            ['category' => 'Accounts Management', 'name' => 'account-trades-view'],
            ['category' => 'Accounts Management', 'name' => 'leverage-action'],
            ['category' => 'Accounts Management', 'name' => 'accounts-export'],

            ['category' => 'Risk Hub Management', 'name' => 'active-positions'],
            ['category' => 'Risk Hub Management', 'name' => 'net-positions-accounts'],
            ['category' => 'Risk Hub Management', 'name' => 'net-positions-groups'],
            ['category' => 'Risk Hub Management', 'name' => 'older-positions-days'],

            // ['category' => 'Referral Management', 'name' => 'target-manage'],
            // ['category' => 'Referral Management', 'name' => 'referral-create'],
            // ['category' => 'Referral Management', 'name' => 'referral-list'],
            // ['category' => 'Referral Management', 'name' => 'referral-edit'],
            // ['category' => 'Referral Management', 'name' => 'referral-delete'],

            ['category' => 'IB Management', 'name' => 'ib-list'],
            ['category' => 'IB Management', 'name' => 'ib-action'],
            ['category' => 'IB Management', 'name' => 'ib-export'],
            ['category' => 'IB Management', 'name' => 'ib-form-manage'],
            ['category' => 'IB Management', 'name' => 'advertisement-material-edit'],
            ['category' => 'IB Management', 'name' => 'ib-leaderboard-list'],

            ['category' => 'Transaction Management', 'name' => 'transaction-list'],
            ['category' => 'Transaction Management', 'name' => 'transaction-action'],
            ['category' => 'Transaction Management', 'name' => 'transaction-export'],

            ['category' => 'Subscriber Management', 'name' => 'subscriber-list'],
            ['category' => 'Subscriber Management', 'name' => 'subscriber-mail-send'],

            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-list'],
            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-create'],
            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-chat'],
            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-assign'],
            ['category' => 'Support Ticket Management', 'name' => 'support-ticket-status'],

            ['category' => 'Partnership Levels Management', 'name' => 'levels-list'],

            ['category' => 'Partnership Multi IB Levels', 'name' => 'multi-ib-level-list'],
            ['category' => 'Partnership Multi IB Levels', 'name' => 'multi-ib-level-create'],
            ['category' => 'Partnership Multi IB Levels', 'name' => 'multi-ib-level-edit'],
            ['category' => 'Partnership Multi IB Levels', 'name' => 'multi-ib-level-delete'],

            ['category' => 'Partnership Symbols', 'name' => 'symbols-list'],
            ['category' => 'Partnership Symbols', 'name' => 'symbols-export'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-create'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-edit'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-delete'],
            ['category' => 'Partnership Symbols', 'name' => 'symbol-group-export'],


            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-list'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-create'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-edit'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-delete'],
            ['category' => 'Partnership Rebate Rules', 'name' => 'rebate-rules-export'],

            ['category' => 'Partnership IB Groups Setting', 'name' => 'ib-group-list'],
            ['category' => 'Partnership IB Groups Setting', 'name' => 'ib-group-create'],
            ['category' => 'Partnership IB Groups Setting', 'name' => 'ib-group-edit'],
            ['category' => 'Partnership IB Groups Setting', 'name' => 'ib-group-delete'],
            ['category' => 'Partnership IB Groups Setting', 'name' => 'ib-group-export'],


            ['category' => 'Setting Management', 'name' => 'site-setting'],
            ['category' => 'Setting Management', 'name' => 'plugin-setting'],

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

            ['category' => 'Social Links Setting', 'name' => 'social-link-list'],
            ['category' => 'Social Links Setting', 'name' => 'social-link-edit'],

            ['category' => 'Social logins Setting', 'name' => 'social-logins-list'],
            ['category' => 'Social logins Setting', 'name' => 'social-logins-action'],

            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-create'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-list'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-edit'],
            ['category' => 'Risk Profile Tags Setting', 'name' => 'risk-profile-delete'],

            ['category' => 'System Tags Setting', 'name' => 'system-tag-create'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-list'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-edit'],
            ['category' => 'System Tags Setting', 'name' => 'system-tag-delete'],

            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-create'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-list'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-edit'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-group-delete'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-permissions'],
            ['category' => 'Customer Groups Setting', 'name' => 'customer-registration-settings'],

            ['category' => 'Customer Misc Setting', 'name' => 'customer-misc-settings'],

            ['category' => 'Role Management', 'name' => 'role-list'],
            ['category' => 'Role Management', 'name' => 'role-create'],
            ['category' => 'Role Management', 'name' => 'role-edit'],
            ['category' => 'Role Management', 'name' => 'role-delete'],

            ['category' => 'Lead Source Setting', 'name' => 'lead-source-list'],
            ['category' => 'Lead Source Setting', 'name' => 'lead-source-create'],
            ['category' => 'Lead Source Setting', 'name' => 'lead-source-edit'],
            ['category' => 'Lead Source Setting', 'name' => 'lead-source-delete'],

            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-pipeline-list'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-pipeline-create'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-pipeline-edit'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-pipeline-delete'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-stage-list'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-stage-create'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-stage-edit'],
            ['category' => 'Lead Pipeline and Stage Setting', 'name' => 'lead-stage-delete'],

            ['category' => 'KYC and Compliance Setting', 'name' => 'kyc-levels-list'],
            ['category' => 'KYC and Compliance Setting', 'name' => 'kyc-levels-edit'],
            ['category' => 'KYC and Compliance Setting', 'name' => 'kyc-levels-permissions'],


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
            ['category' => 'Website Setting', 'name' => 'provider-logo-settings'],
            ['category' => 'Website Setting', 'name' => 'auth-covers-settings'],
            ['category' => 'Website Setting', 'name' => 'site-settings'],
            ['category' => 'Website Setting', 'name' => 'banner-settings'],
            ['category' => 'Website Setting', 'name' => 'gdpr-compliance-settings'],
            ['category' => 'Website Setting', 'name' => 'maintainance-settings'],

            ['category' => 'Customization Setting', 'name' => 'custom-colors-settings'],
            ['category' => 'Customization Setting', 'name' => 'custom-fonts-settings'],
            ['category' => 'Customization Setting', 'name' => 'routes-settings'],
            ['category' => 'Customization Setting', 'name' => 'dynamic-content-settings'],

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

            ['category' => 'Communications Setting', 'name' => 'email-setting'],
            ['category' => 'Communications Setting', 'name' => 'collab-tools-setting'],

            ['category' => 'Email Templates Setting', 'name' => 'admin-email-template'],
            ['category' => 'Email Templates Setting', 'name' => 'user-email-template'],
            ['category' => 'Email Templates Setting', 'name' => 'email-template-action'],

            ['category' => 'SMS Templates Setting', 'name' => 'admin-sms-template'],
            ['category' => 'SMS Templates Setting', 'name' => 'user-sms-template'],
            ['category' => 'SMS Templates Setting', 'name' => 'sms-template-action'],

            ['category' => 'Data Management Setting', 'name' => 'import-settings'],
            ['category' => 'Data Management Setting', 'name' => 'export-settings'],
            ['category' => 'Data Management Setting', 'name' => 'data-encryption-settings'],

            ['category' => 'System Setting', 'name' => 'clear-cache-settings'],
            ['category' => 'System Setting', 'name' => 'application-details-settings'],
            ['category' => 'System Setting', 'name' => 'dev-mode-settings'],
            ['category' => 'System Setting', 'name' => 'changelog-settings'],
            ['category' => 'System Setting', 'name' => 'report-issue-settings'],

            ['category' => 'Payment Gateways Setting', 'name' => 'payment-gateways-list'],
            ['category' => 'Payment Gateways Setting', 'name' => 'payment-gateways-action'],

            ['category' => 'Plugins Setting', 'name' => 'plugins-list'],
            ['category' => 'Plugins Setting', 'name' => 'plugins-action'],

            ['category' => 'SMS Setting', 'name' => 'sms-list'],
            ['category' => 'SMS Setting', 'name' => 'sms-action'],

            ['category' => 'Notification Setting', 'name' => 'notification-list'],
            ['category' => 'Notification Setting', 'name' => 'notification-action'],

            ['category' => 'Notification Tune Setting', 'name' => 'deposit-notification'],
            ['category' => 'Notification Tune Setting', 'name' => 'withdraw-notification'],
            ['category' => 'Notification Tune Setting', 'name' => 'transfer-notification'],

            ['category' => 'Integrations Setting', 'name' => 'api-access-setting'],
            ['category' => 'Integrations Setting', 'name' => 'web-hooks-setting'],

            ['category' => 'Security Setting', 'name' => 'all-sections-settings'],
            ['category' => 'Security Setting', 'name' => 'blocklist-ip-settings'],
            ['category' => 'Security Setting', 'name' => 'single-session-settings'],
            ['category' => 'Security Setting', 'name' => 'blocklist-email-settings'],
            ['category' => 'Security Setting', 'name' => 'login-expiry-settings'],

            ['category' => 'Language Setting', 'name' => 'language-list'],
            ['category' => 'Language Setting', 'name' => 'language-create'],
            ['category' => 'Language Setting', 'name' => 'language-action'],

            ['category' => 'Support Center Ticket Type Setting', 'name' => 'ticket-type-list'],
            ['category' => 'Support Center Ticket Type Setting', 'name' => 'ticket-type-create'],
            ['category' => 'Support Center Ticket Type Setting', 'name' => 'ticket-type-edit'],
            ['category' => 'Support Center Ticket Type Setting', 'name' => 'ticket-type-delete'],

            ['category' => 'Support Center Ticket Category Setting', 'name' => 'ticket-category-list'],
            ['category' => 'Support Center Ticket Category Setting', 'name' => 'ticket-category-create'],
            ['category' => 'Support Center Ticket Category Setting', 'name' => 'ticket-category-edit'],
            ['category' => 'Support Center Ticket Category Setting', 'name' => 'ticket-category-delete'],

            // Deposit Voucher Permissions
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-list'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-create'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-edit'],
            ['category' => 'Deposit Management', 'name' => 'deposit-voucher-delete'],
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
