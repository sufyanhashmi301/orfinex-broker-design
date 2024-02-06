<?php

return [
    'global' => [
        'title' => 'Global Settings',

        'elements' => [
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo', // unique name for field
                'label' => 'Site Logo', // you know what label it is
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_favicon', // unique name for field
                'label' => 'Site Favicon', // you know what label it is
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'image/logo.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'login_bg', // unique name for field
                'label' => 'Admin Login Cover', // you know what label it is
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_admin_prefix', // unique name for field
                'label' => 'Site Admin Prefix', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin', // default value if you want
            ],
            [
                'type' => 'switch', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_currency_type', // unique name for field
                'label' => 'Site Currency Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'fiat', // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_currency', // unique name for field
                'label' => 'Site Currency', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'USD', // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_timezone', // unique name for field
                'label' => 'Site Timezone', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'UTC', // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_referral', // unique name for field
                'label' => 'Site Referral Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'level', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'currency_symbol', // unique name for field
                'label' => 'Currency Symbol', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '$', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'integer', // data type, string, int, boolean
                'name' => 'referral_code_limit', // unique name for field
                'label' => 'Referral Code Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '6', // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'home_redirect', // unique name for field
                'label' => 'Home Redirect', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '/', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_title', // unique name for field
                'label' => 'Site Title', // you know what label it is
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => 'Hyiprio', // default value if you want
            ],
            [
                'type' => 'email', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_email', // unique name for field
                'label' => 'Site Email', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin@tdevs.co', // default value if you want
            ],
            [
                'type' => 'email', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'support_email', // unique name for field
                'label' => 'Support Email', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'support@tdevs.co', // default value if you want
            ],
        ],
    ],

    'permission' => [
        'title' => 'Permission Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'email_verification', // unique name for field
                'label' => 'Email Verification', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'kyc_verification', // unique name for field
                'label' => 'KYC Verification', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'fa_verification', // unique name for field
                'label' => '2FA Verification', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'account_creation', // unique name for field
                'label' => 'Account Creation', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_deposit', // unique name for field
                'label' => 'User Deposit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_withdraw', // unique name for field
                'label' => 'User Withdraw', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'transfer_status', // unique name for field
                'label' => 'User Send Money', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'sign_up_referral', // unique name for field
                'label' => 'User Referral', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'referral_signup_bonus', // unique name for field
                'label' => 'Signup Bonus', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'investment_referral_bounty', // unique name for field
                'label' => 'Investment Referral Bounty', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'deposit_referral_bounty', // unique name for field
                'label' => 'Deposit Referral Bounty', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'site_animation', // unique name for field
                'label' => 'Site Animation', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'back_to_top', // unique name for field
                'label' => 'Site Back to Top', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'debug_mode', // unique name for field
                'label' => 'Development Mode', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'new_trading_accounts', // unique name for field
                'label' => 'New Trading Accounts', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => '90_days_in_activity_trade_disable', // unique name for field
                'label' => '90 Days In-Activity Trade Disable', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'delete_archived_accounts', // unique name for field
                'label' => 'Delete Archived Accounts', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'automatic_withdrawals', // unique name for field
                'label' => 'Automatic Withdrawals', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'automatic_deposits', // unique name for field
                'label' => 'Automatic Deposits', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'automatic_kyc', // unique name for field
                'label' => 'Automatic KYC (SumSub)', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'disable_trading', // unique name for field
                'label' => 'Disable Trading (No Balance)', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ],
    ],
    'document_links' => [
        'title' => 'Document Links',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'aml_policy_link', // unique name for field
                'label' => 'AML Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'aml_policy_show', // unique name for field
                'label' => 'AML Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'client_agreement_link', // unique name for field
                'label' => 'Client Agreement Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'client_agreement_show', // unique name for field
                'label' => 'Client Agreement', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'complaints_handling_policy_link', // unique name for field
                'label' => 'Complaints Handling Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'complaints_handling_policy_show', // unique name for field
                'label' => 'Complaints Handling Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'cookies_policy_link', // unique name for field
                'label' => 'Cookies Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'cookies_policy_show', // unique name for field
                'label' => 'Cookies Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'IB_partner_agreement_link', // unique name for field
                'label' => 'IB Partner Agreement Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'IB_partner_agreement_show', // unique name for field
                'label' => 'IB Partner Agreement', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'order_execution_policy_link', // unique name for field
                'label' => 'Order Execution Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'order_execution_policy_show', // unique name for field
                'label' => 'Order Execution Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'privacy_policy_link', // unique name for field
                'label' => 'Privacy Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'privacy_policy_show', // unique name for field
                'label' => 'Privacy Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'risk_disclosure_link', // unique name for field
                'label' => 'Risk Disclosure Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'risk_disclosure_show', // unique name for field
                'label' => 'Risk Disclosure', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'US_clients_policy_link', // unique name for field
                'label' => 'US Clients Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'US_clients_policy_show', // unique name for field
                'label' => 'US Clients Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],


        ],

    ],
    'platform_links' => [
        'title' => 'Platform Links',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'desktop_terminal_windows_link', // unique name for field
                'label' => 'Desktop Terminal - Windows Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'desktop_terminal_windows_show', // unique name for field
                'label' => 'Desktop Terminal - Windows', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'desktop_terminal_mac_link', // unique name for field
                'label' => 'Desktop Terminal - Mac Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'desktop_terminal_mac_show', // unique name for field
                'label' => 'Desktop Terminal - Mac', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mobile_application_android_link', // unique name for field
                'label' => 'Mobile Application - Android Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'mobile_application_android_show', // unique name for field
                'label' => 'Mobile Application - Android', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mobile_application_iOS_link', // unique name for field
                'label' => 'Mobile Application - iOS Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'mobile_application_iOS_show', // unique name for field
                'label' => 'Mobile Application - iOS', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mobile_application_Android_APK_link', // unique name for field
                'label' => 'Mobile Application - Android (APK) Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'mobile_application_Android_APK_show', // unique name for field
                'label' => 'Mobile Application - Android (APK)', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'web_terminal_link', // unique name for field
                'label' => 'Web Terminal (No Download)', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'web_terminal_show', // unique name for field
                'label' => 'Web Terminal (No Download)', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'trust_pilot_review_link', // unique name for field
                'label' => 'Trust Pilot Review', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'trust_pilot_review_show', // unique name for field
                'label' => 'Trust Pilot Review', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
        ],


    ],


    'fee' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'min_send', // unique name for field
                'label' => 'Minimum Send Money', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'max_send', // unique name for field
                'label' => 'Maximum Send Money', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'send_charge_type', // unique name for field
                'label' => 'Send Money Charge Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'send_charge', // unique name for field
                'label' => 'Send Money Charge', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'wallet_exchange_charge_type', // unique name for field
                'label' => 'Wallet Exchange Charge Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'wallet_exchange_charge', // unique name for field
                'label' => 'Wallet Exchange Charge', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'referral_bonus', // unique name for field
                'label' => 'Referral Bonus', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'signup_bonus', // unique name for field
                'label' => 'Sign Up Bonus', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'wallet_exchange_day_limit', // unique name for field
                'label' => 'Wallet Exchange Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 10, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'send_money_day_limit', // unique name for field
                'label' => 'Send Money Day Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 14, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'withdraw_day_limit', // unique name for field
                'label' => 'Withdraw Day Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 11, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'investment_cancellation_daily_limit', // unique name for field
                'label' => 'Investment Cancellation Daily Limit', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
        ],
    ],

    'mail' => [
        'title' => 'Mail Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_from_name', // unique name for field
                'label' => 'Email From Name', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'Tdevs', // default value if you want
            ],
            [
                'type' => 'email', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_from_address', // unique name for field
                'label' => 'Email From Address', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'wd2rasel@gmail.com', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mailing_driver', // unique name for field
                'label' => 'Mailing Driver', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'SMTP', // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_username', // unique name for field
                'label' => 'Mail Username', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '465', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_password', // unique name for field
                'label' => 'Mail Password', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '0000', // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_host', // unique name for field
                'label' => 'Mail Host', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'mail.tdevs.co', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'integer', // data type, string, int, boolean
                'name' => 'mail_port', // unique name for field
                'label' => 'Mail Port', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '465', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mail_secure', // unique name for field
                'label' => 'Mail Secure', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'ssl', // default value if you want
            ],
        ],
    ],

    'site_maintenance' => [
        'title' => 'Site Maintenance',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'maintenance_mode', // unique name for field
                'label' => 'Maintenance Mode', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secret_key', // unique name for field
                'label' => 'Secret Key', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'secret', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_title', // unique name for field
                'label' => 'Title', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'Site is not under maintenance', // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_text', // unique name for field
                'label' => 'Maintenance Text', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Sorry for interrupt! Site will live soon.', // default value if you want
            ],
        ],
    ],

    'gdpr' => [
        'title' => 'GDPR Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'gdpr_status', // unique name for field
                'label' => 'GDPR Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_text', // unique name for field
                'label' => 'GDPR Text', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Please allow us to collect data about how you use our website. We will use it to improve our website, make your browsing experience and our business decisions better. Learn more', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_label', // unique name for field
                'label' => 'Button Label', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Learn More', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_url', // unique name for field
                'label' => 'Button URL', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => '/privacy-policy', // default value if you want
            ],
        ],
    ],

];
