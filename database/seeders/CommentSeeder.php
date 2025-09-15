<?php

namespace Database\Seeders;

use App\Enums\CommentType;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $templates = [
            // Deposit
            [
                'title' => 'Deposit Received',
                'type' => CommentType::Deposit->value,
                'description' => 'We have received your deposit request and it is currently under review. You will be notified once it is processed.',
                'status' => false,
            ],
            [
                'title' => 'Deposit Approved',
                'type' => CommentType::Deposit->value,
                'description' => 'Your deposit has been approved and the funds have been credited to your account. Thank you for your patience.',
                'status' => true,
            ],
            [
                'title' => 'Deposit Rejected',
                'type' => CommentType::Deposit->value,
                'description' => 'Unfortunately, we could not process your deposit. Please verify the payment details and try again or contact support for assistance.',
                'status' => true,
            ],

            // Withdraw Amount
            [
                'title' => 'Withdrawal Approved',
                'type' => CommentType::WithdrawAmount->value,
                'description' => 'Your withdrawal request has been approved. The funds will be transferred shortly. Processing times may vary depending on your payment provider.',
                'status' => true,
            ],
            [
                'title' => 'Withdrawal Rejected',
                'type' => CommentType::WithdrawAmount->value,
                'description' => 'Your withdrawal request was rejected. Please ensure your account meets the withdrawal requirements and that your balance and verification status are valid.',
                'status' => true,
            ],
            [
                'title' => 'Additional Verification Required (Withdrawal)',
                'type' => CommentType::WithdrawAmount->value,
                'description' => 'To proceed with your withdrawal, we require additional verification. Please submit the requested documents and resubmit the request.',
                'status' => true,
            ],

            // Withdraw Account
            [
                'title' => 'Payout Account Approved',
                'type' => CommentType::WithdrawAccount->value,
                'description' => 'Your payout account has been approved and is now available for withdrawals. Please ensure the account details remain up to date.',
                'status' => true,
            ],
            [
                'title' => 'Payout Account Rejected',
                'type' => CommentType::WithdrawAccount->value,
                'description' => 'We were unable to approve your payout account. Please review the account information and resubmit, or contact support for guidance.',
                'status' => true,
            ],
            [
                'title' => 'Payout Account Requires Update',
                'type' => CommentType::WithdrawAccount->value,
                'description' => 'Your payout account requires an update. Please correct the highlighted details and submit again for review.',
                'status' => true,
            ],

            // KYC
            [
                'title' => 'KYC Received',
                'type' => CommentType::KYC->value,
                'description' => 'We have received your KYC submission and our team is reviewing your documents. You will be notified once the review is complete.',
                'status' => true,
            ],
            [
                'title' => 'KYC Approved',
                'type' => CommentType::KYC->value,
                'description' => 'Your KYC verification has been approved. You can now access all features available to verified accounts.',
                'status' => true,
            ],
            [
                'title' => 'KYC Rejected',
                'type' => CommentType::KYC->value,
                'description' => 'We could not verify your KYC documents. Please ensure all documents are clear, valid, and up to date, and resubmit for review.',
                'status' => true,
            ],
            [
                'title' => 'Additional Documents Required (KYC)',
                'type' => CommentType::KYC->value,
                'description' => 'Additional documents are required to complete your KYC verification. Please upload the requested files to proceed.',
                'status' => true,
            ],
        ];

        foreach ($templates as $template) {
            Comment::firstOrCreate(
                [
                    'title' => $template['title'],
                    'type' => $template['type'],
                ],
                [
                    'description' => $template['description'],
                    'status' => $template['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}


