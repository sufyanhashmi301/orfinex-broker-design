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
            [
                'name' => 'Withdraw Account Request',
                'code' => 'withdraw_account_request',
                'for' => 'Admin',
                'title' => 'New Withdraw Account Request',
                'message_body' => 'User [[full_name]] submitted a withdraw account request for [[method_name]] ([[currency]]).',
                'short_codes' => json_encode(["[[full_name]]", "[[method_name]]", "[[currency]]", "[[site_title]]", "[[site_url]]"]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Withdraw Account Approved',
                'code' => 'withdraw_account_approval',
                'for' => 'Admin',
                'title' => 'Withdraw Account Approved',
                'message_body' => 'User [[full_name]] withdraw account [[method_name]] ([[currency]]) has been approved.',
                'short_codes' => json_encode(["[[full_name]]", "[[method_name]]", "[[currency]]", "[[site_title]]", "[[site_url]]"]),
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


