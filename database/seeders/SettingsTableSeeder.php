<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'name' => 'currency_symbol',
                'type' => 'string',
                'val' => '$',
            ],
            [
                'name' => 'site_title',
                'type' => 'string',
                'val' => 'Your Broker',
            ],
            [
                'name' => 'site_email',
                'type' => 'string',
                'val' => 'info@yourbroker.com',
            ],
            [
                'name' => 'copyright_text',
                'type' => 'string',
                'val' => 'All rights reserved © Your Broker 2024',
            ],
            [
                'name' => 'site_currency',
                'type' => 'string',
                'val' => 'USD',
            ],
            [
                'name' => 'login_bg',
                'type' => 'string',
                'val' => 'global/images/Etj83xxWsdizBDUZlxS1.jpg',
            ],
            [
                'name' => 'site_logo',
                'type' => 'string',
                'val' => 'global/images/MPnzkFJmwrc85XbglVWk.png',
            ],
            [
                'name' => 'site_favicon',
                'type' => 'string',
                'val' => 'global/images/7981JNwhf34DGuBbVZiv.png',
            ],
            [
                'name' => 'maintenance_mode',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'maintenance_title',
                'type' => 'string',
                'val' => 'Site Under Maintenance for Exciting New Updates!',
            ],
            [
                'name' => 'maintenance_text',
                'type' => 'string',
                'val' => 'We apologize for the inconvenience! Our site is currently undergoing a major update to bring you an enhanced experience with new features and improvements. We\'ll be back online shortly. Thank you for your patience!',
            ],
            [
                'name' => 'min_send',
                'type' => 'double',
                'val' => '10',
            ],
            [
                'name' => 'max_send',
                'type' => 'double',
                'val' => '100000',
            ],
            [
                'name' => 'send_charge_type',
                'type' => 'string',
                'val' => 'percentage',
            ],
            [
                'name' => 'send_charge',
                'type' => 'double',
                'val' => '10',
            ],
            [
                'name' => 'referral_commission',
                'type' => 'double',
                'val' => '21',
            ],
            [
                'name' => 'referral_bonus',
                'type' => 'double',
                'val' => '0',
            ],
            [
                'name' => 'signup_bonus',
                'type' => 'double',
                'val' => '0',
            ],
            [
                'name' => 'email_verification',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'kyc_verification',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'fa_verification',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'account_creation',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'user_deposit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'user_withdraw',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'sign_up_referral',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'referral_signup_bonus',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'investment_referral_bounty',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'deposit_referral_bounty',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'email_from_name',
                'type' => 'string',
                'val' => 'Brokeret',
            ],
            [
                'name' => 'email_from_address',
                'type' => 'string',
                'val' => 'no-reply@brokeret.com',
            ],
            [
                'name' => 'email_footer',
                'type' => 'string',
                'val' => '© 2024 - 2025 Your Broker',
            ],
            [
                'name' => 'mailing_driver',
                'type' => 'string',
                'val' => 'smtp',
            ],
            [
                'name' => 'mail_host',
                'type' => 'string',
                'val' => 'smtp.zeptomail.com',
            ],
            [
                'name' => 'mail_port',
                'type' => 'integer',
                'val' => '465',
            ],
            [
                'name' => 'mail_secure',
                'type' => 'string',
                'val' => 'tls',
            ],
            [
                'name' => 'mail_username',
                'type' => 'string',
                'val' => 'emailapikey',
            ],
            [
                'name' => 'mail_password',
                'type' => 'string',
                'val' => 'wSsVR612+hWlDKp+nGauL79syg4DAF30REh/0Vuhv3L6HvHE8cc7xE3HDQaiHPUaF246E2FBpe8pnBtV1zALh9R+w15TASiF9mqRe1U4J3x17qnvhDzDWm9YkBGJKYkLxQponGVoEs4l+g==',
            ],
            [
                'name' => 'site_animation',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'support_email',
                'type' => 'string',
                'val' => 'support@yourbroker.com',
            ],
            [
                'name' => 'wallet_exchange_charge_type',
                'type' => 'string',
                'val' => 'percentage',
            ],
            [
                'name' => 'wallet_exchange_charge',
                'type' => 'string',
                'val' => '0.05',
            ],
            [
                'name' => 'secret_key',
                'type' => 'string',
                'val' => 'secureAccess123',
            ],
            [
                'name' => 'transfer_status',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'back_to_top',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'referral_deposit_bonus',
                'type' => 'double',
                'val' => '2.4',
            ],
            [
                'name' => 'site_timezone',
                'type' => 'string',
                'val' => 'Etc/GMT+0',
            ],
            [
                'name' => 'debug_mode',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'profit_level',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'investment_level',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'deposit_level',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'site_referral',
                'type' => 'string',
                'val' => 'level',
            ],
            [
                'name' => 'site_admin_prefix',
                'type' => 'string',
                'val' => 'backoffice',
            ],
            [
                'name' => 'home_redirect',
                'type' => 'string',
                'val' => '/login',
            ],
            [
                'name' => 'wallet_exchange_day_limit',
                'type' => 'int',
                'val' => '1',
            ],
            [
                'name' => 'send_money_day_limit',
                'type' => 'int',
                'val' => '100',
            ],
            [
                'name' => 'withdraw_day_limit',
                'type' => 'int',
                'val' => '100000',
            ],
            [
                'name' => 'investment_cancellation_daily_limit',
                'type' => 'int',
                'val' => '7',
            ],
            [
                'name' => 'referral_code_limit',
                'type' => 'integer',
                'val' => '10',
            ],
            [
                'name' => 'site_currency_type',
                'type' => 'string',
                'val' => 'fiat',
            ],
            [
                'name' => 'gdpr_status',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'gdpr_text',
                'type' => 'string',
                'val' => 'Please allow us to collect data about how you use our website. We will use it to improve our website, make your browsing experience and our business decisions better. Learn more',
            ],
            [
                'name' => 'gdpr_button_label',
                'type' => 'string',
                'val' => 'Learn More',
            ],
            [
                'name' => 'gdpr_button_url',
                'type' => 'string',
                'val' => '/privacy-policy',
            ],
            [
                'name' => 'aml_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'aml_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'client_agreement_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'client_agreement_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'complaints_handling_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'complaints_handling_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'cookies_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'cookies_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'IB_partner_agreement_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'IB_partner_agreement_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'order_execution_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'order_execution_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'privacy_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'privacy_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'risk_disclosure_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'risk_disclosure_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'US_clients_policy_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'US_clients_policy_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'desktop_terminal_windows_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'desktop_terminal_windows_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'desktop_terminal_mac_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'desktop_terminal_mac_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'mobile_application_android_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'mobile_application_android_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'mobile_application_iOS_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'mobile_application_iOS_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'mobile_application_Android_APK_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'mobile_application_Android_APK_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'web_terminal_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'web_terminal_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'trust_pilot_review_link',
                'type' => 'string',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ],
            [
                'name' => 'trust_pilot_review_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'multi_ib_level',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'new_trading_accounts',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => '90_days_in_activity_trade_disable',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'delete_archived_accounts',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'automatic_withdrawals',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'automatic_deposits',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'automatic_kyc',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'disable_trading',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'user_ranking',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'internal_min_send',
                'type' => 'double',
                'val' => '5',
            ],
            [
                'name' => 'internal_max_send',
                'type' => 'double',
                'val' => '100000',
            ],
            [
                'name' => 'internal_send_charge_type',
                'type' => 'string',
                'val' => 'percentage',
            ],
            [
                'name' => 'internal_send_charge',
                'type' => 'double',
                'val' => '1',
            ],
            [
                'name' => 'link_thumbnail',
                'type' => 'string',
                'val' => 'global/images/L124WJSf3rezdQsieyQi.png',
            ],
            [
                'name' => 'site_logo_light',
                'type' => 'string',
                'val' => 'global/images/hUTh00ov11TR31i6jIyb.png',
            ],
            [
                'name' => 'forex_api_url',
                'type' => 'string',
                'val' => 'http://11.222.332.444:1234',
            ],
            [
                'name' => 'forex_api_key',
                'type' => 'string',
                'val' => 'PVTfAIPjQZ4Ggansfasd',
            ],
            [
                'name' => 'mt5_api_url_real1',
                'type' => 'string',
                'val' => 'http://92.204.253.130:4001',
            ],
            [
                'name' => 'mt5_api_key_real',
                'type' => 'string',
                'val' => 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM',
            ],
            [
                'name' => 'mt5_api_url_real',
                'type' => 'string',
                'val' => 'http://api.brokeret.com:4045',
            ],
            [
                'name' => 'mt5_api_url_demo',
                'type' => 'string',
                'val' => 'http://api.brokeret.com:4045',
            ],
            [
                'name' => 'mt5_api_key_demo',
                'type' => 'string',
                'val' => 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM',
            ],
            [
                'name' => 'customer_support_link',
                'type' => 'string',
                'val' => '',
            ],
            [
                'name' => 'customer_support_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'copy_trading_follower_access',
                'type' => 'string',
                'val' => 'https://copytrader.mbfx.co/portal/registration/subscription',
            ],
            [
                'name' => 'copy_trading_follower_access_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'copy_trading_provider_access',
                'type' => 'string',
                'val' => 'https://copytrader.mbfx.co/portal/registration/provider',
            ],
            [
                'name' => 'copy_trading_provider_access_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'copy_trading_ratings',
                'type' => 'string',
                'val' => 'https://brokeree.mbfx.co/widgets/ratings',
            ],
            [
                'name' => 'copy_trading_ratings_show',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'withdraw_deduction',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'copy_trading',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'demo_server_enable',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'primary_color',
                'type' => 'string',
                'val' => '#000000',
            ],
            [
                'name' => 'secondary_color',
                'type' => 'string',
                'val' => '#c9c9c9',
            ],
            [
                'name' => 'sidebar_active_menu_bg',
                'type' => 'string',
                'val' => '#0f172a',
            ],
            [
                'name' => 'sidebar_active_menu_color',
                'type' => 'string',
                'val' => '#0f172a',
            ],
            [
                'name' => 'active_menu_bg',
                'type' => 'string',
                'val' => '#e5e7eb',
            ],
            [
                'name' => 'active_menu_color',
                'type' => 'string',
                'val' => '#000000',
            ],
            [
                'name' => 'live_server',
                'type' => 'string',
                'val' => 'BluVoxMarkets-Server',
            ],
            [
                'name' => 'demo_server',
                'type' => 'string',
                'val' => 'MT5 Server1',
            ],
            [
                'name' => 'is_forex_group_range',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'database_host',
                'type' => 'string',
                'val' => '134.119.205.99',
            ],
            [
                'name' => 'database_port',
                'type' => 'integer',
                'val' => '3306',
            ],
            [
                'name' => 'database_name',
                'type' => 'string',
                'val' => 'bluvoxmarkets_mt5',
            ],
            [
                'name' => 'database_username',
                'type' => 'string',
                'val' => 'bluvoxmarkets_mt5demo',
            ],
            [
                'name' => 'database_password',
                'type' => 'string',
                'val' => 'T4h#hCR]8-n8',
            ],
            [
                'name' => 'disclaimer',
                'type' => 'string',
                'val' => 'This CRM demo is provided for informational purposes only. All data and functionality presented are for demonstration use and may not reflect actual performance, accuracy, or reliability. Brokeret disclaims any liability for actions taken based on the information displayed in this demo.',
            ],
            [
                'name' => 'email_disclaimer',
                'type' => 'string',
                'val' => 'Please note that this email is part of a demonstration from Brokeret\'s CRM platform. The contents are purely illustrative and should not be construed as financial advice or actual client communication.',
            ],
            [
                'name' => 'footer_content',
                'type' => 'string',
                'val' => '© 2024 - 2025 Your Broker',
            ],
            [
                'name' => 'risk_warning',
                'type' => 'string',
                'val' => 'Trading in forex and other financial instruments carries a high level of risk and may not be suitable for all investors. Demo results may not be reflective of actual market conditions. Please exercise caution.',
            ],
            [
                'name' => 'email_risk_warning',
                'type' => 'string',
                'val' => 'This email contains information on forex trading which carries high risk. This is a demo email and should not be considered actual financial advice. Always consult a professional before making trading decisions.',
            ],
            [
                'name' => 'webterminal_src_light',
                'type' => 'string',
                'val' => 'https://web.mbfx.co/terminal?utm_campaign=BanexClientOffice&utm_source=www.banexcapital.com&mode=connect&lang=en&theme-mode=0&theme=blueRed',
            ],
            [
                'name' => 'webterminal_src_dark',
                'type' => 'string',
                'val' => 'https://web.mbfx.co/terminal?utm_campaign=BanexClientOffice&utm_source=www.banexcapital.com&mode=connect&lang=en&theme-mode=1&theme=blueRed',
            ],
            [
                'name' => 'webterminal_width',
                'type' => 'string',
                'val' => '100',
            ],
            [
                'name' => 'webterminal_height',
                'type' => 'string',
                'val' => '100',
            ],
            [
                'name' => 'is_webterminal',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'active_trader_type',
                'type' => 'string',
                'val' => 'mt5',
            ],
            [
                'name' => 'x9_name',
                'type' => 'string',
                'val' => 'X9trader1',
            ],
            [
                'name' => 'x9_demo_server_enable',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'x9_network_address',
                'type' => 'string',
                'val' => 'https://shareafunds-5000.encrypted-gateway.com',
            ],
            [
                'name' => 'x9_API_access_key',
                'type' => 'string',
                'val' => 'CRegMvGeoX9O24nSHQ',
            ],
            [
                'name' => 'x9_status',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'default_transaction_method',
                'type' => 'string',
                'val' => 'global/images/RI5i7T35LbnSdoFhOOO5.jpg',
            ],
            [
                'name' => 'body_bg_color',
                'type' => 'string',
                'val' => '#f3f4f6',
            ],
            [
                'name' => 'base_color',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'sidebar_bg',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'sidebar_color',
                'type' => 'string',
                'val' => '#000000',
            ],
            [
                'name' => 'secondary_btn_bg',
                'type' => 'string',
                'val' => '#6c727f',
            ],
            [
                'name' => 'secondary_btn_color',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'primary_btn_bg',
                'type' => 'string',
                'val' => '#1f1e1e',
            ],
            [
                'name' => 'primary_btn_color',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'body_bg_color_dark',
                'type' => 'string',
                'val' => '#100e0e',
            ],
            [
                'name' => 'base_color_dark',
                'type' => 'string',
                'val' => '#1d1b1b',
            ],
            [
                'name' => 'sidebar_bg_dark',
                'type' => 'string',
                'val' => '#616161',
            ],
            [
                'name' => 'sidebar_color_dark',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'active_menu_bg_dark',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'active_menu_color_dark',
                'type' => 'string',
                'val' => '#000000',
            ],
            [
                'name' => 'secondary_btn_bg_dark',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'secondary_btn_color_dark',
                'type' => 'string',
                'val' => '#797b81',
            ],
            [
                'name' => 'primary_btn_bg_dark',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'primary_btn_color_dark',
                'type' => 'string',
                'val' => '#000000',
            ],
            [
                'name' => 'success_color',
                'type' => 'string',
                'val' => '#0fb60b',
            ],
            [
                'name' => 'warning_color',
                'type' => 'string',
                'val' => '#ffbb0d',
            ],
            [
                'name' => 'danger_color',
                'type' => 'string',
                'val' => '#ff0000',
            ],
            [
                'name' => 'font_family',
                'type' => 'string',
                'val' => 'Inter',
            ],
            [
                'name' => 'company_website',
                'type' => 'string',
                'val' => 'https://yourbroker.com',
            ],
            [
                'name' => 'company_phone',
                'type' => 'string',
                'val' => '+1-234-567-890',
            ],
            [
                'name' => 'registered_address',
                'type' => 'string',
                'val' => '123 Broker Street, New York, NY 10001, USA',
            ],
            [
                'name' => 'registered_number',
                'type' => 'string',
                'val' => '9876543210',
            ],
            [
                'name' => 'copy_trading_ratings_js',
                'type' => 'string',
                'val' => 'https://copytrader.mbfx.co/portal/registration/provider',
            ],
            [
                'name' => 'is_copy_trading',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'webterminal_status',
                'type' => 'string',
                'val' => '1',
            ],
            [
                'name' => 'session_expiry',
                'type' => 'string',
                'val' => '60',
            ],
            [
                'name' => 'active_data_sync_way',
                'type' => 'string',
                'val' => 'mt5',
            ],
            [
                'name' => 'header_bg',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'header_color',
                'type' => 'string',
                'val' => '#242424',
            ],
            [
                'name' => 'header_bg_dark',
                'type' => 'string',
                'val' => '#50e600',
            ],
            [
                'name' => 'header_color_dark',
                'type' => 'string',
                'val' => '#ffffff',
            ],
            [
                'name' => 'popup_image',
                'type' => 'string',
                'val' => 'global/images/4za4m5x62ZvLXcRWSQXV.png',
            ],
            [
                'name' => 'popup_status',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'popup_btn_text',
                'type' => 'string',
                'val' => 'Happy New Year',
            ],
            [
                'name' => 'popup_btn_link',
                'type' => 'string',
                'val' => 'https://brokeret.com',
            ],
            [
                'name' => 'external_min_send',
                'type' => 'double',
                'val' => '1',
            ],
            [
                'name' => 'external_max_send',
                'type' => 'double',
                'val' => '90000',
            ],
            [
                'name' => 'external_send_charge',
                'type' => 'double',
                'val' => '5',
            ],
            [
                'name' => 'external_send_charge_type',
                'type' => 'string',
                'val' => 'percentage',
            ],
            [
                'name' => 'external_send_daily_limit',
                'type' => 'int',
                'val' => '15',
            ],
            [
                'name' => 'is_external_transfer',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'is_external_transfer_auto_approve',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'is_external_transfer_purpose',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_name_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_phone_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_username_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_email_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_country_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'customer_dob_edit',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'is_whitelabel',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'pending_withdraw_limit',
                'type' => 'double',
                'val' => '3',
            ],
            [
                'name' => 'min_ib_wallet_withdraw_limit',
                'type' => 'double',
                'val' => '100',
            ],
            [
                'name' => 'withdraw_otp_expires',
                'type' => 'int',
                'val' => '5',
            ],
            [
                'name' => 'withdraw_otp',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'site_mobile_logo',
                'type' => 'string',
                'val' => 'global/images/d7P7MjUHhzwIBltn4zsm.png',
            ],
            [
                'name' => 'deposit_notification_tune',
                'type' => 'string',
                'val' => 'https://demo.brokeret.com/assets/global/tune/expert_notification.mp3',
            ],
            [
                'name' => 'default_notification_tune',
                'type' => 'string',
                'val' => 'https://demo.brokeret.com/assets/global/tune/knock_knock.mp3',
            ],
            [
                'name' => 'is_internal_transfer',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'internal_send_daily_limit',
                'type' => 'int',
                'val' => '50',
            ],
            [
                'name' => 'withdraw_notification_tune',
                'type' => 'string',
                'val' => 'https://demo.brokeret.com/assets/global/tune/sticky.mp3',
            ],
            [
                'name' => 'deposit_amount',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'withdraw_amount',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'internal_transfer_amount',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'external_transfer_amount',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'provider_logo_status',
                'type' => 'boolean',
                'val' => '0',
            ],
            [
                'name' => 'is_desktop_dashboard_quick_link',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'is_mobile_dashboard_quick_link',
                'type' => 'boolean',
                'val' => '1',
            ],
            [
                'name' => 'admin_2fa_enabled',
                'type' => 'boolean',
                'val' => '0',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['name' => $setting['name']],
                [
                    'type' => $setting['type'],
                    'val' => $setting['val'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
