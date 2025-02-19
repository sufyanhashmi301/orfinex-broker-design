<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Plugin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePluginsData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $updates = [
            [
                'name' => 'Tawk Chat',
                'description' => 'Effortlessly engage with your customers through a free, real-time messaging platform.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/tawk.webp'
            ],
            [
                'name' => 'Google reCaptcha',
                'description' => 'Protect your website against bots and fraudulent activities without compromising user experience.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/recaptcha.webp'
            ],
            [
                'name' => 'Google Analytics',
                'description' => 'Track and analyze your website’s performance to make data-driven decisions and improve user experience.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/analytics.webp'
            ],
            [
                'name' => 'Facebook Messenger',
                'description' => 'Seamlessly connect with your audience via Meta’s powerful instant messaging platform.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/messanger.webp'
            ],
            [
                'name' => 'Nexmo',
                'description' => 'Easily integrate SMS capabilities to send and receive messages globally with high reliability.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/nexmo.webp'
            ],
            [
                'name' => 'Twilio',
                'description' => 'Enhance customer engagement with scalable SMS solutions designed for agility and performance.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/twilio.webp'
            ],
            [
                'name' => 'Pusher',
                'description' => 'Deliver real-time updates and notifications seamlessly with the industry leader in live communication technology.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/pusher.webp'
            ],
            [
                'name' => 'Sumsub (Automated KYC)',
                'description' => 'Streamline KYC processes with automated compliance solutions to detect and prevent fraud.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/sumsub.webp'
            ],
            [
                'name' => 'Risk Management System',
                'description' => 'Identify, assess, and mitigate risks effectively with comprehensive tools for financial and operational security.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/riskhub.webp'
            ],
            [
                'name' => 'Custom Chat',
                'description' => 'Build personalized connections with your audience using a customizable chat solution.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/customchat.webp'
            ],
            [
                'name' => 'Zoho SalesIQ',
                'description' => 'Enhance customer engagement and track visitor behavior with real-time chat and analytics tools.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/salesiq.webp'
            ],
            [
                'name' => 'Zoho PageSense',
                'description' => 'Visualize user behavior with heatmaps and analytics to optimize your website’s performance.',
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/pagesense.webp'
            ],
        ];

        foreach ($updates as $update) {
            Plugin::where('name', $update['name'])
                ->update([
                    'description' => $update['description'],
                    'icon' => $update['icon'],
                ]);
        }
    }
}
