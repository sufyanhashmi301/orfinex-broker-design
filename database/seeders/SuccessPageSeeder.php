<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuccessPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $successPages = [
            // Deposit Success
            [
                'name' => 'Default Deposit Success',
                'type' => 'deposit',
                'title' => 'Payment Successful!',
                'subtitle' => 'Your deposit has been processed successfully',
                'message' => 'Thank you for your trust in our platform. Your funds have been credited to your account and are ready to use.',
                'quote' => 'Success is not final, failure is not fatal: it is the courage to continue that counts.',
                'quote_author' => 'Winston Churchill',
                'image_path' => 'frontend/images/success-page__img.svg',
                'button_text' => 'Go to Dashboard',
                'button_link' => '/user/dashboard',
                'button_type' => 'primary',
                'trustpilot_button_show' => true,
                'quote_show' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Withdrawal Success
            [
                'name' => 'Default Withdrawal Success',
                'type' => 'withdrawal',
                'title' => 'Withdrawal Request Submitted!',
                'subtitle' => 'Your withdrawal request is being processed',
                'message' => 'We have received your withdrawal request. The funds will be transferred to your designated account within the specified processing time.',
                'quote' => 'The best time to plant a tree was 20 years ago. The second best time is now.',
                'quote_author' => 'Chinese Proverb',
                'image_path' => 'frontend/images/success-page__img.svg',
                'button_text' => 'View Transaction History',
                'button_link' => '/user/history/transactions',
                'button_type' => 'primary',
                'trustpilot_button_show' => false,
                'quote_show' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Transfer Success
            [
                'name' => 'Default Transfer Success',
                'type' => 'transfer',
                'title' => 'Transfer Complete!',
                'subtitle' => 'Your funds have been transferred successfully',
                'message' => 'The transfer has been completed successfully. The recipient will receive the funds shortly.',
                'quote' => 'The purpose of life is not to be happy. It is to be useful, to be honorable, to be compassionate.',
                'quote_author' => 'Ralph Waldo Emerson',
                'image_path' => 'frontend/images/success-page__img.svg',
                'button_text' => 'Back to Transfers',
                'button_link' => '/user/transfer',
                'button_type' => 'primary',
                'trustpilot_button_show' => false,
                'quote_show' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert or update success pages (idempotent)
        foreach ($successPages as $page) {
            DB::table('success_pages')->updateOrInsert(
                [
                    'type' => $page['type'],
                    'name' => $page['name'],
                ],
                $page
            );
        }

        $this->command->info('Success pages seeded successfully!');
    }
}
