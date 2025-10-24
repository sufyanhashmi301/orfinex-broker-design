<?php

namespace Database\Seeders;

use App\Models\PushNotificationTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PushNotificationTemplatesSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            // ============================= DEPOSIT NOTIFICATIONS =============================
            [
                'name' => 'Payment Deposit Request',
                'code' => 'payment_deposit_request',
                'for' => 'Admin',
                'icon' => 'credit-card',
                'title' => 'New Payment Deposit Request',
                'message_body' => '[[full_name]] submitted a payment deposit request of [[amount]] [[currency]] via [[method_name]].',
                'short_codes' => json_encode(["[[full_name]]", "[[amount]]", "[[currency]]", "[[method_name]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Payment Deposit Approval',
                'code' => 'payment_deposit_approved',
                'for' => 'User',
                'icon' => 'circle-check',
                'title' => 'Payment Deposit Approval',
                'message_body' => 'Your payment deposit of [[amount]] [[currency]] has been approved successfully.',
                'short_codes' => json_encode(["[[amount]]", "[[currency]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Payment Deposit Rejection',
                'code' => 'payment_deposit_rejected',
                'for' => 'User',
                'icon' => 'circle-x',
                'title' => 'Payment Deposit Rejection',
                'message_body' => 'Your payment deposit of [[amount]] [[currency]] has been rejected. Reason: [[reason]]',
                'short_codes' => json_encode(["[[amount]]", "[[currency]]", "[[reason]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Manual Deposit Request',
                'code' => 'manual_deposit_request',
                'for' => 'Admin',
                'icon' => 'wallet',
                'title' => 'Manual Deposit Request Received',
                'message_body' => '[[full_name]] submitted a manual deposit request of [[amount]] [[currency]] via [[method_name]].',
                'short_codes' => json_encode(["[[full_name]]", "[[amount]]", "[[currency]]", "[[method_name]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User Manual Deposit Request',
                'code' => 'user_manual_deposit_request',
                'for' => 'User',
                'icon' => 'receipt',
                'title' => 'Deposit Status Update',
                'message_body' => 'Your deposit of [[amount]] [[currency]] has been [[status]]. Transaction ID: [[tnx]]',
                'short_codes' => json_encode(["[[amount]]", "[[currency]]", "[[tnx]]", "[[status]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============================= WITHDRAWAL NOTIFICATIONS =============================
            [
                'name' => 'Withdraw Account Request',
                'code' => 'withdraw_account_request',
                'for' => 'Admin',
                'icon' => 'landmark',
                'title' => 'New Withdraw Account Request',
                'message_body' => '[[full_name]] submitted a withdraw account request for [[method_name]] ([[currency]]).',
                'short_codes' => json_encode(["[[full_name]]", "[[method_name]]", "[[currency]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Withdraw Account Approval',
                'code' => 'withdraw_account_approval',
                'for' => 'User',
                'icon' => 'badge-check',
                'title' => 'Withdraw Account Approved',
                'message_body' => 'Your withdraw account [[method_name]] ([[currency]]) has been approved successfully.',
                'short_codes' => json_encode(["[[method_name]]", "[[currency]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Withdraw Account Rejection',
                'code' => 'withdraw_account_rejection',
                'for' => 'User',
                'icon' => 'shield-x',
                'title' => 'Withdraw Account Rejected',
                'message_body' => 'Your withdraw account request for [[method_name]] has been rejected. Reason: [[rejection_reason]]',
                'short_codes' => json_encode(["[[method_name]]", "[[currency]]", "[[rejection_reason]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Withdraw Request',
                'code' => 'withdraw_request',
                'for' => 'Admin',
                'icon' => 'banknote',
                'title' => 'New Withdrawal Request Received',
                'message_body' => '[[full_name]] requested a withdrawal of [[amount]] [[currency]] via [[method_name]].',
                'short_codes' => json_encode(["[[full_name]]", "[[amount]]", "[[currency]]", "[[method_name]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Withdraw Request User',
                'code' => 'withdraw_request_user',
                'for' => 'User',
                'icon' => 'coins',
                'title' => 'Withdrawal Status Update',
                'message_body' => 'Your withdrawal request of [[amount]] [[currency]] has been [[status]]. Transaction ID: [[tnx]]',
                'short_codes' => json_encode(["[[amount]]", "[[currency]]", "[[status]]", "[[tnx]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============================= KYC NOTIFICATIONS =============================
            [
                'name' => 'KYC Request',
                'code' => 'kyc_request',
                'for' => 'Admin',
                'icon' => 'id-card',
                'title' => 'New KYC Submission',
                'message_body' => '[[full_name]] submitted KYC documents for verification.',
                'short_codes' => json_encode(["[[full_name]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'KYC Action',
                'code' => 'kyc_action',
                'for' => 'User',
                'icon' => 'user-check',
                'title' => 'KYC Status Update',
                'message_body' => 'Your KYC verification has been [[status]]. [[message]]',
                'short_codes' => json_encode(["[[status]]", "[[message]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============================= IB NOTIFICATIONS =============================
            [
                'name' => 'IB Request',
                'code' => 'ib_request',
                'for' => 'Admin',
                'icon' => 'handshake',
                'title' => 'New IB Application Received',
                'message_body' => '[[full_name]] submitted an Introducing Broker application for [[email]].',
                'short_codes' => json_encode(["[[full_name]]", "[[email]]", "[[site_title]]", "[[site_url]]", "[[status]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'IB Approval',
                'code' => 'ib_action',
                'for' => 'User',
                'icon' => 'check-check',
                'title' => 'IB Approval',
                'message_body' => 'Your Introducing Broker application has been approved. [[message]]',
                'short_codes' => json_encode(["[[message]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'IB Disable',
                'code' => 'ib_disable_action',
                'for' => 'User',
                'icon' => 'user-x',
                'title' => 'IB Account Disabled',
                'message_body' => '[[full_name]] has been disabled as an Introducing Broker. Reason: [[reason]]',
                'short_codes' => json_encode(["[[full_name]]", "[[reason]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'IB Reject',
                'code' => 'ib_reject_action',
                'for' => 'User',
                'icon' => 'user-minus',
                'title' => 'IB Application Rejected',
                'message_body' => '[[full_name]] has been rejected as an Introducing Broker. Reason: [[reason]]',
                'short_codes' => json_encode(["[[full_name]]", "[[reason]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Master IB Status Update',
                'code' => 'mib_action',
                'for' => 'User',
                'icon' => 'crown',
                'title' => 'Master IB Status Update',
                'message_body' => 'Your Master IB status has been updated to [[status]]. [[message]]',
                'short_codes' => json_encode(["[[status]]", "[[message]]", "[[full_name]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============================= USER MANAGEMENT NOTIFICATIONS =============================
            [
                'name' => 'New User Registered',
                'code' => 'new_user',
                'for' => 'Admin',
                'icon' => 'user-plus',
                'title' => 'New User Registered',
                'message_body' => '[[full_name]] ([[email]]) has registered on [[site_title]].',
                'short_codes' => json_encode(["[[full_name]]", "[[email]]", "[[site_title]]", "[[site_url]]", "[[status]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($templates as $template) {
            if (!PushNotificationTemplate::where('code', $template['code'])->exists()) {
                PushNotificationTemplate::insert($template);
            }
        }
    }
}


