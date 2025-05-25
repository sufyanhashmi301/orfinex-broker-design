<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'created_at' => '2022-08-24 15:01:01',
                'id' => 1,
                'name' => 'currency_symbol',
                'type' => 'string',
                'updated_at' => '2023-07-31 04:13:24',
                'val' => '$',
            ),
            1 => 
            array (
                'created_at' => '2022-08-24 15:01:01',
                'id' => 2,
                'name' => 'site_title',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:13:40',
                'val' => 'Your Broker',
            ),
            2 => 
            array (
                'created_at' => '2022-08-24 15:01:01',
                'id' => 3,
                'name' => 'site_email',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:13:40',
                'val' => 'info@yourbroker.com',
            ),
            3 => 
            array (
                'created_at' => '2022-08-24 15:01:01',
                'id' => 4,
                'name' => 'copyright_text',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:13:40',
                'val' => 'All rights reserved © Your Broker 2024',
            ),
            4 => 
            array (
                'created_at' => '2022-08-24 15:01:08',
                'id' => 5,
                'name' => 'site_currency',
                'type' => 'string',
                'updated_at' => '2023-08-13 20:38:25',
                'val' => 'USD',
            ),
            5 => 
            array (
                'created_at' => '2022-08-28 07:25:00',
                'id' => 6,
                'name' => 'login_bg',
                'type' => 'string',
                'updated_at' => '2024-06-13 23:06:12',
                'val' => 'global/images/Etj83xxWsdizBDUZlxS1.jpg',
            ),
            6 => 
            array (
                'created_at' => '2022-08-28 07:25:14',
                'id' => 7,
                'name' => 'site_logo',
                'type' => 'string',
                'updated_at' => '2024-12-17 17:22:30',
                'val' => 'global/images/MPnzkFJmwrc85XbglVWk.png',
            ),
            7 => 
            array (
                'created_at' => '2022-08-28 07:25:14',
                'id' => 8,
                'name' => 'site_favicon',
                'type' => 'string',
                'updated_at' => '2024-12-17 17:22:30',
                'val' => 'global/images/7981JNwhf34DGuBbVZiv.png',
            ),
            8 => 
            array (
                'created_at' => '2022-09-20 19:29:38',
                'id' => 9,
                'name' => 'maintenance_mode',
                'type' => 'boolean',
                'updated_at' => '2023-08-01 09:58:23',
                'val' => '0',
            ),
            9 => 
            array (
                'created_at' => '2022-09-20 19:29:38',
                'id' => 10,
                'name' => 'maintenance_title',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:18:03',
                'val' => 'Site Under Maintenance for Exciting New Updates!',
            ),
            10 => 
            array (
                'created_at' => '2022-09-20 19:29:38',
                'id' => 11,
                'name' => 'maintenance_text',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:18:03',
                'val' => 'We apologize for the inconvenience! Our site is currently undergoing a major update to bring you an enhanced experience with new features and improvements. We’ll be back online shortly. Thank you for your patience!',
            ),
            11 => 
            array (
                'created_at' => '2022-09-21 13:35:47',
                'id' => 12,
                'name' => 'min_send',
                'type' => 'double',
                'updated_at' => '2022-11-13 12:34:21',
                'val' => '10',
            ),
            12 => 
            array (
                'created_at' => '2022-09-21 13:35:47',
                'id' => 13,
                'name' => 'max_send',
                'type' => 'double',
                'updated_at' => '2024-01-26 16:45:18',
                'val' => '100000',
            ),
            13 => 
            array (
                'created_at' => '2022-09-21 13:35:47',
                'id' => 14,
                'name' => 'send_charge_type',
                'type' => 'string',
                'updated_at' => '2022-09-21 16:26:22',
                'val' => 'percentage',
            ),
            14 => 
            array (
                'created_at' => '2022-09-21 13:35:47',
                'id' => 15,
                'name' => 'send_charge',
                'type' => 'double',
                'updated_at' => '2024-05-18 20:52:53',
                'val' => '10',
            ),
            15 => 
            array (
                'created_at' => '2022-10-10 09:34:02',
                'id' => 16,
                'name' => 'referral_commission',
                'type' => 'double',
                'updated_at' => '2022-10-10 09:34:02',
                'val' => '21',
            ),
            16 => 
            array (
                'created_at' => '2022-10-10 19:17:10',
                'id' => 17,
                'name' => 'referral_bonus',
                'type' => 'double',
                'updated_at' => '2024-01-01 12:41:24',
                'val' => '0',
            ),
            17 => 
            array (
                'created_at' => '2022-10-10 19:17:10',
                'id' => 18,
                'name' => 'signup_bonus',
                'type' => 'double',
                'updated_at' => '2024-01-01 12:41:24',
                'val' => '0',
            ),
            18 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 19,
                'name' => 'email_verification',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            19 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 20,
                'name' => 'kyc_verification',
                'type' => 'boolean',
                'updated_at' => '2024-01-04 14:35:06',
                'val' => '1',
            ),
            20 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 21,
                'name' => 'fa_verification',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:48:56',
                'val' => '1',
            ),
            21 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 22,
                'name' => 'account_creation',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 13:23:06',
                'val' => '1',
            ),
            22 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 23,
                'name' => 'user_deposit',
                'type' => 'boolean',
                'updated_at' => '2022-10-12 10:14:39',
                'val' => '1',
            ),
            23 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 24,
                'name' => 'user_withdraw',
                'type' => 'boolean',
                'updated_at' => '2022-11-18 01:46:38',
                'val' => '1',
            ),
            24 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 25,
                'name' => 'sign_up_referral',
                'type' => 'boolean',
                'updated_at' => '2022-10-12 10:14:39',
                'val' => '1',
            ),
            25 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 26,
                'name' => 'referral_signup_bonus',
                'type' => 'boolean',
                'updated_at' => '2024-01-01 21:27:20',
                'val' => '0',
            ),
            26 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 27,
                'name' => 'investment_referral_bounty',
                'type' => 'boolean',
                'updated_at' => '2024-01-01 21:27:20',
                'val' => '0',
            ),
            27 => 
            array (
                'created_at' => '2022-10-12 10:14:39',
                'id' => 28,
                'name' => 'deposit_referral_bounty',
                'type' => 'boolean',
                'updated_at' => '2024-01-01 21:27:20',
                'val' => '0',
            ),
            28 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 29,
                'name' => 'email_from_name',
                'type' => 'string',
                'updated_at' => '2024-06-13 23:08:07',
                'val' => 'Brokeret',
            ),
            29 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 30,
                'name' => 'email_from_address',
                'type' => 'string',
                'updated_at' => '2024-06-13 23:08:07',
                'val' => 'no-reply@brokeret.com',
            ),
            30 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 31,
                'name' => 'email_footer',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:19:36',
                'val' => '© 2024 - 2025 Your Broker',
            ),
            31 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 32,
                'name' => 'mailing_driver',
                'type' => 'string',
                'updated_at' => '2022-10-29 19:50:36',
                'val' => 'smtp',
            ),
            32 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 33,
                'name' => 'mail_host',
                'type' => 'string',
                'updated_at' => '2024-12-31 14:41:19',
                'val' => 'smtp.zeptomail.com',
            ),
            33 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 34,
                'name' => 'mail_port',
                'type' => 'integer',
                'updated_at' => '2023-10-19 07:23:19',
                'val' => '465',
            ),
            34 => 
            array (
                'created_at' => '2022-10-29 19:11:51',
                'id' => 35,
                'name' => 'mail_secure',
                'type' => 'string',
                'updated_at' => '2024-01-01 12:38:43',
                'val' => 'tls',
            ),
            35 => 
            array (
                'created_at' => '2022-10-29 20:05:23',
                'id' => 36,
                'name' => 'mail_username',
                'type' => 'string',
                'updated_at' => '2024-12-31 14:41:19',
                'val' => 'emailapikey',
            ),
            36 => 
            array (
                'created_at' => '2022-10-29 20:05:23',
                'id' => 37,
                'name' => 'mail_password',
                'type' => 'string',
                'updated_at' => '2024-12-31 14:41:19',
                'val' => 'wSsVR612+hWlDKp+nGauL79syg4DAF30REh/0Vuhv3L6HvHE8cc7xE3HDQaiHPUaF246E2FBpe8pnBtV1zALh9R+w15TASiF9mqRe1U4J3x17qnvhDzDWm9YkBGJKYkLxQponGVoEs4l+g==',
            ),
            37 => 
            array (
                'created_at' => '2022-11-06 15:24:22',
                'id' => 38,
                'name' => 'site_animation',
                'type' => 'boolean',
                'updated_at' => '2024-07-30 12:56:04',
                'val' => '0',
            ),
            38 => 
            array (
                'created_at' => '2022-11-07 13:29:03',
                'id' => 39,
                'name' => 'support_email',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:13:40',
                'val' => 'support@yourbroker.com',
            ),
            39 => 
            array (
                'created_at' => '2022-11-11 10:31:21',
                'id' => 40,
                'name' => 'wallet_exchange_charge_type',
                'type' => 'string',
                'updated_at' => '2022-11-11 10:31:21',
                'val' => 'percentage',
            ),
            40 => 
            array (
                'created_at' => '2022-11-11 10:31:21',
                'id' => 41,
                'name' => 'wallet_exchange_charge',
                'type' => 'string',
                'updated_at' => '2022-11-11 10:31:21',
                'val' => '0.05',
            ),
            41 => 
            array (
                'created_at' => '2022-11-14 06:32:48',
                'id' => 42,
                'name' => 'secret_key',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:18:03',
                'val' => 'secureAccess123',
            ),
            42 => 
            array (
                'created_at' => '2022-11-16 13:16:07',
                'id' => 43,
                'name' => 'transfer_status',
                'type' => 'boolean',
                'updated_at' => '2022-11-16 13:17:08',
                'val' => '1',
            ),
            43 => 
            array (
                'created_at' => '2022-11-16 13:16:07',
                'id' => 44,
                'name' => 'back_to_top',
                'type' => 'boolean',
                'updated_at' => '2023-09-03 22:51:42',
                'val' => '0',
            ),
            44 => 
            array (
                'created_at' => '2023-02-04 08:39:35',
                'id' => 45,
                'name' => 'referral_deposit_bonus',
                'type' => 'double',
                'updated_at' => '2023-02-04 08:39:35',
                'val' => '2.4',
            ),
            45 => 
            array (
                'created_at' => '2023-02-15 13:26:05',
                'id' => 46,
                'name' => 'site_timezone',
                'type' => 'string',
                'updated_at' => '2023-10-19 07:24:32',
                'val' => 'Etc/GMT+0',
            ),
            46 => 
            array (
                'created_at' => '2023-02-15 19:07:46',
                'id' => 47,
                'name' => 'debug_mode',
                'type' => 'boolean',
                'updated_at' => '2025-05-21 20:42:16',
                'val' => '0',
            ),
            47 => 
            array (
                'created_at' => NULL,
                'id' => 48,
                'name' => 'profit_level',
                'type' => 'boolean',
                'updated_at' => '2024-02-01 16:04:21',
                'val' => '0',
            ),
            48 => 
            array (
                'created_at' => NULL,
                'id' => 49,
                'name' => 'investment_level',
                'type' => 'boolean',
                'updated_at' => '2023-03-28 11:35:55',
                'val' => '1',
            ),
            49 => 
            array (
                'created_at' => NULL,
                'id' => 50,
                'name' => 'deposit_level',
                'type' => 'boolean',
                'updated_at' => '2024-02-01 16:04:20',
                'val' => '0',
            ),
            50 => 
            array (
                'created_at' => '2023-03-25 06:14:29',
                'id' => 51,
                'name' => 'site_referral',
                'type' => 'string',
                'updated_at' => '2023-07-31 04:13:24',
                'val' => 'level',
            ),
            51 => 
            array (
                'created_at' => '2023-05-20 16:03:46',
                'id' => 52,
                'name' => 'site_admin_prefix',
                'type' => 'string',
                'updated_at' => '2024-12-31 14:49:20',
                'val' => 'backoffice',
            ),
            52 => 
            array (
                'created_at' => '2023-05-21 13:16:02',
                'id' => 53,
                'name' => 'home_redirect',
                'type' => 'string',
                'updated_at' => '2023-12-05 18:15:36',
                'val' => '/login',
            ),
            53 => 
            array (
                'created_at' => '2023-05-24 16:07:21',
                'id' => 54,
                'name' => 'wallet_exchange_day_limit',
                'type' => 'int',
                'updated_at' => '2023-07-19 22:58:06',
                'val' => '1',
            ),
            54 => 
            array (
                'created_at' => '2023-05-24 16:07:21',
                'id' => 55,
                'name' => 'send_money_day_limit',
                'type' => 'int',
                'updated_at' => '2024-01-26 17:05:59',
                'val' => '100',
            ),
            55 => 
            array (
                'created_at' => '2023-05-24 16:07:21',
                'id' => 56,
                'name' => 'withdraw_day_limit',
                'type' => 'int',
                'updated_at' => '2024-01-26 17:05:59',
                'val' => '100000',
            ),
            56 => 
            array (
                'created_at' => '2023-05-24 16:15:41',
                'id' => 57,
                'name' => 'investment_cancellation_daily_limit',
                'type' => 'int',
                'updated_at' => '2023-05-24 16:15:41',
                'val' => '7',
            ),
            57 => 
            array (
                'created_at' => '2023-06-12 01:56:07',
                'id' => 58,
                'name' => 'referral_code_limit',
                'type' => 'integer',
                'updated_at' => '2023-06-12 01:56:07',
                'val' => '10',
            ),
            58 => 
            array (
                'created_at' => '2023-07-20 17:18:23',
                'id' => 59,
                'name' => 'site_currency_type',
                'type' => 'string',
                'updated_at' => '2023-08-13 20:38:25',
                'val' => 'fiat',
            ),
            59 => 
            array (
                'created_at' => '2024-01-01 21:22:10',
                'id' => 60,
                'name' => 'gdpr_status',
                'type' => 'boolean',
                'updated_at' => '2024-01-01 21:22:10',
                'val' => '1',
            ),
            60 => 
            array (
                'created_at' => '2024-01-01 21:22:10',
                'id' => 61,
                'name' => 'gdpr_text',
                'type' => 'string',
                'updated_at' => '2024-01-01 21:22:10',
                'val' => 'Please allow us to collect data about how you use our website. We will use it to improve our website, make your browsing experience and our business decisions better. Learn more',
            ),
            61 => 
            array (
                'created_at' => '2024-01-01 21:22:10',
                'id' => 62,
                'name' => 'gdpr_button_label',
                'type' => 'string',
                'updated_at' => '2024-01-01 21:22:10',
                'val' => 'Learn More',
            ),
            62 => 
            array (
                'created_at' => '2024-01-01 21:22:10',
                'id' => 63,
                'name' => 'gdpr_button_url',
                'type' => 'string',
                'updated_at' => '2024-01-01 21:22:10',
                'val' => '/privacy-policy',
            ),
            63 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 64,
                'name' => 'aml_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            64 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 65,
                'name' => 'aml_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:28:09',
                'val' => '1',
            ),
            65 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 66,
                'name' => 'client_agreement_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            66 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 67,
                'name' => 'client_agreement_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:43:14',
                'val' => '1',
            ),
            67 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 68,
                'name' => 'complaints_handling_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            68 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 69,
                'name' => 'complaints_handling_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:44:35',
                'val' => '1',
            ),
            69 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 70,
                'name' => 'cookies_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            70 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 71,
                'name' => 'cookies_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:44:35',
                'val' => '1',
            ),
            71 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 72,
                'name' => 'IB_partner_agreement_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            72 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 73,
                'name' => 'IB_partner_agreement_show',
                'type' => 'boolean',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => '1',
            ),
            73 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 74,
                'name' => 'order_execution_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            74 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 75,
                'name' => 'order_execution_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:45:29',
                'val' => '1',
            ),
            75 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 76,
                'name' => 'privacy_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            76 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 77,
                'name' => 'privacy_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:45:52',
                'val' => '1',
            ),
            77 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 78,
                'name' => 'risk_disclosure_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            78 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 79,
                'name' => 'risk_disclosure_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:46:11',
                'val' => '1',
            ),
            79 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 80,
                'name' => 'US_clients_policy_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:27',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            80 => 
            array (
                'created_at' => '2024-01-29 12:28:09',
                'id' => 81,
                'name' => 'US_clients_policy_show',
                'type' => 'boolean',
                'updated_at' => '2024-01-29 12:46:54',
                'val' => '1',
            ),
            81 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 82,
                'name' => 'desktop_terminal_windows_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            82 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 83,
                'name' => 'desktop_terminal_windows_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            83 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 84,
                'name' => 'desktop_terminal_mac_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            84 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 85,
                'name' => 'desktop_terminal_mac_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            85 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 86,
                'name' => 'mobile_application_android_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            86 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 87,
                'name' => 'mobile_application_android_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            87 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 88,
                'name' => 'mobile_application_iOS_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            88 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 89,
                'name' => 'mobile_application_iOS_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            89 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 90,
                'name' => 'mobile_application_Android_APK_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            90 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 91,
                'name' => 'mobile_application_Android_APK_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            91 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 92,
                'name' => 'web_terminal_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            92 => 
            array (
                'created_at' => '2024-02-06 10:36:52',
                'id' => 93,
                'name' => 'web_terminal_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 10:36:52',
                'val' => '1',
            ),
            93 => 
            array (
                'created_at' => '2024-02-06 15:24:08',
                'id' => 94,
                'name' => 'trust_pilot_review_link',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:11:47',
                'val' => 'https://cdn.brokeret.com/doc/example.pdf',
            ),
            94 => 
            array (
                'created_at' => '2024-02-06 15:24:08',
                'id' => 95,
                'name' => 'trust_pilot_review_show',
                'type' => 'boolean',
                'updated_at' => '2024-02-06 15:24:08',
                'val' => '1',
            ),
            95 => 
            array (
                'created_at' => '2024-02-12 11:56:28',
                'id' => 96,
                'name' => 'multi_ib_level',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 11:57:32',
                'val' => '1',
            ),
            96 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 97,
                'name' => 'new_trading_accounts',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            97 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 98,
                'name' => '90_days_in_activity_trade_disable',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            98 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 99,
                'name' => 'delete_archived_accounts',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            99 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 100,
                'name' => 'automatic_withdrawals',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            100 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 101,
                'name' => 'automatic_deposits',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            101 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 102,
                'name' => 'automatic_kyc',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            102 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 103,
                'name' => 'disable_trading',
                'type' => 'boolean',
                'updated_at' => '2024-02-12 12:07:35',
                'val' => '0',
            ),
            103 => 
            array (
                'created_at' => '2024-02-12 12:07:35',
                'id' => 104,
                'name' => 'user_ranking',
                'type' => 'boolean',
                'updated_at' => '2024-02-16 13:53:30',
                'val' => '0',
            ),
            104 => 
            array (
                'created_at' => '2024-02-23 21:33:07',
                'id' => 105,
                'name' => 'internal_min_send',
                'type' => 'double',
                'updated_at' => '2024-02-23 21:34:18',
                'val' => '5',
            ),
            105 => 
            array (
                'created_at' => '2024-02-23 21:33:07',
                'id' => 106,
                'name' => 'internal_max_send',
                'type' => 'double',
                'updated_at' => '2024-02-23 21:34:18',
                'val' => '100000',
            ),
            106 => 
            array (
                'created_at' => '2024-02-23 21:33:07',
                'id' => 107,
                'name' => 'internal_send_charge_type',
                'type' => 'string',
                'updated_at' => '2024-02-23 21:33:31',
                'val' => 'percentage',
            ),
            107 => 
            array (
                'created_at' => '2024-02-23 21:33:07',
                'id' => 108,
                'name' => 'internal_send_charge',
                'type' => 'double',
                'updated_at' => '2025-05-14 18:29:18',
                'val' => '1',
            ),
            108 => 
            array (
                'created_at' => '2024-06-12 11:20:50',
                'id' => 109,
                'name' => 'link_thumbnail',
                'type' => 'string',
                'updated_at' => '2024-06-12 11:20:50',
                'val' => 'global/images/L124WJSf3rezdQsieyQi.png',
            ),
            109 => 
            array (
                'created_at' => '2024-06-13 23:06:12',
                'id' => 110,
                'name' => 'site_logo_light',
                'type' => 'string',
                'updated_at' => '2024-12-17 17:22:30',
                'val' => 'global/images/hUTh00ov11TR31i6jIyb.png',
            ),
            110 => 
            array (
                'created_at' => '2024-06-20 15:13:48',
                'id' => 111,
                'name' => 'forex_api_url',
                'type' => 'string',
                'updated_at' => '2024-06-20 15:13:59',
                'val' => 'http://11.222.332.444:1234',
            ),
            111 => 
            array (
                'created_at' => '2024-06-20 15:13:48',
                'id' => 112,
                'name' => 'forex_api_key',
                'type' => 'string',
                'updated_at' => '2024-06-20 15:13:59',
                'val' => 'PVTfAIPjQZ4Ggansfasd',
            ),
            112 => 
            array (
                'created_at' => '2024-06-20 18:26:37',
                'id' => 113,
                'name' => 'mt5_api_url_real1',
                'type' => 'string',
                'updated_at' => '2024-06-20 19:25:40',
                'val' => 'http://92.204.253.130:4001',
            ),
            113 => 
            array (
                'created_at' => '2024-06-20 18:26:37',
                'id' => 114,
                'name' => 'mt5_api_key_real',
                'type' => 'string',
                'updated_at' => '2024-06-20 19:25:40',
                'val' => 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM',
            ),
            114 => 
            array (
                'created_at' => '2024-06-20 20:54:38',
                'id' => 117,
                'name' => 'mt5_api_url_real',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:08:49',
                'val' => 'http://api.brokeret.com:4045',
            ),
            115 => 
            array (
                'created_at' => '2024-06-20 20:54:39',
                'id' => 118,
                'name' => 'mt5_api_url_demo',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:08:49',
                'val' => 'http://api.brokeret.com:4045',
            ),
            116 => 
            array (
                'created_at' => '2024-06-20 20:54:39',
                'id' => 119,
                'name' => 'mt5_api_key_demo',
                'type' => 'string',
                'updated_at' => '2024-06-21 01:16:43',
                'val' => 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM',
            ),
            117 => 
            array (
                'created_at' => '2024-07-08 13:54:02',
                'id' => 120,
                'name' => 'customer_support_link',
                'type' => 'string',
                'updated_at' => '2024-07-08 13:54:02',
                'val' => '',
            ),
            118 => 
            array (
                'created_at' => '2024-07-08 13:54:02',
                'id' => 121,
                'name' => 'customer_support_show',
                'type' => 'boolean',
                'updated_at' => '2024-07-08 13:54:02',
                'val' => '1',
            ),
            119 => 
            array (
                'created_at' => '2024-07-08 13:54:02',
                'id' => 122,
                'name' => 'copy_trading_follower_access',
                'type' => 'string',
                'updated_at' => '2024-07-08 13:54:02',
                'val' => 'https://copytrader.mbfx.co/portal/registration/subscription',
            ),
            120 => 
            array (
                'created_at' => '2024-07-08 13:54:02',
                'id' => 123,
                'name' => 'copy_trading_follower_access_show',
                'type' => 'boolean',
                'updated_at' => '2024-07-08 13:54:02',
                'val' => '1',
            ),
            121 => 
            array (
                'created_at' => '2024-07-08 13:54:03',
                'id' => 124,
                'name' => 'copy_trading_provider_access',
                'type' => 'string',
                'updated_at' => '2024-07-08 13:54:03',
                'val' => 'https://copytrader.mbfx.co/portal/registration/provider',
            ),
            122 => 
            array (
                'created_at' => '2024-07-08 13:54:03',
                'id' => 125,
                'name' => 'copy_trading_provider_access_show',
                'type' => 'boolean',
                'updated_at' => '2024-07-08 13:54:03',
                'val' => '1',
            ),
            123 => 
            array (
                'created_at' => '2024-07-08 13:54:03',
                'id' => 126,
                'name' => 'copy_trading_ratings',
                'type' => 'string',
                'updated_at' => '2024-07-08 13:56:25',
                'val' => 'https://brokeree.mbfx.co/widgets/ratings',
            ),
            124 => 
            array (
                'created_at' => '2024-07-08 13:54:03',
                'id' => 127,
                'name' => 'copy_trading_ratings_show',
                'type' => 'boolean',
                'updated_at' => '2024-07-08 13:54:03',
                'val' => '1',
            ),
            125 => 
            array (
                'created_at' => '2024-07-12 22:37:14',
                'id' => 128,
                'name' => 'withdraw_deduction',
                'type' => 'boolean',
                'updated_at' => '2024-09-21 15:22:24',
                'val' => '1',
            ),
            126 => 
            array (
                'created_at' => '2024-07-13 00:01:24',
                'id' => 129,
                'name' => 'copy_trading',
                'type' => 'boolean',
                'updated_at' => '2024-08-12 09:59:19',
                'val' => '1',
            ),
            127 => 
            array (
                'created_at' => '2024-07-16 20:37:02',
                'id' => 130,
                'name' => 'demo_server_enable',
                'type' => 'boolean',
                'updated_at' => '2025-05-14 11:08:49',
                'val' => '1',
            ),
            128 => 
            array (
                'created_at' => '2024-07-30 12:19:52',
                'id' => 131,
                'name' => 'primary_color',
                'type' => 'string',
                'updated_at' => '2025-02-19 16:43:45',
                'val' => '#000000',
            ),
            129 => 
            array (
                'created_at' => '2024-07-30 12:19:52',
                'id' => 132,
                'name' => 'secondary_color',
                'type' => 'string',
                'updated_at' => '2024-10-18 19:29:09',
                'val' => '#c9c9c9',
            ),
            130 => 
            array (
                'created_at' => '2024-07-30 12:19:53',
                'id' => 133,
                'name' => 'sidebar_active_menu_bg',
                'type' => 'string',
                'updated_at' => '2024-07-30 12:19:53',
                'val' => '#0f172a',
            ),
            131 => 
            array (
                'created_at' => '2024-07-30 12:19:53',
                'id' => 134,
                'name' => 'sidebar_active_menu_color',
                'type' => 'string',
                'updated_at' => '2024-07-30 12:19:53',
                'val' => '#0f172a',
            ),
            132 => 
            array (
                'created_at' => '2024-08-01 18:14:39',
                'id' => 135,
                'name' => 'active_menu_bg',
                'type' => 'string',
                'updated_at' => '2025-03-19 19:38:04',
                'val' => '#e5e7eb',
            ),
            133 => 
            array (
                'created_at' => '2024-08-01 18:14:39',
                'id' => 136,
                'name' => 'active_menu_color',
                'type' => 'string',
                'updated_at' => '2025-02-25 17:49:00',
                'val' => '#000000',
            ),
            134 => 
            array (
                'created_at' => '2024-08-11 18:28:09',
                'id' => 137,
                'name' => 'live_server',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:08:50',
                'val' => 'BluVoxMarkets-Server',
            ),
            135 => 
            array (
                'created_at' => '2024-08-11 18:28:09',
                'id' => 138,
                'name' => 'demo_server',
                'type' => 'string',
                'updated_at' => '2024-08-11 18:28:09',
                'val' => 'MT5 Server1',
            ),
            136 => 
            array (
                'created_at' => '2024-08-19 12:44:02',
                'id' => 139,
                'name' => 'is_forex_group_range',
                'type' => 'boolean',
                'updated_at' => '2024-08-19 12:44:02',
                'val' => '0',
            ),
            137 => 
            array (
                'created_at' => '2024-08-29 17:18:01',
                'id' => 140,
                'name' => 'database_host',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:07:27',
                'val' => '134.119.205.99',
            ),
            138 => 
            array (
                'created_at' => '2024-08-29 17:18:01',
                'id' => 141,
                'name' => 'database_port',
                'type' => 'integer',
                'updated_at' => '2024-08-29 17:18:01',
                'val' => '3306',
            ),
            139 => 
            array (
                'created_at' => '2024-08-29 17:18:01',
                'id' => 142,
                'name' => 'database_name',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:07:27',
                'val' => 'bluvoxmarkets_mt5',
            ),
            140 => 
            array (
                'created_at' => '2024-08-29 17:18:01',
                'id' => 143,
                'name' => 'database_username',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:07:27',
                'val' => 'bluvoxmarkets_mt5demo',
            ),
            141 => 
            array (
                'created_at' => '2024-08-29 17:18:01',
                'id' => 144,
                'name' => 'database_password',
                'type' => 'string',
                'updated_at' => '2025-05-14 11:07:27',
                'val' => 'T4h#hCR]8-n8',
            ),
            142 => 
            array (
                'created_at' => '2024-08-29 20:22:46',
                'id' => 145,
                'name' => 'disclaimer',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:16:30',
                'val' => 'This CRM demo is provided for informational purposes only. All data and functionality presented are for demonstration use and may not reflect actual performance, accuracy, or reliability. Brokeret disclaims any liability for actions taken based on the information displayed in this demo.',
            ),
            143 => 
            array (
                'created_at' => '2024-08-29 20:22:46',
                'id' => 146,
                'name' => 'email_disclaimer',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:16:30',
                'val' => 'Please note that this email is part of a demonstration from Brokeret’s CRM platform. The contents are purely illustrative and should not be construed as financial advice or actual client communication.',
            ),
            144 => 
            array (
                'created_at' => '2024-08-29 20:22:46',
                'id' => 147,
                'name' => 'footer_content',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:19:36',
                'val' => '© 2024 - 2025 Your Broker',
            ),
            145 => 
            array (
                'created_at' => '2024-08-29 20:22:46',
                'id' => 148,
                'name' => 'risk_warning',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:16:30',
                'val' => 'Trading in forex and other financial instruments carries a high level of risk and may not be suitable for all investors. Demo results may not be reflective of actual market conditions. Please exercise caution.',
            ),
            146 => 
            array (
                'created_at' => '2024-08-29 20:22:46',
                'id' => 149,
                'name' => 'email_risk_warning',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:16:30',
                'val' => 'This email contains information on forex trading which carries high risk. This is a demo email and should not be considered actual financial advice. Always consult a professional before making trading decisions.',
            ),
            147 => 
            array (
                'created_at' => '2024-09-07 10:34:41',
                'id' => 150,
                'name' => 'webterminal_src_light',
                'type' => 'string',
                'updated_at' => '2024-11-18 22:20:09',
                'val' => 'https://web.mbfx.co/terminal?utm_campaign=BanexClientOffice&utm_source=www.banexcapital.com&mode=connect&lang=en&theme-mode=0&theme=blueRed',
            ),
            148 => 
            array (
                'created_at' => '2024-09-07 10:34:41',
                'id' => 151,
                'name' => 'webterminal_src_dark',
                'type' => 'string',
                'updated_at' => '2024-11-18 22:20:09',
                'val' => 'https://web.mbfx.co/terminal?utm_campaign=BanexClientOffice&utm_source=www.banexcapital.com&mode=connect&lang=en&theme-mode=1&theme=blueRed',
            ),
            149 => 
            array (
                'created_at' => '2024-09-07 10:34:41',
                'id' => 152,
                'name' => 'webterminal_width',
                'type' => 'string',
                'updated_at' => '2024-10-21 23:19:32',
                'val' => '100',
            ),
            150 => 
            array (
                'created_at' => '2024-09-07 10:34:41',
                'id' => 153,
                'name' => 'webterminal_height',
                'type' => 'string',
                'updated_at' => '2024-10-21 23:19:32',
                'val' => '100',
            ),
            151 => 
            array (
                'created_at' => '2024-09-07 10:34:41',
                'id' => 154,
                'name' => 'is_webterminal',
                'type' => 'boolean',
                'updated_at' => '2024-09-07 10:34:41',
                'val' => '1',
            ),
            152 => 
            array (
                'created_at' => '2024-10-03 10:03:29',
                'id' => 155,
                'name' => 'active_trader_type',
                'type' => 'string',
                'updated_at' => '2024-11-27 17:48:25',
                'val' => 'mt5',
            ),
            153 => 
            array (
                'created_at' => '2024-10-03 10:03:53',
                'id' => 156,
                'name' => 'x9_name',
                'type' => 'string',
                'updated_at' => '2024-10-03 10:03:53',
                'val' => 'X9trader1',
            ),
            154 => 
            array (
                'created_at' => '2024-10-03 10:03:53',
                'id' => 157,
                'name' => 'x9_demo_server_enable',
                'type' => 'boolean',
                'updated_at' => '2024-10-03 10:03:53',
                'val' => '0',
            ),
            155 => 
            array (
                'created_at' => '2024-10-03 10:03:53',
                'id' => 158,
                'name' => 'x9_network_address',
                'type' => 'string',
                'updated_at' => '2024-10-13 19:27:11',
                'val' => 'https://shareafunds-5000.encrypted-gateway.com',
            ),
            156 => 
            array (
                'created_at' => '2024-10-03 10:03:53',
                'id' => 159,
                'name' => 'x9_API_access_key',
                'type' => 'string',
                'updated_at' => '2024-10-13 19:27:11',
                'val' => 'CRegMvGeoX9O24nSHQ',
            ),
            157 => 
            array (
                'created_at' => '2024-10-03 10:03:53',
                'id' => 160,
                'name' => 'x9_status',
                'type' => 'boolean',
                'updated_at' => '2024-10-03 10:03:53',
                'val' => '1',
            ),
            158 => 
            array (
                'created_at' => '2024-10-08 20:11:22',
                'id' => 161,
                'name' => 'default_transaction_method',
                'type' => 'string',
                'updated_at' => '2024-10-08 20:11:22',
                'val' => 'global/images/RI5i7T35LbnSdoFhOOO5.jpg',
            ),
            159 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 162,
                'name' => 'body_bg_color',
                'type' => 'string',
                'updated_at' => '2025-03-19 19:38:04',
                'val' => '#f3f4f6',
            ),
            160 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 163,
                'name' => 'base_color',
                'type' => 'string',
                'updated_at' => '2024-10-11 07:29:28',
                'val' => '#ffffff',
            ),
            161 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 164,
                'name' => 'sidebar_bg',
                'type' => 'string',
                'updated_at' => '2025-02-25 17:49:00',
                'val' => '#ffffff',
            ),
            162 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 165,
                'name' => 'sidebar_color',
                'type' => 'string',
                'updated_at' => '2025-02-25 17:49:00',
                'val' => '#000000',
            ),
            163 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 166,
                'name' => 'secondary_btn_bg',
                'type' => 'string',
                'updated_at' => '2025-03-19 19:39:38',
                'val' => '#6c727f',
            ),
            164 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 167,
                'name' => 'secondary_btn_color',
                'type' => 'string',
                'updated_at' => '2024-10-18 19:27:57',
                'val' => '#ffffff',
            ),
            165 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 168,
                'name' => 'primary_btn_bg',
                'type' => 'string',
                'updated_at' => '2025-03-19 19:41:11',
                'val' => '#1f1e1e',
            ),
            166 => 
            array (
                'created_at' => '2024-10-11 07:29:28',
                'id' => 169,
                'name' => 'primary_btn_color',
                'type' => 'string',
                'updated_at' => '2024-10-11 07:29:28',
                'val' => '#ffffff',
            ),
            167 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 170,
                'name' => 'body_bg_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:18:30',
                'val' => '#100e0e',
            ),
            168 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 171,
                'name' => 'base_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:19:13',
                'val' => '#1d1b1b',
            ),
            169 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 172,
                'name' => 'sidebar_bg_dark',
                'type' => 'string',
                'updated_at' => '2025-01-11 14:43:48',
                'val' => '#616161',
            ),
            170 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 173,
                'name' => 'sidebar_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-11 07:29:44',
                'val' => '#ffffff',
            ),
            171 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 174,
                'name' => 'active_menu_bg_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:16:46',
                'val' => '#ffffff',
            ),
            172 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 175,
                'name' => 'active_menu_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:16:46',
                'val' => '#000000',
            ),
            173 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 176,
                'name' => 'secondary_btn_bg_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:14:22',
                'val' => '#ffffff',
            ),
            174 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 177,
                'name' => 'secondary_btn_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:14:11',
                'val' => '#797b81',
            ),
            175 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 178,
                'name' => 'primary_btn_bg_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:15:03',
                'val' => '#ffffff',
            ),
            176 => 
            array (
                'created_at' => '2024-10-11 07:29:44',
                'id' => 179,
                'name' => 'primary_btn_color_dark',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:15:03',
                'val' => '#000000',
            ),
            177 => 
            array (
                'created_at' => '2024-10-18 18:11:40',
                'id' => 180,
                'name' => 'success_color',
                'type' => 'string',
                'updated_at' => '2024-10-18 18:11:40',
                'val' => '#0fb60b',
            ),
            178 => 
            array (
                'created_at' => '2024-10-18 18:11:40',
                'id' => 181,
                'name' => 'warning_color',
                'type' => 'string',
                'updated_at' => '2024-10-18 18:11:40',
                'val' => '#ffbb0d',
            ),
            179 => 
            array (
                'created_at' => '2024-10-18 18:11:40',
                'id' => 182,
                'name' => 'danger_color',
                'type' => 'string',
                'updated_at' => '2025-04-22 14:11:11',
                'val' => '#ff0000',
            ),
            180 => 
            array (
                'created_at' => '2024-10-18 19:30:21',
                'id' => 183,
                'name' => 'font_family',
                'type' => 'string',
                'updated_at' => '2025-04-15 11:53:47',
                'val' => 'Inter',
            ),
            181 => 
            array (
                'created_at' => '2024-10-18 19:31:06',
                'id' => 184,
                'name' => 'company_website',
                'type' => 'string',
                'updated_at' => '2024-11-14 12:13:40',
                'val' => 'https://yourbroker.com',
            ),
            182 => 
            array (
                'created_at' => '2024-10-18 19:31:06',
                'id' => 185,
                'name' => 'company_phone',
                'type' => 'string',
                'updated_at' => '2024-10-21 08:55:25',
                'val' => '+1-234-567-890',
            ),
            183 => 
            array (
                'created_at' => '2024-10-18 19:31:06',
                'id' => 186,
                'name' => 'registered_address',
                'type' => 'string',
                'updated_at' => '2024-10-21 08:55:25',
                'val' => '123 Broker Street, New York, NY 10001, USA',
            ),
            184 => 
            array (
                'created_at' => '2024-10-18 19:31:06',
                'id' => 187,
                'name' => 'registered_number',
                'type' => 'string',
                'updated_at' => '2024-10-21 08:55:25',
                'val' => '9876543210',
            ),
            185 => 
            array (
                'created_at' => '2024-10-18 20:34:46',
                'id' => 188,
                'name' => 'copy_trading_ratings_js',
                'type' => 'string',
                'updated_at' => '2024-10-18 20:34:46',
                'val' => 'https://copytrader.mbfx.co/portal/registration/provider',
            ),
            186 => 
            array (
                'created_at' => '2024-10-18 20:34:46',
                'id' => 189,
                'name' => 'is_copy_trading',
                'type' => 'boolean',
                'updated_at' => '2024-10-21 23:20:47',
                'val' => '0',
            ),
            187 => 
            array (
                'created_at' => '2024-10-21 23:19:32',
                'id' => 190,
                'name' => 'webterminal_status',
                'type' => 'string',
                'updated_at' => '2024-10-21 23:19:32',
                'val' => '1',
            ),
            188 => 
            array (
                'created_at' => '2024-10-26 00:33:13',
                'id' => 191,
                'name' => 'session_expiry',
                'type' => 'string',
                'updated_at' => '2024-12-31 14:48:57',
                'val' => '60',
            ),
            189 => 
            array (
                'created_at' => '2024-11-27 17:48:25',
                'id' => 192,
                'name' => 'active_data_sync_way',
                'type' => 'string',
                'updated_at' => '2024-11-27 17:48:25',
                'val' => 'mt5',
            ),
            190 => 
            array (
                'created_at' => '2024-12-09 12:56:29',
                'id' => 193,
                'name' => 'header_bg',
                'type' => 'string',
                'updated_at' => '2025-03-29 15:05:14',
                'val' => '#ffffff',
            ),
            191 => 
            array (
                'created_at' => '2024-12-09 12:56:29',
                'id' => 194,
                'name' => 'header_color',
                'type' => 'string',
                'updated_at' => '2025-03-29 15:13:09',
                'val' => '#242424',
            ),
            192 => 
            array (
                'created_at' => '2024-12-11 23:34:54',
                'id' => 195,
                'name' => 'header_bg_dark',
                'type' => 'string',
                'updated_at' => '2025-04-10 21:45:14',
                'val' => '#50e600',
            ),
            193 => 
            array (
                'created_at' => '2024-12-11 23:34:54',
                'id' => 196,
                'name' => 'header_color_dark',
                'type' => 'string',
                'updated_at' => '2025-02-11 14:07:16',
                'val' => '#ffffff',
            ),
            194 => 
            array (
                'created_at' => '2024-12-17 14:22:05',
                'id' => 197,
                'name' => 'popup_image',
                'type' => 'string',
                'updated_at' => '2024-12-31 18:38:46',
                'val' => 'global/images/4za4m5x62ZvLXcRWSQXV.png',
            ),
            195 => 
            array (
                'created_at' => '2024-12-17 14:22:05',
                'id' => 198,
                'name' => 'popup_status',
                'type' => 'boolean',
                'updated_at' => '2025-02-25 17:26:42',
                'val' => '0',
            ),
            196 => 
            array (
                'created_at' => '2024-12-17 14:22:05',
                'id' => 199,
                'name' => 'popup_btn_text',
                'type' => 'string',
                'updated_at' => '2024-12-31 18:38:46',
                'val' => 'Happy New Year',
            ),
            197 => 
            array (
                'created_at' => '2024-12-17 14:22:05',
                'id' => 200,
                'name' => 'popup_btn_link',
                'type' => 'string',
                'updated_at' => '2024-12-17 14:22:05',
                'val' => 'https://brokeret.com',
            ),
            198 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 201,
                'name' => 'external_min_send',
                'type' => 'double',
                'updated_at' => '2025-02-11 13:39:10',
                'val' => '1',
            ),
            199 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 202,
                'name' => 'external_max_send',
                'type' => 'double',
                'updated_at' => '2025-02-11 13:39:10',
                'val' => '90000',
            ),
            200 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 203,
                'name' => 'external_send_charge',
                'type' => 'double',
                'updated_at' => '2025-02-11 14:01:15',
                'val' => '5',
            ),
            201 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 204,
                'name' => 'external_send_charge_type',
                'type' => 'string',
                'updated_at' => '2025-02-11 13:39:10',
                'val' => 'percentage',
            ),
            202 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 205,
                'name' => 'external_send_daily_limit',
                'type' => 'int',
                'updated_at' => '2025-05-14 18:29:56',
                'val' => '15',
            ),
            203 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 206,
                'name' => 'is_external_transfer',
                'type' => 'boolean',
                'updated_at' => '2025-02-11 13:39:19',
                'val' => '1',
            ),
            204 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 207,
                'name' => 'is_external_transfer_auto_approve',
                'type' => 'boolean',
                'updated_at' => '2025-02-11 13:39:10',
                'val' => '1',
            ),
            205 => 
            array (
                'created_at' => '2025-02-11 13:39:10',
                'id' => 208,
                'name' => 'is_external_transfer_purpose',
                'type' => 'boolean',
                'updated_at' => '2025-02-11 13:39:10',
                'val' => '1',
            ),
            206 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 209,
                'name' => 'customer_name_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            207 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 210,
                'name' => 'customer_phone_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            208 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 211,
                'name' => 'customer_username_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            209 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 212,
                'name' => 'customer_email_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            210 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 213,
                'name' => 'customer_country_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            211 => 
            array (
                'created_at' => '2025-02-26 11:47:30',
                'id' => 214,
                'name' => 'customer_dob_edit',
                'type' => 'boolean',
                'updated_at' => '2025-02-26 11:47:30',
                'val' => '1',
            ),
            212 => 
            array (
                'created_at' => '2025-03-13 21:10:44',
                'id' => 215,
                'name' => 'is_whitelabel',
                'type' => 'boolean',
                'updated_at' => '2025-04-23 15:42:20',
                'val' => '0',
            ),
            213 => 
            array (
                'created_at' => '2025-04-30 10:29:27',
                'id' => 216,
                'name' => 'pending_withdraw_limit',
                'type' => 'double',
                'updated_at' => '2025-04-30 10:29:27',
                'val' => '3',
            ),
            214 => 
            array (
                'created_at' => '2025-04-30 10:29:27',
                'id' => 217,
                'name' => 'min_ib_wallet_withdraw_limit',
                'type' => 'double',
                'updated_at' => '2025-04-30 10:29:38',
                'val' => '100',
            ),
            215 => 
            array (
                'created_at' => '2025-04-30 10:29:27',
                'id' => 218,
                'name' => 'withdraw_otp_expires',
                'type' => 'int',
                'updated_at' => '2025-04-30 10:29:27',
                'val' => '5',
            ),
            216 => 
            array (
                'created_at' => '2025-04-30 10:29:27',
                'id' => 219,
                'name' => 'withdraw_otp',
                'type' => 'boolean',
                'updated_at' => '2025-04-30 10:29:27',
                'val' => '0',
            ),
            217 => 
            array (
                'created_at' => '2025-05-09 13:52:20',
                'id' => 220,
                'name' => 'site_mobile_logo',
                'type' => 'string',
                'updated_at' => '2025-05-09 13:52:20',
                'val' => 'global/images/d7P7MjUHhzwIBltn4zsm.png',
            ),
            218 => 
            array (
                'created_at' => '2025-05-12 14:33:55',
                'id' => 221,
                'name' => 'deposit_notification_tune',
                'type' => 'string',
                'updated_at' => '2025-05-15 13:23:02',
                'val' => 'https://demo.brokeret.com/assets/global/tune/expert_notification.mp3',
            ),
            219 => 
            array (
                'created_at' => '2025-05-13 09:18:25',
                'id' => 222,
                'name' => 'default_notification_tune',
                'type' => 'string',
                'updated_at' => '2025-05-13 09:18:25',
                'val' => 'https://demo.brokeret.com/assets/global/tune/knock_knock.mp3',
            ),
            220 => 
            array (
                'created_at' => '2025-05-14 18:29:18',
                'id' => 223,
                'name' => 'is_internal_transfer',
                'type' => 'boolean',
                'updated_at' => '2025-05-14 18:29:18',
                'val' => '1',
            ),
            221 => 
            array (
                'created_at' => '2025-05-14 18:29:18',
                'id' => 224,
                'name' => 'internal_send_daily_limit',
                'type' => 'int',
                'updated_at' => '2025-05-14 18:29:43',
                'val' => '50',
            ),
            222 => 
            array (
                'created_at' => '2025-05-15 13:23:31',
                'id' => 225,
                'name' => 'withdraw_notification_tune',
                'type' => 'string',
                'updated_at' => '2025-05-15 13:23:31',
                'val' => 'https://demo.brokeret.com/assets/global/tune/sticky.mp3',
            ),
            223 => 
            array (
                'created_at' => '2025-05-22 13:17:59',
                'id' => 226,
                'name' => 'deposit_amount',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 13:23:06',
                'val' => '1',
            ),
            224 => 
            array (
                'created_at' => '2025-05-22 13:17:59',
                'id' => 227,
                'name' => 'withdraw_amount',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 13:17:59',
                'val' => '0',
            ),
            225 => 
            array (
                'created_at' => '2025-05-22 13:17:59',
                'id' => 228,
                'name' => 'internal_transfer_amount',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 13:17:59',
                'val' => '0',
            ),
            226 => 
            array (
                'created_at' => '2025-05-22 13:17:59',
                'id' => 229,
                'name' => 'external_transfer_amount',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 13:17:59',
                'val' => '0',
            ),
            227 => 
            array (
                'created_at' => '2025-05-22 18:10:52',
                'id' => 230,
                'name' => 'provider_logo_status',
                'type' => 'boolean',
                'updated_at' => '2025-05-22 18:26:42',
                'val' => '0',
            ),
        ));
        
        
    }
}