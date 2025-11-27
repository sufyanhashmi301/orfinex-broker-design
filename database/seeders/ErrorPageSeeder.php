<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ErrorPage;
use Carbon\Carbon;

class ErrorPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $errorPages = [
            [
                'name' => 'Withdraw Disabled',
                'type' => 'withdraw_disabled',
                'title' => 'Withdraw Disabled',
                'description' => 'Withdraw is currently disabled. Please contact our support team for assistance.',
                'message' => 'Thank you for your understanding',
                'button_text' => 'Back to Dashboard',
                'button_link' => '/user/dashboard',
                'button_type' => 'primary',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Withdraw Off Day',
                'type' => 'withdraw_off_day',
                'title' => 'Withdrawals are not available today.',
                'description' => 'Withdrawals are not available today. Please try again tomorrow.',
                'message' => 'Thank you for your understanding',
                'button_text' => 'Back to Dashboard',
                'button_link' => '/user/dashboard',
                'button_type' => 'primary',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Deposit Disabled',
                'type' => 'deposit_disabled',
                'title' => 'Deposit Disabled',
                'description' => 'Deposit is currently disabled. Please contact our support team for assistance.',
                'message' => 'Thank you for your understanding',
                'button_text' => 'Back to Dashboard',
                'button_link' => '/user/dashboard',
                'button_type' => 'primary',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Transfer Money Disabled',
                'type' => 'send_money_disabled',
                'title' => 'Transfer Money Disabled',
                'description' => 'Transfer money functionality is currently disabled. Please contact our support team for assistance.',
                'message' => 'Thank you for your understanding',
                'button_text' => 'Back to Dashboard',
                'button_link' => '/user/dashboard',
                'button_type' => 'primary',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($errorPages as $errorPage) {
            ErrorPage::updateOrCreate(
                ['type' => $errorPage['type']],
                $errorPage
            );
        }
    }
}

