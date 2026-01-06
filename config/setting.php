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
                'description' => 'The main title of your platform, displayed in browser tabs and site headers',
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => 'CRM', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo', // unique name for field
                'label' => 'Desktop Logo (Dark)', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'example_logo' => 'fallback/branding/desktop-logo.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_logo_light', // unique name for field
                'label' => 'Desktop Logo (Light)', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'example_logo' => 'fallback/branding/desktop-logo.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_mobile_logo', // unique name for field
                'label' => 'Mobile / Admin Logo (Dark)', // you know what label it is
                'description' => 'Recommended Size 32 x 32',
                'example_logo' => 'fallback/branding/mobile-admin-logo.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_mobile_logo_light', // unique name for field
                'label' => 'Mobile / Admin Logo (Light)', // you know what label it is
                'description' => 'Recommended Size 32 x 32',
                'example_logo' => 'fallback/branding/mobile-admin-logo.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_favicon', // unique name for field
                'label' => 'Site Favicon', // you know what label it is
                'description' => 'Recommended Size 32 x 32',
                'example_logo' => 'fallback/branding/favicon.png',
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'image/logo.png', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'login_bg', // unique name for field
                'label' => 'Login/Signup Cover', // you know what label it is
                'description' => 'Recommended Size 935 x 920',
                'example_logo' => 'fallback/branding/admin-login-cover.png',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'link_thumbnail', // unique name for field
                'label' => 'Link Thumbnail Image ', // you know what label it is
                'description' => 'Recommended Size 1600 x 627',
                'example_logo' => 'fallback/branding/thumbnail.png',
                'rules' => 'mimes:jpeg,jpg,png|max:2000', // validation rule of laravel
                'value' => 'default/auth-bg.jpg', // default value if you want
            ],
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'default_transaction_method', // unique name for field
                'label' => 'Default Transaction Image', // you know what label it is
                'description' => 'Recommended Size 160 x 40',
                'example_logo' => 'fallback/branding/transaction-logo.png',
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
                'name' => 'base_color', // unique name for field
                'label' => 'Base Color', // you know what label it is
                'description' => 'Select the base color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'header_bg', // unique name for field
                'label' => 'Header Color', // you know what label it is
                'description' => 'Select the header background color for the light theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'header_color', // unique name for field
                'label' => 'Header text', // you know what label it is
                'description' => 'Select the header text color for the light theme',
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
                'name' => 'header_bg_dark', // unique name for field
                'label' => 'Header Color', // you know what label it is
                'description' => 'Select the header background color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#ffffff', // default value if you want
            ],
            [
                'type' => 'color', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'header_color_dark', // unique name for field
                'label' => 'Header text', // you know what label it is
                'description' => 'Select the header text color for the dark theme',
                'rules' => 'required', // validation rule of laravel
                'value' => '#0f172a', // default value if you want
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
                'description' => 'Displayed name of your site or portal',
                'rules' => 'required|min:2|max:50', // validation rule of laravel
                'value' => 'Brokeret', // default value if you want
            ],
            [
                'type' => 'text', // input fields type (allow multiple comma-separated emails)
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_email', // unique name for field
                'label' => 'Site Email', // you know what label it is
                'description' => 'Comma-separated email addresses used for system notifications. e.g. admin@example.com, support@example.com',
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin@tdevs.co', // default value if you want
            ],
            [
                'type' => 'text', // input fields type (allow multiple comma-separated emails)
                'data' => 'string', // data type, string, int, boolean
                'name' => 'user_site_email', // unique name for field
                'label' => 'New User Notification', // you know what label it is
                'description' => 'Comma-separated email addresses to notify on new user registration. e.g. abc@gmail.com, xyz@gmail.com',
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin@tdevs.co', // default value if you want
            ],
            [
                'type' => 'text', // input fields type (allow multiple comma-separated emails)
                'data' => 'string', // data type, string, int, boolean
                'name' => 'staff_site_email', // unique name for field
                'label' => 'New Staff Notification', // you know what label it is
                'description' => 'Comma-separated email addresses to notify on new staff registration. e.g. abc@gmail.com, xyz@gmail.com',
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin@tdevs.co', // default value if you want
            ],
            [
                'type' => 'email', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'support_email', // unique name for field
                'label' => 'Support Email', // you know what label it is
                'description' => 'Email address for customer support inquiries',
                'rules' => 'required', // validation rule of laravel
                'value' => 'support@tdevs.co', // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'company_website', // unique name for field
                'label' => 'Company Website', // you know what label it is
                'description' => 'Public URL of the company website',
                'rules' => 'required', // validation rule of laravel
                'value' => 'https://company.com', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'company_phone', // unique name for field
                'label' => 'Company Phone', // you know what label it is
                'description' => 'Official contact number for the company',
                'rules' => 'required', // validation rule of laravel
                'value' => '123456789', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'registered_address', // unique name for field
                'label' => 'Registered Address', // you know what label it is
                'description' => "Company's legal registration number",
                'rules' => 'required', // validation rule of laravel
                'value' => 'United State', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'registered_number', // unique name for field
                'label' => 'Registered Number', // you know what label it is
                'description' => 'Official address used for registration',
                'rules' => 'required', // validation rule of laravel
                'value' => '123456789', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copyright_text', // unique name for field
                'label' => 'Copyright Text', // you know what label it is
                'description' => 'Footer text for copyright or legal notice',
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
                'description' => 'Custom URL path to access the admin panel (e.g., yourdomain.com/backoffice)',
                'rules' => 'required', // validation rule of laravel
                'value' => 'admin', // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'site_timezone', // unique name for field
                'label' => 'Site Timezone', // you know what label it is
                'description' => 'Sets the default timezone for all system timestamps and activities. All database records are stored in UTC and displayed according to this timezone.',
                'rules' => 'required', // validation rule of laravel
                'value' => 'UTC', // default value if you want
                'options' => [], // Options populated via JavaScript using getAllTimezones() helper
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'session_expiry', // unique name for field
                'label' => 'Session Expiry', // you know what label it is
                'description' => 'Defines how long a user remains logged in without activity',
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
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_whitelabel', // unique name for field
                'label' => 'White Label', // you know what label it is
                'description' => 'Toggle to hide branding and white-label the platform interface',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'admin_2fa_enabled', // unique name for field
                'label' => 'Admin Email Two-Factor Authentication', // you know what label it is
                'description' => 'Enable two-factor authentication for admin login using email verification codes',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ],
    ],

    'permission' => [
        'title' => 'Permission Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'new_trading_accounts', // unique name for field
                'label' => 'New Trading Accounts', // you know what label it is
                'description' => 'Allow creation of new accounts',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => '90_days_in_activity_trade_disable', // unique name for field
                'label' => '90 Days In-Activity Trade Disable', // you know what label it is
                'description' => 'Disable trading after 90 days of no activity',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'delete_archived_accounts', // unique name for field
                'label' => 'Delete Archived Accounts', // you know what label it is
                'description' => 'Enable deletion of archived accounts',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'automatic_withdrawals', // unique name for field
                'label' => 'Automatic Withdrawals', // you know what label it is
                'description' => 'Enable automatic withdrawal handling',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'automatic_deposits', // unique name for field
                'label' => 'Automatic Deposits', // you know what label it is
                'description' => 'Enable auto-processing of deposits',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'disable_trading', // unique name for field
                'label' => 'Disable Trading (No Balance)', // you know what label it is
                'description' => 'Block trading when balance is zero',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_ranking', // unique name for field
                'label' => 'User Ranking (show/hide)', // you know what label it is
                'description' => 'Show or hide user performance ranking',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_forex_group_range', // unique name for field
                'label' => 'Forex Group Range', // you know what label it is
                'description' => 'Enable control over allowed groups',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'auto_exchange_rates_update', // unique name for field
                'label' => 'Auto Exchange Rates Update', // you know what label it is
                'description' => 'Enable automatic exchange rates updates from external APIs',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value - enabled by default
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'phone_number_restriction', // unique name for field
                'label' => 'Duplicate Phone Number Restriction', // you know what label it is
                'description' => 'Restrict one phone number to one user account only',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'forex_account_create_limit', // unique name for field
                'label' => 'Forex Account Limit', // you know what label it is
                'description' => 'Set max number of accounts per user',
                'rules' => 'required', // validation rule of laravel
                'value' => 10, // default value if you want
            ],

        ],
    ],

    'kyc_permissions' => [
        'title' => 'Kyc Miscellaneous',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'deposit_amount', // unique name for field
                'label' => 'Deposit Amount', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'withdraw_amount', // unique name for field
                'label' => 'Withdraw Amount', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'internal_transfer_amount', // unique name for field
                'label' => 'Internal Transfer Amount', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'external_transfer_amount', // unique name for field
                'label' => 'External Transfer Amount', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
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
                'name' => 'master_ib_request', // unique name for field
                'label' => 'Master IB Request', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
        ],
    ],
    'customer_permission' => [
        'title' => 'Customers',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_name_edit', // unique name for field
                'label' => 'Edit Name', // you know what label it is
                'description' => 'Allow customers to edit their name',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_phone_edit', // unique name for field
                'label' => 'Edit Phone', // you know what label it is
                'description' => 'Allow customers to edit their phone number',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_username_edit', // unique name for field
                'label' => 'Edit Username', // you know what label it is
                'description' => 'Allow changing the username',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_email_edit', // unique name for field
                'label' => 'Edit Email', // you know what label it is
                'description' => 'Allow email address changes',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_country_edit', // unique name for field
                'label' => 'Edit Country', // you know what label it is
                'description' => 'Enable country field editing',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'customer_dob_edit', // unique name for field
                'label' => 'Edit Date Of Birth', // you know what label it is
                'description' => 'Allow updating birth date info',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'account_creation', // unique name for field
                'label' => 'Account Creation', // you know what label it is
                'description' => 'Permit creation of new accounts',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'email_verification', // unique name for field
                'label' => 'Email Verification', // you know what label it is
                'description' => 'Require email verification',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'kyc_verification', // unique name for field
                'label' => 'KYC Verification', // you know what label it is
                'description' => 'Require KYC verification',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'fa_verification', // unique name for field
                'label' => '2FA Verification', // you know what label it is
                'description' => 'Enable two-factor authentication',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_deposit', // unique name for field
                'label' => 'Deposit', // you know what label it is
                'description' => 'Allow customer to deposit funds',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_withdraw', // unique name for field
                'label' => 'Withdraw', // you know what label it is
                'description' => 'Allow customer to withdraw funds',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'user_tickets_feature', // unique name for field
                'label' => 'Tickets Feature', // you know what label it is
                'description' => 'Enable support ticket functionality',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
        ]
    ],
    'customer_misc' => [
        'title' => 'Customer Miscellaneous',
        'elements' => [
            [
                'type' => 'number',
                'data' => 'int',
                'name' => 'user_removal_grace_period',
                'label' => 'User Removal Grace Period (Days)',
                'description' => 'Number of days after which inactive users will be automatically removed.',
                'rules' => 'required|integer|min:1|max:365',
                'value' => 30, // default value
            ],
             [
                'type' => 'checkbox',
                'data' => 'int',
                'name' => 'grace_period',
                'label' => 'Grace Period ',
                'rules' => 'required',
                'value' => 1, // default value
            ],
        ],
    ],

    'staff_permission' => [
        'title' => 'Staff Permissions',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'email_verification', // unique name for field
                'label' => 'Email Verification', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
        ]
    ],
    'popup' => [
        'title' => 'Popup Settings',
        'elements' => [
            [
                'type' => 'file', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'popup_image', // unique name for field
                'label' => 'Popup Image', // you know what label it is
                'rules' => 'mimes:jpeg,jpg,png|max:1000', // validation rule of laravel
                'value' => 'default/fav.png', // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'popup_status', // unique name for field
                'label' => 'Popup Status', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'popup_btn_text', // unique name for field
                'label' => 'Popup Button Text', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'popup_btn_link', // unique name for field
                'label' => 'Popup Button Link', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'dropdown', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'popup_btn_alignment', // unique name for field
                'label' => 'Button Alignment', // you know what label it is
                'rules' => '', // validation rule of laravel
                'value' => 'right', // default value if you want
                'options' => [
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ],
            ]
        ]
    ],
     'provider_logo' => [
        'title' => 'Provider Logo Settings',
        'elements' => [
            [
                'type' => 'file',
                'data' => 'string',
                'name' => 'provider_logo_image',
                'label' => 'Provider Logo Image',
                'rules' => 'mimes:jpeg,jpg,png,svg|max:2048',
                'value' => 'backend/images/brokeret_logo.png',
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'provider_logo_status',
                'label' => 'Enable Custom Logo',
                'rules' => '',
                'value' => 1,
            ],
        ]
    ],
    'admin_auth_logo' => [
        'title' => 'Admin Auth Logo Settings',
        'elements' => [
            [
                'type' => 'file',
                'data' => 'string',
                'name' => 'admin_auth_logo_image',
                'label' => 'Admin Auth Logo Image',
                'rules' => 'mimes:jpeg,jpg,png,svg|max:2048',
                'value' => 'backend/images/brokeret_logo.png',
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'admin_auth_logo_status',
                'label' => 'Enable Admin Auth Logo',
                'rules' => '',
                'value' => 1,
            ],
        ]
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
                'description' => 'URL (Iframe) for followers to sign up or access copy trading as subscribers',
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_provider_access', // unique name for field
                'label' => 'Copy Trading Provider Access(iframe)', // you know what label it is
                'description' => 'URL (Iframe) for traders who want to register and provide strategies.',
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_ratings', // unique name for field
                'label' => 'Copy Trading Rating(iframe)', // you know what label it is
                'description' => 'Embedded Iframe showing trader performance ratings on the platform',
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'url', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'copy_trading_ratings_js', // unique name for field
                'label' => 'Copy Trading Rating(Js)', // you know what label it is
                'description' => 'JavaScript-based rating widget link, ideal for external websites or dynamic UI',
                'rules' => '', // validation rule of laravel
                'value' => null, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_copy_trading', // unique name for field
                'label' => 'Status', // you know what label it is
                'description' => 'Toggle to enable or disable the copy trading feature',
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
    'transfer_internal' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_internal_transfer', // unique name for field
                'label' => 'Status', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_min_send', // unique name for field
                'label' => 'Internal Minimum Transfer', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_max_send', // unique name for field
                'label' => 'Internal Maximum Transfer', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'internal_send_charge_type', // unique name for field
                'label' => 'Internal Transfer Type', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'internal_send_charge', // unique name for field
                'label' => 'Internal Transfer Charge', // you know what label it is
                'description' => '',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'internal_send_daily_limit', // unique name for field
                'label' => 'Transfers Per Day', // you know what label it is
                'description' => '',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
        ],
    ],
    'transfer_external' => [
        'title' => 'Site Fee, Limit and Bonus Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_min_send', // unique name for field
                'label' => 'External Minimum Transfer', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_max_send', // unique name for field
                'label' => 'External Maximum Transfer', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'external_send_charge', // unique name for field
                'label' => 'External Transfer Charge', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'external_send_charge_type', // unique name for field
                'label' => 'External Transfer Type', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'external_send_daily_limit', // unique name for field
                'label' => 'Transfers Per Day', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 6, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer', // unique name for field
                'label' => 'Status', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer_auto_approve', // unique name for field
                'label' => 'Status', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'is_external_transfer_purpose', // unique name for field
                'label' => 'Status', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
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
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'wallet_exchange_charge', // unique name for field
                'label' => 'Wallet Exchange Charge', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 90000, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'wallet_exchange_day_limit', // unique name for field
                'label' => 'Wallet Exchange Limit', // you know what label it is
                'description' => '',
                'rules' => 'required', // validation rule of laravel
                'value' => 10, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'withdraw_day_limit', // unique name for field
                'label' => 'Withdraw Day Limit', // you know what label it is
                'description' => '',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 11, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'investment_cancellation_daily_limit', // unique name for field
                'label' => 'Investment Cancellation Daily Limit', // you know what label it is
                'description' => '',
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
                'description' => 'Max pending deposits allowed per user',
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
                'description' => 'Maximum number of withdrawal requests allowed to remain pending at a time',
                'rules' => 'required', // validation rule of laravel
                'value' => 3, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'min_ib_wallet_withdraw_limit', // unique name for field
                'label' => 'Min IB Wallet Withdraw Limit', // you know what label it is
                'description' => 'Minimum amount an IB user must have to withdraw from their wallet',
                'rules' => 'required', // validation rule of laravel
                'value' => 100, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'int', // data type, string, int, boolean
                'name' => 'withdraw_otp_expires', // unique name for field
                'label' => 'Withdraw OTP Expires(In Minutes)', // you know what label it is
                'description' => 'Time (in minutes) before the OTP for withdrawals becomes invalid',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 5, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'withdraw_otp', // unique name for field
                'label' => 'Withdraw OTP', // you know what label it is
                'description' => 'Enable this to require an OTP (One-Time Password) for withdrawals',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'withdraw_account_otp', // unique name for field
                'label' => 'User Withdraw Account Creation OTP', // you know what label it is
                'description' => 'Enable this to require an OTP for creating withdraw accounts',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                "type" => "checkbox", // input fields type
                "data" => "boolean", // data type, string, int, boolean
                "name" => "withdraw_account_approval", // unique name for field
                "label" => "Withdraw Account Manual Approval", // you know what label it is
                "description" => "Enable this to require manual admin approval for withdraw account creation even if withdraw account creation otp is enabled or disabled",
                "rules" => "required", // validation rule of laravel
                "value" => 0, // default value if you want
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
                'label' => 'Forex Account Limit', // you know what label it is
                'rules' => 'required', // validation rule of laravel
                'value' => 10, // default value if you want
            ],
        ],
    ],
    'features' => [
        'title' => 'Features Settings',
        'elements' => [
            [
                'type' => 'radio', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'deposit_account_mode', // unique name for field
                'label' => 'Deposit Account Mode', // you know what label it is
                'description' => 'Choose between default deposit accounts managed by admin or user request-based deposit accounts',
                'rules' => 'required', // validation rule of laravel
                'value' => 'default_deposit_accounts', // default value - this option will be checked by default
                'options' => [
                    'default_deposit_accounts' => 'Default Deposit Accounts',
                    'request_deposit_accounts' => 'Request Deposit Account',
                ],
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'withdraw_deduction', // unique name for field
                'label' => 'Withdraw Deduction', // you know what label it is
                'description' => 'Choose if withdrawals are deducted immediately (On Request) or after approval (On Approval)',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'copy_trading', // unique name for field
                'label' => 'Copy Trading', // you know what label it is
                'description' => 'Enable or disable the Copy Trading feature for users',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'radio', // Change type to 'radio'
                'data' => 'string',
                'name' => 'active_trader_type',
                'label' => 'Trader Type',
                'description' => 'Specify which trading platform types are supported (e.g., MT5, X9, C_Trader, or All)',
                'rules' => 'required', // Include options
                'value' => \App\Enums\TraderType::MT5, // Default value
                'options' => [
                    'mt5' => \App\Enums\TraderType::MT5,
                    'x9' => \App\Enums\TraderType::X9,
                    'c_trader' => \App\Enums\TraderType::CTRADER,
                    'all' => \App\Enums\TraderType::All,
                ],
            ],
            [
                'type' => 'radio', // Change type to 'radio'
                'data' => 'string',
                'name' => 'leverage_approval',
                'label' => 'Leverage Approval',
                'description' => 'Set leverage changes to be handled automatically or require admin approval',
                'rules' => 'required', // Include options
                'value' => 'auto', // Default value
                'options' => [
                    'auto' => 'auto',
                    'by_admin' => 'By admin',
                ],
            ],
            [
                'type' => 'text',  // Using text type with numeric restrictions
                'data' => 'integer',  
                'name' => 'ib_distribution_time',
                'label' => 'IB Distribution Time (in minutes)',
                'rules' => 'required|integer|min:1',  // Validation rules
                'value' => 5,  // Default value (1 minutes)
                'attributes' => [
                    'inputmode' => 'numeric',  // Shows numeric keyboard on mobile
                'pattern' => '^[1-9][0-9]*$',     // HTML5 pattern for positive integers (allows 10, 11, ...)
                'oninput' => 'this.value = this.value.replace(/\D/g, "").replace(/^0+/, "")', // Allow digits, strip non-digits and leading zeros
                    'placeholder' => 'Enter minutes',
                ],
            ],
            [
                "type" => "checkbox", // input fields type
                "data" => "boolean", // data type, string, int, boolean
                "name" => "live_account_creation", // unique name for field
                "label" => "Live Account Admin Approval", // you know what label it is
                "description" => "Enable this to require manual admin approval for live account creation.",
                "rules" => "required", // validation rule of laravel
                "value" => 0, // default value if you want
            ],
            [
                "type" => "checkbox", // input fields type
                "data" => "boolean", // data type, string, int, boolean
                "name" => "demo_account_creation", // unique name for field
                "label" => "Demo Account Admin Approval", // you know what label it is
                "description" => "Enable this to require manual admin approval for demo account creation.",
                "rules" => "required", // validation rule of laravel
                "value" => 0, // default value if you want
            ]
//            [
//                'type' => 'radio', // Change type to 'radio'
//                'data' => 'string',
//                'name' => 'active_data_sync_way',
//                'label' => 'Trader Type',
//                'description' =>> '',
//                'rules' => 'required', // Include options
//                'value' => \App\Enums\TraderType::MT5, // Default value
//                'options' => [
//                    'mt5' => \App\Enums\TraderType::MT5,
//                    'x9' => \App\Enums\TraderType::X9,
//                    'c_trader' => \App\Enums\TraderType::CTRADER,
//                    'all' => \App\Enums\TraderType::All,
//                ],
//            ],
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
                'rules' => 'required', // validation rule of laravel
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
                'description' => 'Do not enable it unless you want the site need to be under Maintenance',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'secret_key', // unique name for field
                'label' => 'Secret Key', // you know what label it is
                'description' => 'Remember the Secret Key. Use domain/secret-key to trun back the website live',
                'rules' => 'required', // validation rule of laravel
                'value' => 'secret', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_title', // unique name for field
                'label' => 'Title', // you know what label it is
                'description' => 'Headline shown to users during maintenance',
                'rules' => 'required', // validation rule of laravel
                'value' => 'Site is not under maintenance', // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'maintenance_text', // unique name for field
                'label' => 'Maintenance Text', // you know what label it is
                'description' => 'Custom message displayed to inform users of downtime',
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
                'description' => 'Toggle to enable or disable the GDPR notice on the site',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_label', // unique name for field
                'label' => 'Button Label', // you know what label it is
                'description' => 'Text shown on the GDPR button (e.g., Learn More)',
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => 'Learn More', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_button_url', // unique name for field
                'label' => 'Button URL', // you know what label it is
                'description' => 'Link to your privacy policy or related page',
                'rules' => 'required|max:500', // validation rule of laravel
                'value' => '/privacy-policy', // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'gdpr_text', // unique name for field
                'label' => 'GDPR Text', // you know what label it is
                'description' => 'Message explaining your data collection purpose',
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
                'description' => 'General disclaimer shown on client-facing interfaces',
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'risk_warning', // unique name for field
                'label' => 'Risk Warning', // you know what label it is
                'description' => 'Important risk disclosure for users',
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'footer_content', // unique name for field
                'label' => 'Footer', // you know what label it is
                'description' => 'Text displayed at the bottom of public pages',
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
    'email_template' => [
        'title' => 'Template Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'email_show_site_logo', // unique name for field
                'label' => 'Show/Hide Site Logo In Emails Headers', // you know what label it is
                'description' => 'enable to show or disable to hide the site logo in emails header',
                'rules' => 'nullable', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'textarea', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_disclaimer', // unique name for field
                'label' => 'Disclaimer', // you know what label it is
                'description' => 'Add a general disclaimer message that will appear at the end of each email',
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_risk_warning', // unique name for field
                'label' => 'Risk Warning', // you know what label it is
                'description' => 'Include a cautionary statement to alert users of potential financial risks',
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'email_footer', // unique name for field
                'label' => 'Footer', // you know what label it is
                'description' => 'Set the default email closing text',
                'rules' => 'nullable', // validation rule of laravel
                'value' => '', // default value if you want
            ],

        ],
    ],
    'bonus_settings' => [
        'title' => 'Bonus Settings',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'referral_bonus', // unique name for field
                'label' => 'Referral Bonus', // you know what label it is
                'description' => 'Amount awarded to a user for referring a new customer',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'double', // data type, string, int, boolean
                'name' => 'signup_bonus', // unique name for field
                'label' => 'Sign Up Bonus', // you know what label it is
                'description' => 'Bonus credited to users upon successful registration',
                'rules' => 'required|regex:/^\d+(\.\d{1,2})?$/', // validation rule of laravel
                'value' => 20, // default value if you want
            ],
        ],
    ],
    'notification_tune' => [
        'title' => 'Notification Tune',
        'elements' => [
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'deposit_notification_tune', // unique name for field
                'label' => 'Deposit Notification Tune', // label for field
                'rules' => '', // validation rule of laravel
                'value' => 'global/tune/knock_knock.mp3', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'withdraw_notification_tune', // unique name for field
                'label' => 'Withdraw Notification Tune', // label for field
                'rules' => '', // validation rule of laravel
                'value' => 'global/tune/knock_knock.mp3', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'transfer_notification_tune', // unique name for field
                'label' => 'Transfer Notification Tune', // label for field
                'rules' => '', // validation rule of laravel
                'value' => 'global/tune/knock_knock.mp3', // default value if you want
            ],
            [
                'type' => 'text', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'default_notification_tune', // unique name for field
                'label' => 'Default Notification Tune', // label for field
                'rules' => '', // validation rule of laravel
                'value' => 'global/tune/knock_knock.mp3', // default value if you want
            ],
        ],
    ],
    'user_dashboard' => [
        'title' => 'User Dashboard',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'is_desktop_dashboard_quick_link',
                'label' => 'Enable Desktop Dashboard Quick Links',
                'description' => 'Show or hide quick links section on the dashboard',
                'rules' => 'required',
                'value' => true,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'is_mobile_dashboard_quick_link',
                'label' => 'Enable Mobile Dashboard Quick Links',
                'description' => 'Show or hide quick links section on the dashboard',
                'rules' => 'required',
                'value' => true,
            ],
        ],
    ],
    'contact_widget' => [
        'title' => 'Contact Widget',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'contact_widget_deposit_page',
                'label' => 'Enable Deposit Page',
                'description' => 'Show or hide contact widget on the deposit page',
                'rules' => 'required',
                'value' => true,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'contact_widget_withdraw_page',
                'label' => 'Enable Withdraw Page',
                'description' => 'Show or hide contact widget on the withdraw page',
                'rules' => 'required',
                'value' => true,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'contact_widget_transfer_page',
                'label' => 'Enable Transfer Page',
                'description' => 'Show or hide contact widget on the transfer page',
                'rules' => 'required',
                'value' => true,
            ],
        ],
    ],
    // Account Type Settings
   'account_type_settings' => [
        'title' => 'Account Type Settings',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'show_global_accounts_with_country_tags',
                'label' => 'Show Global Accounts with Country & Tags',
                'description' => 'if enable this it shows global accounts with country and tags if disable it hides the global accounts',
                'rules' => 'nullable',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'show_global_accounts_with_ib_rebate_rules',
                'label' => 'Show Global Accounts with Ib Rebate Rules',
                'description' => 'if enable this it shows global accounts with Ib Rebate Rule if disable it hides the global accounts',
                'rules' => 'nullable',
                'value' => 1,
            ],
        ],
    ],

    'smtp_monitoring' => [
        'title' => 'SMTP Monitoring Settings',
        'elements' => [
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'smtp_monitoring_enabled', // unique name for field
                'label' => 'Enable SMTP Monitoring', // you know what label it is
                'description' => 'Automatically detect and alert on email sending failures',
                'rules' => 'required', // validation rule of laravel
                'value' => 1, // default value if you want
            ],
            [
                'type' => 'number', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'smtp_alert_cooldown',
                'label' => 'Alert Cooldown Period (seconds)',
                'description' => 'Minimum time between push notifications (60-86400 seconds)',
                'rules' => 'required|numeric|min:60|max:86400', // validation rule of laravel
                'value' => '1800', // default value if you want
            ],
            [
                'type' => 'number', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'smtp_failure_threshold',
                'label' => 'Failure Threshold',
                'description' => 'Number of failures before sending escalated alert',
                'rules' => 'required|numeric|min:1|max:100', // validation rule of laravel
                'value' => '3', // default value if you want
            ],
            [
                'type' => 'checkbox', // input fields type
                'data' => 'boolean', // data type, string, int, boolean
                'name' => 'smtp_health_check_enabled', // unique name for field
                'label' => 'Enable Scheduled Health Checks', // you know what label it is
                'description' => 'Periodically test SMTP connection via cron job',
                'rules' => 'required', // validation rule of laravel
                'value' => 0, // default value if you want
            ],
            [
                'type' => 'select', // input fields type
                'data' => 'string', // data type, string, int, boolean
                'name' => 'smtp_health_check_interval', // unique name for field
                'label' => 'Health Check Interval', // you know what label it is
                'description' => 'How often to run automated SMTP health checks',
                'rules' => 'required', // validation rule of laravel
                'options' => [ // options for the select box
                    '5' => 'Every 5 minutes',
                    '15' => 'Every 15 minutes',
                    '30' => 'Every 30 minutes',
                    '60' => 'Every 1 hour',
                ],
                'value' => '15', // default value if you want
            ],
        ],
    ],
    'company_register' => [
        'title' => 'Company Registration Form',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'company_form_status',
                'label' => 'Enable Company Registration Form',
                'rules' => 'nullable',
                'value' => 0,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'company_form_admin_approval',
                'label' => 'Require Admin Approval',
                'rules' => 'nullable',
                'value' => 1,
            ],
            [
                'type' => 'textarea',
                'data' => 'string',
                'name' => 'company_form_fields',
                'label' => 'Form Fields (JSON)',
                'description' => 'Automatically managed. Do not edit manually.',
                'rules' => 'nullable',
                'value' => null,
            ],
        ],
    ],
    'forex_daily_reporting' => [
        'title' => 'Forex Daily Reporting Settings',
        'elements' => [
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'daily_statement_enabled',
                'label' => 'Enable Daily Account Statements',
                'description' => 'Enable or disable the daily account statements',
                'rules' => 'nullable',
                'value' => 0,
            ],
            [
                'type' => 'time',
                'data' => 'string',
                'name' => 'daily_statement_time',
                'label' => 'Statement Sending Time',
                'description' => 'The time of day to send the daily account statements',
                'rules' => 'nullable|date_format:H:i',
                'value' => '23:00',
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'daily_statement_timezone',
                'label' => 'Timezone',
                'description' => 'The timezone to use for the daily account statements',
                'rules' => 'nullable|string|max:50',
                'options' => [
                    'UTC' => 'UTC (Coordinated Universal Time)',
                    // Asian Trading Session
                    'Asia/Tokyo' => 'Asia/Tokyo (Japan Standard Time)',
                    'Asia/Shanghai' => 'Asia/Shanghai (China Standard Time)',
                    'Asia/Hong_Kong' => 'Asia/Hong_Kong (Hong Kong Time)',
                    'Asia/Singapore' => 'Asia/Singapore (Singapore Time)',
                    'Asia/Dubai' => 'Asia/Dubai (Gulf Standard Time)',
                    'Australia/Sydney' => 'Australia/Sydney (Australian Eastern Time)',
                    // European Trading Session
                    'Europe/London' => 'Europe/London (Greenwich Mean Time)',
                    'Europe/Frankfurt' => 'Europe/Frankfurt (Central European Time)',
                    'Europe/Paris' => 'Europe/Paris (Central European Time)',
                    'Europe/Zurich' => 'Europe/Zurich (Central European Time)',
                    // American Trading Session
                    'America/New_York' => 'America/New_York (Eastern Time)',
                    'America/Chicago' => 'America/Chicago (Central Time)',
                    'America/Los_Angeles' => 'America/Los_Angeles (Pacific Time)',
                    // Other Major Centers
                    'Pacific/Auckland' => 'Pacific/Auckland (New Zealand Time)',
                ],
                'value' => 'UTC',
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'daily_statement_account_types',
                'label' => 'Account Types',
                'description' => 'The account types to include in the daily account statements',
                'rules' => 'nullable|in:both,real,demo',
                'options' => [
                    'both' => 'Both',
                    'real' => 'Real',
                    'demo' => 'Demo',
                ],
                'value' => 'both',
            ],
            [
                'type' => 'text',
                'data' => 'integer',
                'name' => 'daily_statement_batch_size',
                'label' => 'Batch Size',
                'description' => 'Number of accounts to process in each batch',
                'rules' => 'nullable|integer|min:1|max:500',
                'value' => '50',
            ],
            [
                'type' => 'text',
                'data' => 'integer',
                'name' => 'daily_statement_retry_attempts',
                'label' => 'Retry Attempts',
                'description' => 'The number of retry attempts for failed sends',
                'rules' => 'nullable|integer|min:0|max:10',
                'value' => '3',
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'daily_statement_period',
                'label' => 'Statement Period',
                'description' => 'The period for which the statement is generated',
                'rules' => 'nullable|in:previous_day,current_day',
                'options' => [
                    'previous_day' => 'Previous Day',
                    'current_day' => 'Current Day',
                ],
                'value' => 'previous_day',
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'daily_statement_include_pdf',
                'label' => 'Include PDF Attachment',
                'description' => 'Include the PDF attachment in the daily account statements',
                'rules' => 'nullable',
                'value' => 1,
            ],
            [
                'type' => 'checkbox',
                'data' => 'boolean',
                'name' => 'daily_statement_retry_failed',
                'label' => 'Retry Failed Sends',
                'description' => 'Retry the failed sends for the daily account statements',
                'rules' => 'nullable',
                'value' => 1,
            ],
        ],
    ],
];
