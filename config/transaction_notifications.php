<?php

return [

    // -------------------------
    // USER EMAIL TEMPLATES
    // -------------------------
    'user_email' => [
        // Manual Deposit
        'manual_deposit' => [
            'pending'  => 'user_manual_deposit_request',
            'success'  => 'user_manual_deposit_approve',
            'approved' => 'user_manual_deposit_approve',
            'failed'   => 'user_manual_deposit_reject',
            'rejected' => 'user_manual_deposit_reject',
        ],

        // Auto Deposit
        'deposit'          => ['success' => 'deposit_auto_user_success', 'failed' => 'deposit_auto_user_failed'],
        'voucher_deposit'  => ['success' => 'voucher_deposit_user'],
        'demo_deposit'     => ['success' => 'deposit_auto_user_success'],

        // Withdraw
        'withdraw'         => ['pending' => 'withdraw_request_user', 'success' => 'withdraw_request_user_approve'],
        'withdraw_auto'    => ['success' => 'withdraw_auto_user_success', 'failed' => 'withdraw_auto_user_failed'],

        // Send Money = Withdraw success template
        'send_money'       => ['success' => 'withdraw_request_user_approve'],

        // Bonuses, refunds, IB, referrals → treat as deposit success
        'bonus'            => ['success' => 'deposit_auto_user_success'],
        'signup_bonus'     => ['success' => 'deposit_auto_user_success'],
        'ib_bonus'         => ['success' => 'deposit_auto_user_success'],
        'bonus_refund'     => ['success' => 'deposit_auto_user_success'],
        'referral'         => ['success' => 'deposit_auto_user_success'],
        'multi_ib'         => ['success' => 'deposit_auto_user_success'],

        // Subtract Balance
        'subtract'         => ['success' => 'balance_subtracted_user'],

        // Exchange
        'exchange'         => ['success' => 'deposit_auto_user_success'],
    ],

    // -------------------------
    // USER PUSH TEMPLATES
    // -------------------------
    'user_push' => [
        'withdraw'      => 'withdraw_request_user',
        'withdraw_auto' => 'withdraw_request_user',
        'send_money'    => 'withdraw_request_user',
        'subtract'      => 'balance_subtracted_user',
        'manual_deposit' => 'user_manual_deposit_request',
        'default'       => 'deposit_auto_user_success',
    ],

    // -------------------------
    // ADMIN EMAIL
    // -------------------------
    'admin_email' => [
        'deposit' => ['success' => 'deposit_auto_admin_success'],
        'withdraw_auto' => ['success' => 'withdraw_auto_admin_success'],
        'subtract'      => ['success' => 'balance_subtracted_admin'],
        'voucher_deposit'  => ['success' => 'voucher_deposit_admin'],

        // Default fallback
        'default' => 'manual_deposit_request',
    ],

    // -------------------------
    // ADMIN PUSH
    // -------------------------
    'admin_push' => [
        'withdraw'      => 'withdraw_request',
        'withdraw_auto' => 'withdraw_request',
        'default'       => 'manual_deposit_request',
    ],
];
