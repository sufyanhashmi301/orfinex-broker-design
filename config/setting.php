<?php

return [
    'theme' => [
        'title' => 'Theme Settings',

        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_title', // unique name for field
                'label' => 'Site Title', // you know what label it is
                'description' => '',
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => 'CRM', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo', // unique name for field
                'label' => 'Site Logo (Dark)', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo_light', // unique name for field
                'label' => 'Site Logo (Light)', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_favicon', // unique name for field
                'label' => 'Site Favicon', // you know what label it is
                'description' => '',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'image/logo.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'login_bg', // unique name for field
                'label' => 'Admin Login Cover', // you know what label it is
                'description' => '',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'link_thumbnail', // unique name for field
                'label' => 'Link Thumbnail Image ', // you know what label it is
                'description' => '',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'default_transaction_method', // unique name for field
                'label' => 'Default Transaction Image', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
        ],
    ],
    'light_colors' => [
        'title' => 'Light Theme Colors',
        'elements' => [
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'body_bg_color', // unique name for field
                'label' => 'Body Color', // you know what label it is
                'description' => 'Select the body background color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#f1f5f9', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_color', // unique name for field
                'label' => 'Base Color', // you know what label it is
                'description' => 'Select the base color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'active_menu_bg', // unique name for field
                'label' => 'Active menu', // you know what label it is
                'description' => 'Select the active menu background color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'active_menu_color', // unique name for field
                'label' => 'Active menu text', // you know what label it is
                'description' => 'Select the active menu text color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_btn_bg', // unique name for field
                'label' => 'Base button', // you know what label it is
                'description' => 'Select the base button background color',
                'rules' => 'required', // validation rule of laravel
                'value' => '#f3f4f6', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_btn_color', // unique name for field
                'label' => 'Base button text', // you know what label it is
                'description' => 'Select the base button text color',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_btn_bg', // unique name for field
                'label' => 'Primary button', // you know what label it is
                'description' => 'Select the primary button background color',
                'rules' => 'required', // validation rule of laravel
                'value' => '#FED000', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_btn_color', // unique name for field
                'label' => 'Primary button text', // you know what label it is
                'description' => 'Select the primary button text color',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
        ],
    ],
    'dark_colors' => [
        'title' => 'Dark Theme Colors',
        'elements' => [
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'body_bg_color_dark', // unique name for field
                'label' => 'Body Color', // you know what label it is
                'description' => 'Select the body background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#11171f', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_color_dark', // unique name for field
                'label' => 'Base Color', // you know what label it is
                'description' => 'Select the base color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#181e26', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_color_dark', // unique name for field
                'label' => 'Primary Color', // you know what label it is
                'description' => 'Select the primary color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'active_menu_bg_dark', // unique name for field
                'label' => 'Active menu', // you know what label it is
                'description' => 'Select the active menu background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'active_menu_color_dark', // unique name for field
                'label' => 'Active menu text', // you know what label it is
                'description' => 'Select the active menu text color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_btn_bg_dark', // unique name for field
                'label' => 'Primary button', // you know what label it is
                'description' => 'Select the primary button background color',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
        ],
    ],
    'misc_colors' => [
        'title' => 'Misc Colors',
        'elements' => [
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_color', // unique name for field
                'label' => 'Primary Color', // you know what label it is
                'description' => 'Select the primary color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'success_color', // unique name for field
                'label' => 'Success Color', // you know what label it is
                'description' => 'Select the success color for theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0FB60B', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'warning_color', // unique name for field
                'label' => 'Warning Color', // you know what label it is
                'description' => 'Select the warning color for theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#FFBB0D', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'danger_color', // unique name for field
                'label' => 'Danger Color', // you know what label it is
                'description' => 'Select the danger color for theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#DC0000', // default value if you want
            ],
        ],
    ],
    'common_settings' => [
        'title' => 'Company Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_title', // unique name for field
                'label' => 'Site Title', // you know what label it is
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => 'Brokeret', // default value if you want
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
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'company_website', // unique name for field
                'label' => 'Company Website', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'https://company.com', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'company_phone', // unique name for field
                'label' => 'Company Phone', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '123456789', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'registered_address', // unique name for field
                'label' => 'Registered Address', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'United State', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'registered_number', // unique name for field
                'label' => 'Registered Number', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '123456789', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copyright_text', // unique name for field
                'label' => 'Copyright Text', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'All rights reserved @tdevs 2022', // default value if you want
            ],
        ],

    ],

    'currency_setting' => [
        'title' => 'Currency Settings',

        'elements' => [
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
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'currency_symbol', // unique name for field
                'label' => 'Currency Symbol', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '$', // default value if you want
            ],
        ],
    ],

    'global' => [
        'title' => 'Site Settings',

        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_admin_prefix', // unique name for field
                'label' => 'Site Admin Prefix', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin', // default value if you want
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
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_ranking', // unique name for field
                'label' => 'User Ranking (show/hide)', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_forex_group_range', // unique name for field
                'label' => 'Forex Group Range', // you know what label it is
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
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'customer_support_link', // unique name for field
                'label' => 'Customer Support', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_support_show', // unique name for field
                'label' => 'Customer Support', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

        ],

    ],

    'copy_trading' => [
        'title' => 'Copy Trading',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_follower_access', // unique name for field
                'label' => 'Copy Trading Follower Access(iframe)', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_provider_access', // unique name for field
                'label' => 'Copy Trading Provider Access(iframe)', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_ratings', // unique name for field
                'label' => 'Copy Trading Rating(iframe)', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_ratings_js', // unique name for field
                'label' => 'Copy Trading Rating(Js)', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_copy_trading', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
        ]
    ],
    'fee' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'min_send', // unique name for field
                'label' => 'External Minimum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'max_send', // unique name for field
                'label' => 'External Maximum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'send_charge_type', // unique name for field
                'label' => 'External Transfer Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'send_charge', // unique name for field
                'label' => 'External Transfer Charge', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_min_send', // unique name for field
                'label' => 'Internal Minimum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_max_send', // unique name for field
                'label' => 'Internal Maximum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'internal_send_charge_type', // unique name for field
                'label' => 'Internal Transfer Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_send_charge', // unique name for field
                'label' => 'Internal Transfer Charge', // you know what label it is
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
    'internal' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_internal_transfer', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_min_send', // unique name for field
                'label' => 'Internal Minimum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_max_send', // unique name for field
                'label' => 'Internal Maximum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'internal_send_charge_type', // unique name for field
                'label' => 'Internal Transfer Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_send_charge', // unique name for field
                'label' => 'Internal Transfer Charge', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'internal_send_daily_limit', // unique name for field
                'label' => 'Transfers Per Day', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
        ],
    ],
    'deposit_settings' => [
        'title' => 'Deposit Settings',
        'elements' => [

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'pending_deposit_limit', // unique name for field
                'label' => 'Pending Deposit Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 3, // default value if you want
            ]
        ],
    ],
    'withdraw_settings' => [
        'title' => 'Withdraw Settings',
        'elements' => [

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'pending_withdraw_limit', // unique name for field
                'label' => 'Pending Withdraw Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 3, // default value if you want
            ]
        ],
    ],
    'forex_account_settings' => [
        'title' => 'Forex Accounts Settings',
        'elements' => [

            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'forex_account_create_limit', // unique name for field
                'label' => 'Pending Deposit Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 3, // default value if you want
            ],
        ],
    ],
    'external' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer_auto_approve', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer_purpose', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_min_send', // unique name for field
                'label' => 'External Minimum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_max_send', // unique name for field
                'label' => 'External Maximum Transfer', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'external_send_charge_type', // unique name for field
                'label' => 'External Transfer Type', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_transfer_charge', // unique name for field
                'label' => 'External Transfer Charge', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'external_send_daily_limit', // unique name for field
                'label' => 'Transfers Per Day', // you know what label it is
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
        ],
    ],
    'transfer_misc' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
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
    'features' => [
        'title' => 'Features Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'withdraw_deduction', // unique name for field
                'label' => 'Withdraw Deduction', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'copy_trading', // unique name for field
                'label' => 'Copy Trading', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'radio', // Change type to 'radio'
                'data' => 'string',
                'name' => 'active_trader_type',
                'label' => 'Trader Type',
                'rules' => 'required', // Include options
                'value' => \App\Enums\TraderType::MT5, // Default value
                'options' => [
                    'mt5' => \App\Enums\TraderType::MT5,
                    'x9' => \App\Enums\TraderType::X9,
                    'c_trader' => \App\Enums\TraderType::CTRADER,
                    'all' => \App\Enums\TraderType::All,
                ],
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
    'platform_api' => [
        'title' => 'Platform API Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt5_api_url_real', // unique name for field
                'label' => 'MT5 API url(real)', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'http://11.222.333.444:1234', // default value if you want
            ],
            [
                'type' => 'password', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt5_api_key_real', // unique name for field
                'label' => 'Forex API key(real)', // you know what label it is
                'rules' => 'required|min:5|max:1000', // validation rule of laravel
                'value' => 'PVTfAIPjQZ4Ggan', // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'demo_server_enable', // unique name for field
                'label' => 'Demo Server Enable', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],

            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt5_api_url_demo', // unique name for field
                'label' => 'MT5 API url(demo)', // you know what label it is
                'rules' => 'sometimes|min:5|max:50', // validation rule of laravel
                'value' => 'http://11.222.333.444:1234', // default value if you want
            ],
            [
                'type' => 'password', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt5_api_key_demo', // unique name for field
                'label' => 'MT5 API key(demo)', // you know what label it is
                'rules' => 'sometimes|min:5|max:1000', // validation rule of laravel
                'value' => 'PVTfAIPjQZ4Ggan', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'live_server', // unique name for field
                'label' => 'Live Server Name', // you know what label it is
                'rules' => 'required|min:5|max:1000', // validation rule of laravel
                'value' => 'MT5 Server', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'demo_server', // unique name for field
                'label' => 'Demo Server Name', // you know what label it is
                'rules' => 'sometimes|min:5|max:1000', // validation rule of laravel
                'value' => 'MT5 Server', // default value if you want
            ],
        ],
    ],
     'x9_api' => [
        'title' => 'X9trader API Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_name', // unique name for field
                'label' => 'Name', // you know what label it is
                'rules' => 'required|min:5|max:50', // validation rule of laravel
                'value' => 'X9trader', // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'x9_demo_server_enable', // unique name for field
                'label' => 'Demo Server Enable', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_network_address', // unique name for field
                'label' => 'Network Address', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'X9 Server', // default value if you want
            ],
            [
                'type' => 'password', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_API_access_key', // unique name for field
                'label' => 'Login', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'x-access-token', // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'x9_status', // unique name for field
                'label' => 'Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
        ],
    ],
    'mt5_db_credentials' => [
        'title' => 'MySQL Database Credentials',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'database_host', // unique name for field
                'label' => 'Database Host', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'integer', // data type, string, int, boolean
                'name' => 'database_port', // unique name for field
                'label' => 'Database Port', // label for field
                'rules' => 'required|integer|min:1024|max:65535', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'database_name', // unique name for field
                'label' => 'Database Name', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'database_username', // unique name for field
                'label' => 'Database Username', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'password', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'database_password', // unique name for field
                'label' => 'Database Password', // label for field
                'rules' => 'sometimes|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
        ],
    ],
    'x9_db_credentials' => [
        'title' => 'X9 Database Credentials',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_database_host', // unique name for field
                'label' => 'Database Host', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'integer', // data type, string, int, boolean
                'name' => 'x9_database_port', // unique name for field
                'label' => 'Database Port', // label for field
                'rules' => 'required|integer|min:1024|max:65535', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_database_name', // unique name for field
                'label' => 'Database Name', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_database_username', // unique name for field
                'label' => 'Database Username', // label for field
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'password', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_database_password', // unique name for field
                'label' => 'Database Password', // label for field
                'rules' => 'sometimes|min:2|max:50', // validation rule of laravel
                'value' => '', // default value if you want
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
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_text', // unique name for field
                'label' => 'GDPR Text', // you know what label it is
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Please allow us to collect data about how you use our website. We will use it to improve our website, make your browsing experience and our business decisions better. Learn more', // default value if you want
            ],
        ],
    ],
    'company_misc' => [
        'title' => 'Misc Settings',
        'elements' => [
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'disclaimer', // unique name for field
                'label' => 'Disclaimer', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_disclaimer', // unique name for field
                'label' => 'Disclaimer Email', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'footer_content', // unique name for field
                'label' => 'Footer', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_footer', // unique name for field
                'label' => 'Footer Email', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'risk_warning', // unique name for field
                'label' => 'Risk Warning', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_risk_warning', // unique name for field
                'label' => 'Risk Warning Email', // you know what label it is
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
        ],
    ],
    'webterminal' => [
        'title' => 'MT5 Web terminal Settings',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'webterminal_src_light', // unique name for field
                'label' => 'Terminal URL (Light)', // you know what label it is
                'description' => 'Provide the URL for the web terminal interface in light mode.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'webterminal_src_dark', // unique name for field
                'label' => 'Terminal URL (Dark)', // you know what label it is
                'description' => 'Provide the URL for the web terminal interface in dark mode.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'webterminal_width', // unique name for field
                'label' => 'Terminal Width', // you know what label it is
                'description' => 'Specify the width of the web terminal as a percentage of its container.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'webterminal_height', // unique name for field
                'label' => 'Terminal Height', // you know what label it is
                'description' => 'Specify the height of the web terminal as a percentage of its container.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'select', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'webterminal_status', // unique name for field
                'label' => 'Status', // label for the field
                'description' => 'Enable or disable the current web terminal configuration.',
                'rules' => 'required', // validation rule of Laravel
                'options' => [ // options for the select box
                    '1' => 'Enabled',
                    '0' => 'Disabled',
                ],
                'value' => 0, // default value
            ],
        ],
    ],
    'x9_webterminal' => [
        'title' => 'X9 Web terminal Settings',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_webterminal_src_light', // unique name for field
                'label' => 'Terminal URL (Light)', // you know what label it is
                'description' => 'Provide the URL for the web terminal interface in light mode.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_webterminal_src_dark', // unique name for field
                'label' => 'Terminal URL (Dark)', // you know what label it is
                'description' => 'Provide the URL for the web terminal interface in dark mode.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_webterminal_width', // unique name for field
                'label' => 'Terminal Width', // you know what label it is
                'description' => 'Specify the width of the web terminal as a percentage of its container.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_webterminal_height', // unique name for field
                'label' => 'Terminal Height', // you know what label it is
                'description' => 'Specify the height of the web terminal as a percentage of its container.',
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'select', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'x9_webterminal_status', // unique name for field
                'label' => 'Status', // label for the field
                'description' => 'Enable or disable the current web terminal configuration.',
                'rules' => 'required', // validation rule of Laravel
                'options' => [ // options for the select box
                    '1' => 'Enabled',
                    '0' => 'Disabled',
                ],
                'value' => 0, // default value
            ],
        ],
    ],

];
