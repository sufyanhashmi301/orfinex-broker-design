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
                'example_logo' => 'example_logo_dark.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo_light', // unique name for field
                'label' => 'Site Logo (Light)', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'example_logo' => 'example_logo_light.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_favicon', // unique name for field
                'label' => 'Site Favicon', // you know what label it is
                'description' => 'Recommended Size 32 x 32',
                'example_logo' => 'example_favicon.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'image/logo.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'login_bg', // unique name for field
                'label' => 'Admin Login Cover', // you know what label it is
                'description' => 'Recommended Size 935 x 920',
                'example_logo' => 'example_',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'link_thumbnail', // unique name for field
                'label' => 'Link Thumbnail Image ', // you know what label it is
                'description' => 'Recommended Size 1600 x 627',
                'example_logo' => 'example_thumbnail_crm.png',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
        ],
    ],
    'defaults' => [
        'elements' => [
            'kyc_badge_visibility' => 'show',
            [
                'name' => 'payout_eligibility_period',
                'value' => '2',
            ],
            [
                'name' => 'site_user_header_code',
                'value' => ''
            ],
            [
                'name' => 'site_user_footer_code',
                'value' => ''
            ],
            [
                'name' => 'site_admin_header_code',
                'value' => ''
            ],
            [
                'name' => 'site_admin_footer_code',
                'value' => ''
            ],
        ]
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
                'name' => 'base_color', // unique name for field
                'label' => 'Base Color', // you know what label it is
                'description' => 'Select the base color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'sidebar_bg', // unique name for field
                'label' => 'Sidebar Color', // you know what label it is
                'description' => 'Select the sidebar background color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'sidebar_color', // unique name for field
                'label' => 'Sidebar text', // you know what label it is
                'description' => 'Select the sidebar text color for the light theme',
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
                'value' => '#0f172a', // default value if you want
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
                'name' => 'base_color_dark', // unique name for field
                'label' => 'Base Color', // you know what label it is
                'description' => 'Select the base color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#181e26', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'sidebar_bg_dark', // unique name for field
                'label' => 'Sidebar Color', // you know what label it is
                'description' => 'Select the sidebar background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'sidebar_color_dark', // unique name for field
                'label' => 'Sidebar text', // you know what label it is
                'description' => 'Select the sidebar text color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
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
                'name' => 'secondary_btn_bg_dark', // unique name for field
                'label' => 'Base button', // you know what label it is
                'description' => 'Select the base button background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#f3f4f6', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_btn_color_dark', // unique name for field
                'label' => 'Base button text', // you know what label it is
                'description' => 'Select the base button text color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_btn_bg_dark', // unique name for field
                'label' => 'Primary button', // you know what label it is
                'description' => 'Select the primary button background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#FED000', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'primary_btn_color_dark', // unique name for field
                'label' => 'Primary button text', // you know what label it is
                'description' => 'Select the primary button text color for the dark theme',
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
                'description' => 'Select the primary color for theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secondary_color', // unique name for field
                'label' => 'Secondary Color', // you know what label it is
                'description' => 'Select the secondary color for theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#f1f5f9', // default value if you want
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
    'fonts_settings' => [
        'title' => 'Fonts Settings',
        'elements' => [
            [
                'type' => 'radio', // Change type to 'radio'
                'data' => 'string',
                'name' => 'font_family',
                'label' => 'Font Family',
                'description' => 'Select your preferred font family for the CRM interface.',
                'rules' => 'required', // Include options
                'value' => 'Inter', // Default value
                'options' => [
                    'Inter' => 'Inter',
                    'Lato' => 'Lato',
                    'Montserrat' => 'Montserrat',
                    'Poppins' => 'Poppins',
                    'Roboto' => 'Roboto',
                ],
            ],
        ]
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
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'session_expiry', // unique name for field
                'label' => 'Session Expiry', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '60', // default value if you want
                'options' => [
                    "60" => '1 Hour',
                    "360" => '6 Hours',
                    "720" => '12 Hours',
                    "1440" => '24 Hours',
                    "10080" => '1 Week',
                ]
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
                'name' => 'is_group_range', // unique name for field
                'label' => 'Group Range', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ],
    ],

    'legal_links' => [
        'title' => 'Legal Links',
        'elements' => [
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_terms_and_conditions_link', // unique name for field
                'label' => 'Terms and Conditions Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want

            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_terms_and_conditions_show', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_terms_and_conditions_on_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_terms_and_conditions_on_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],


            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_client_agreement_link', // unique name for field
                'label' => 'Client Agreement Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_client_agreement_show', // unique name for field
                'label' => 'Client Agreement', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_client_agreement_on_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_client_agreement_on_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],


            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_complaints_handling_policy_link', // unique name for field
                'label' => 'Complaints Handling Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_complaints_handling_policy_show', // unique name for field
                'label' => 'Complaints Handling Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_complaints_handling_policy_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_complaints_handling_policy_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],


            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_cookies_policy_link', // unique name for field
                'label' => 'Cookies Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_cookies_policy_show', // unique name for field
                'label' => 'Cookies Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_cookies_policy_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_cookies_policy_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],



            // [
            //     'type' => 'url', // input fields type
            //     'data' => 'string', // data type, string, int, boolean
            //     'name' => 'IB_partner_agreement_link', // unique name for field
            //     'label' => 'IB Partner Agreement Link', // you know what label it is
            //     'rules' => '', // validation rule of laravel
            //     'value' => null, // default value if you want
            // ],
            // [
            //     'type' => 'checkbox', // input fields type
            //     'data' => 'boolean', // data type, string, int, boolean
            //     'name' => 'IB_partner_agreement_show', // unique name for field
            //     'label' => 'IB Partner Agreement', // you know what label it is
            //     'rules' => 'required', // validation rule of laravel
            //     'value' => 0, // default value if you want
            // ],

            // [
            //     'type' => 'url', // input fields type
            //     'data' => 'string', // data type, string, int, boolean
            //     'name' => 'order_execution_policy_link', // unique name for field
            //     'label' => 'Order Execution Policy Link', // you know what label it is
            //     'rules' => '', // validation rule of laravel
            //     'value' => null, // default value if you want
            // ],
            // [
            //     'type' => 'checkbox', // input fields type
            //     'data' => 'boolean', // data type, string, int, boolean
            //     'name' => 'order_execution_policy_show', // unique name for field
            //     'label' => 'Order Execution Policy', // you know what label it is
            //     'rules' => 'required', // validation rule of laravel
            //     'value' => 0, // default value if you want
            // ],

            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_privacy_policy_link', // unique name for field
                'label' => 'Privacy Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_privacy_policy_show', // unique name for field
                'label' => 'Privacy Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_privacy_policy_on_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_privacy_policy_on_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],


            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_risk_disclosure_link', // unique name for field
                'label' => 'Risk Disclosure Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_risk_disclosure_show', // unique name for field
                'label' => 'Risk Disclosure', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_risk_disclosure_on_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_risk_disclosure_on_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],


            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'legal_US_clients_policy_link', // unique name for field
                'label' => 'US Clients Policy Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_US_clients_policy_show', // unique name for field
                'label' => 'US Clients Policy', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_US_clients_policy_on_purchase', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'legal_US_clients_policy_on_signup', // unique name for field
                'label' => 'Terms and Conditions', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
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

    'social_links' => [
        'title' => 'Social Links',
        'elements' => [
            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_whatsapp_link',
                'label' => 'Whatsapp Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 48 48" version="1.1">
    
    <title>Whatsapp-color</title>
    <desc>Created with Sketch.</desc>
    <defs>

        </defs>
            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Color-" transform="translate(-700.000000, -360.000000)" fill="#67C15E">
                    <path d="M723.993033,360 C710.762252,360 700,370.765287 700,383.999801 C700,389.248451 701.692661,394.116025 704.570026,398.066947 L701.579605,406.983798 L710.804449,404.035539 C714.598605,406.546975 719.126434,408 724.006967,408 C737.237748,408 748,397.234315 748,384.000199 C748,370.765685 737.237748,360.000398 724.006967,360.000398 L723.993033,360.000398 L723.993033,360 Z M717.29285,372.190836 C716.827488,371.07628 716.474784,371.034071 715.769774,371.005401 C715.529728,370.991464 715.262214,370.977527 714.96564,370.977527 C714.04845,370.977527 713.089462,371.245514 712.511043,371.838033 C711.806033,372.557577 710.056843,374.23638 710.056843,377.679202 C710.056843,381.122023 712.567571,384.451756 712.905944,384.917648 C713.258648,385.382743 717.800808,392.55031 724.853297,395.471492 C730.368379,397.757149 732.00491,397.545307 733.260074,397.27732 C735.093658,396.882308 737.393002,395.527239 737.971421,393.891043 C738.54984,392.25405 738.54984,390.857171 738.380255,390.560912 C738.211068,390.264652 737.745308,390.095816 737.040298,389.742615 C736.335288,389.389811 732.90737,387.696673 732.25849,387.470894 C731.623543,387.231179 731.017259,387.315995 730.537963,387.99333 C729.860819,388.938653 729.198006,389.89831 728.661785,390.476494 C728.238619,390.928051 727.547144,390.984595 726.969123,390.744481 C726.193254,390.420348 724.021298,389.657798 721.340985,387.273388 C719.267356,385.42535 717.856938,383.125756 717.448104,382.434484 C717.038871,381.729275 717.405907,381.319529 717.729948,380.938852 C718.082653,380.501232 718.421026,380.191036 718.77373,379.781688 C719.126434,379.372738 719.323884,379.160897 719.549599,378.681068 C719.789645,378.215575 719.62006,377.735746 719.450874,377.382942 C719.281687,377.030139 717.871269,373.587317 717.29285,372.190836 Z" id="Whatsapp">

        </path>
        </g>
    </g>
</svg>'
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_whatsapp_on_navbar',
                'label' => 'Whatsapp',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_whatsapp_on_dashboard',
                'label' => 'Whatsapp',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_whatsapp_on_auth',
                'label' => 'Whatsapp',
                'rules' => 'required',
                'value' => 0,
            ],

            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_facebook_link',
                'label' => 'Facebook Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 48 48" version="1.1">
    
    <title>Facebook-color</title>
    <desc>Created with Sketch.</desc>
    <defs>

        </defs>
            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Color-" transform="translate(-200.000000, -160.000000)" fill="#4460A0">
                    <path d="M225.638355,208 L202.649232,208 C201.185673,208 200,206.813592 200,205.350603 L200,162.649211 C200,161.18585 201.185859,160 202.649232,160 L245.350955,160 C246.813955,160 248,161.18585 248,162.649211 L248,205.350603 C248,206.813778 246.813769,208 245.350955,208 L233.119305,208 L233.119305,189.411755 L239.358521,189.411755 L240.292755,182.167586 L233.119305,182.167586 L233.119305,177.542641 C233.119305,175.445287 233.701712,174.01601 236.70929,174.01601 L240.545311,174.014333 L240.545311,167.535091 C239.881886,167.446808 237.604784,167.24957 234.955552,167.24957 C229.424834,167.24957 225.638355,170.625526 225.638355,176.825209 L225.638355,182.167586 L219.383122,182.167586 L219.383122,189.411755 L225.638355,189.411755 L225.638355,208 L225.638355,208 Z" id="Facebook">

        </path>
        </g>
    </g>
</svg>',

            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_facebook_on_navbar',
                'label' => 'Facebook',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_facebook_on_dashboard',
                'label' => 'Facebook',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_facebook_on_auth',
                'label' => 'Facebook',
                'rules' => 'required',
                'value' => 0,
            ],


            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_instagram_link',
                'label' => 'Instagram Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 20 20" version="1.1">
    
    <title>instagram [#167]</title>
    <desc>Created with Sketch.</desc>
    <defs>

        </defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -7439.000000)" fill="#D62976">
                    <g id="icons" transform="translate(56.000000, 160.000000)">
                        <path d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792" id="instagram-[#167]">

        </path>
            </g>
        </g>
    </g>
</svg>'
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_instagram_on_navbar',
                'label' => 'Instagram',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_instagram_on_dashboard',
                'label' => 'Instagram',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_instagram_on_auth',
                'label' => 'Instagram',
                'rules' => 'required',
                'value' => 0,
            ],

            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_messenger_link',
                'label' => 'Messenger Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 48 48" version="1.1">
    
    <title>Messenger-color</title>
    <desc>Created with Sketch.</desc>
    <defs>

            </defs>
                <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Color-" transform="translate(-301.000000, -860.000000)" fill="#007FFF">
                        <path d="M325,860 C311.745143,860 301,869.949185 301,882.222222 C301,889.215556 304.489988,895.453481 309.944099,899.526963 L309.944099,908 L318.115876,903.515111 C320.296745,904.118667 322.607155,904.444444 325,904.444444 C338.254857,904.444444 349,894.495259 349,882.222222 C349,869.949185 338.254857,860 325,860 L325,860 Z M327.385093,889.925926 L321.273292,883.407407 L309.347826,889.925926 L322.465839,876 L328.726708,882.518519 L340.503106,876 L327.385093,889.925926 L327.385093,889.925926 Z" id="Messenger">

            </path>
        </g>
    </g>
</svg>'
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_messenger_on_navbar',
                'label' => 'Messenger',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_messenger_on_dashboard',
                'label' => 'Messenger',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_messenger_on_auth',
                'label' => 'Messenger',
                'rules' => 'required',
                'value' => 0,
            ],

            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_telegram_link',
                'label' => 'Telegram Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 256 256" version="1.1" preserveAspectRatio="xMidYMid">
		<g>
				<path d="M128,0 C57.307,0 0,57.307 0,128 L0,128 C0,198.693 57.307,256 128,256 L128,256 C198.693,256 256,198.693 256,128 L256,128 C256,57.307 198.693,0 128,0 L128,0 Z" fill="#40B3E0">

            </path>
                            <path d="M190.2826,73.6308 L167.4206,188.8978 C167.4206,188.8978 164.2236,196.8918 155.4306,193.0548 L102.6726,152.6068 L83.4886,143.3348 L51.1946,132.4628 C51.1946,132.4628 46.2386,130.7048 45.7586,126.8678 C45.2796,123.0308 51.3546,120.9528 51.3546,120.9528 L179.7306,70.5928 C179.7306,70.5928 190.2826,65.9568 190.2826,73.6308" fill="#FFFFFF">

            </path>
                            <path d="M98.6178,187.6035 C98.6178,187.6035 97.0778,187.4595 95.1588,181.3835 C93.2408,175.3085 83.4888,143.3345 83.4888,143.3345 L161.0258,94.0945 C161.0258,94.0945 165.5028,91.3765 165.3428,94.0945 C165.3428,94.0945 166.1418,94.5735 163.7438,96.8115 C161.3458,99.0505 102.8328,151.6475 102.8328,151.6475" fill="#D2E5F1">

            </path>
                            <path d="M122.9015,168.1154 L102.0335,187.1414 C102.0335,187.1414 100.4025,188.3794 98.6175,187.6034 L102.6135,152.2624" fill="#B5CFE4">

            </path>
		</g>
</svg>'
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_telegram_on_navbar',
                'label' => 'Telegram',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_telegram_on_dashboard',
                'label' => 'Telegram',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_telegram_on_auth',
                'label' => 'Telegram',
                'rules' => 'required',
                'value' => 0,
            ],

            [
                'type' => 'url',
                'data' => 'string',
                'name' => 'social_discord_link',
                'label' => 'Discord Link',
                'rules' => '',
                'value' => null,
                'icon' => '
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 -28.5 256 256" version="1.1" preserveAspectRatio="xMidYMid">
    <g>
        <path d="M216.856339,16.5966031 C200.285002,8.84328665 182.566144,3.2084988 164.041564,0 C161.766523,4.11318106 159.108624,9.64549908 157.276099,14.0464379 C137.583995,11.0849896 118.072967,11.0849896 98.7430163,14.0464379 C96.9108417,9.64549908 94.1925838,4.11318106 91.8971895,0 C73.3526068,3.2084988 55.6133949,8.86399117 39.0420583,16.6376612 C5.61752293,67.146514 -3.4433191,116.400813 1.08711069,164.955721 C23.2560196,181.510915 44.7403634,191.567697 65.8621325,198.148576 C71.0772151,190.971126 75.7283628,183.341335 79.7352139,175.300261 C72.104019,172.400575 64.7949724,168.822202 57.8887866,164.667963 C59.7209612,163.310589 61.5131304,161.891452 63.2445898,160.431257 C105.36741,180.133187 151.134928,180.133187 192.754523,160.431257 C194.506336,161.891452 196.298154,163.310589 198.110326,164.667963 C191.183787,168.842556 183.854737,172.420929 176.223542,175.320965 C180.230393,183.341335 184.861538,190.991831 190.096624,198.16893 C211.238746,191.588051 232.743023,181.531619 254.911949,164.955721 C260.227747,108.668201 245.831087,59.8662432 216.856339,16.5966031 Z M85.4738752,135.09489 C72.8290281,135.09489 62.4592217,123.290155 62.4592217,108.914901 C62.4592217,94.5396472 72.607595,82.7145587 85.4738752,82.7145587 C98.3405064,82.7145587 108.709962,94.5189427 108.488529,108.914901 C108.508531,123.290155 98.3405064,135.09489 85.4738752,135.09489 Z M170.525237,135.09489 C157.88039,135.09489 147.510584,123.290155 147.510584,108.914901 C147.510584,94.5396472 157.658606,82.7145587 170.525237,82.7145587 C183.391518,82.7145587 193.761324,94.5189427 193.539891,108.914901 C193.539891,123.290155 183.391518,135.09489 170.525237,135.09489 Z" fill="#5865F2" fill-rule="nonzero">

        </path>
    </g>
</svg>'
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_discord_on_navbar',
                'label' => 'Discord',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_discord_on_dashboard',
                'label' => 'Discord',
                'rules' => 'required',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'social_discord_on_auth',
                'label' => 'Discord',
                'rules' => 'required',
                'value' => 0,
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
                    'match_trader' => \App\Enums\TraderType::MT,
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

    'match_trader_platform_api' => [
        'title' => 'Meta Trader Platform API Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt_live_server_real', // unique name for field
                'label' => 'Live Server Name', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'Match Trader Server', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt_network_address_real', // unique name for field
                'label' => 'Network Address', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'https://broker-api-demo.match-trader.com', // default value if you want
            ],
            [
                'type' => 'select', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt_api_version_real', // unique name for field
                'label' => 'API Version', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'v1', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt_api_key_real', // unique name for field
                'label' => 'API Key', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'select', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'mt_server_enabled', // unique name for field
                'label' => 'Meta Trader Enabled', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 'enabled', // default value if you want
            ],
        ]
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
    'dev_mode' => [
        'title' => 'Development Mode',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'debug_mode', // unique name for field
                'label' => 'Development Mode', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ]
    ],
    'end_to_end_encryption' => [
        'title' => 'End To End Encryption',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'enc_mode', // unique name for field
                'label' => 'End To End Encryption', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ]
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
