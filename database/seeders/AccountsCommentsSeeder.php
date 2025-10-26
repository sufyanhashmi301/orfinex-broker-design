<?php

namespace Database\Seeders;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AccountsCommentsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $comments = [
            [
                'title' => 'Incomplete KYC',
                'description' => 'Your KYC is incomplete. Please submit missing documents to proceed.'
            ],
            [
                'title' => 'Invalid Group Selection',
                'description' => 'Selected group is not allowed for this schema. Choose a valid group.'
            ],
            [
                'title' => 'Leverage Not Supported',
                'description' => 'Requested leverage is not supported for this account type.'
            ],
            [
                'title' => 'Insufficient Wallet Balance',
                'description' => 'Minimum wallet balance requirement not met for account creation.'
            ],
            [
                'title' => 'Incorrect Account Details',
                'description' => 'Some provided details are incorrect. Please review and resubmit.'
            ],
        ];

        foreach ($comments as $c) {
            Comment::updateOrCreate(
                [
                    'title' => $c['title'],
                    'type' => 'accounts',
                ],
                [
                    'description' => $c['description'],
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}


