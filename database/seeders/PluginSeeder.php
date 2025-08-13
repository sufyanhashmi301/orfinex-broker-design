<?php

namespace Database\Seeders;

use App\Models\Plugin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customChatExists = Plugin::where('name', 'Custom Chat')->exists();
        $zohoSalesIQExists = Plugin::where('name', 'Zoho SalesIQ')->exists();
        $zohoPageSenseExists = Plugin::where('name', 'Zoho PageSense')->exists();
        $trustpilotExists = Plugin::where('name', 'Trustpilot')->exists();
        $voisoExists = Plugin::where('name', 'Voiso')->exists();
        $shuftiProExists = Plugin::where('name', 'ShuftiPro')->exists();
        $vonageExists = Plugin::where('name', 'Vonage')->exists();
        $infobipExists = Plugin::where('name', 'Infobip')->exists();
        $oneSignalExists = Plugin::where('name', 'OneSignal')->exists();
        $cleverTapExists = Plugin::where('name', 'CleverTap')->exists();
        $cloudflareTurnstileExists = Plugin::where('name', 'Cloudflare Turnstile')->exists();
        $exchangeRateApiExists = Plugin::where('name', 'Currency Exchange API')->exists();
        $veriffExists = Plugin::where('name', 'Veriff (Automated KYC)')->exists();

        $plugins = [];
        // Add this to the $plugins array before the insert
        if (!$exchangeRateApiExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/exchangerate.webp',
                'type' => 'system',
                'name' => 'Currency Exchange API',
                'description' => 'Provides currency exchange rate data for the system',
                'data' => json_encode([
                    'api_host' => 'currency-converter-pro1.p.rapidapi.com',
                    'api_key' => '3eab3debf0msh7b97dbe30b9a426p1809fejsn0a7ea4674ebd',
                    'api_url' => 'https://currency-converter-pro1.p.rapidapi.com/latest-rates',
                    'base_currency' => 'USD'
                ]),
                'status' => 1, // Active by default since it's essential
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        // Insert Custom Chat plugin if it doesn't exist
        if (!$customChatExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/customchat.webp',
                'type' => 'system',
                'name' => 'Custom Chat',
                'description' => 'Build personalized connections with your audience using a customizable chat solution.',
                'data' => json_encode([
                    'style' => "<link rel='stylesheet' href='https://mydomain.online/static/css/main.css' />",
                    'script' => "<script src='https://mydomain.online/static/js/main.js'></script>"
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert Zoho SalesIQ plugin if it doesn't exist
        if (!$zohoSalesIQExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/salesiq.webp',
                'type' => 'system',
                'name' => 'Zoho SalesIQ',
                'description' => 'Enhance customer engagement and track visitor behavior with real-time chat and analytics tools.',
                'data' => json_encode([
                    'script' => 'https://salesiq.zohopublic.com/widget?wc=siqfa9c40c8e1860867576bdf256294f0d6a4ea805bdf69c4522834275a36d7f9b1'
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert Zoho PageSense plugin if it doesn't exist
        if (!$zohoPageSenseExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/pagesense.webp',
                'type' => 'system',
                'name' => 'Zoho PageSense',
                'description' => 'Visualize user behavior with heatmaps and analytics to optimize your website’s performance.',
                'data' => json_encode([
                    'script' => '<script src="https://cdn.pagesense.io/js/x9systems/ed4a374cd3b2492d9cb0b8d5291a4b0c.js"></script>'
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$trustpilotExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/trustpilot.webp',
                'type' => 'system',
                'name' => 'Trustpilot',
                'description' => 'Boost your business reputation by gathering and showcasing authentic customer reviews across key touchpoints.',
                'data' => json_encode([
                    'link' => 'https://www.trustpilot.com/'
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$voisoExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/voiso.webp',
                'type' => 'sms',
                'name' => 'Voiso',
                'description' => 'Streamline customer interactions with a robust omnichannel communication platform for seamless engagement.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$shuftiProExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/shuftipro.webp',
                'type' => 'system',
                'name' => 'ShuftiPro',
                'description' => 'Automate your KYC processes with advanced AI-driven identity verification solutions.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$vonageExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/vonage.webp',
                'type' => 'sms',
                'name' => 'Vonage',
                'description' => 'Simplify global SMS delivery with reliable, scalable, and developer-friendly communication APIs.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$infobipExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/infobip.webp',
                'type' => 'sms',
                'name' => 'Infobip',
                'description' => 'Enhance customer engagement with secure and fast SMS solutions, tailored for global reach.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$oneSignalExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/onesignal.webp',
                'type' => 'notification',
                'name' => 'OneSignal',
                'description' => 'Boost user engagement with scalable and easy-to-integrate push notification solutions.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$cleverTapExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/clevertap.webp',
                'type' => 'notification',
                'name' => 'CleverTap',
                'description' => 'Deliver personalized push notifications to drive retention and enhance customer experiences.',
                'data' => json_encode([

                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$cloudflareTurnstileExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/turnstile.webp',
                'type' => 'system',
                'name' => 'Cloudflare Turnstile',
                'description' => 'Safeguard your website against bots and automated abuse without compromising visitor experience.',
                'data' => json_encode([
                    'site_key' => 'your-turnstile-site-key-here',
                    'secret_key' => 'your-turnstile-secret-key-here'
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!$veriffExists) {
            $plugins[] = [
                'icon' => 'https://cdn.brokeret.com/crm-assets/admin/plugins/veriff.svg',
                'type' => 'system',
                'name' => 'Veriff (Automated KYC)',
                'description' => 'Advanced identity verification with AI-powered document analysis and real-time fraud detection.',
                'data' => json_encode([
                    'api_key' => '',
                    'shared_secret' => '',
                    'base_url' => 'https://api.veriff.com',
                    // 'integration_id' => '',
                    // 'level_name' => 'Level 2 Verification'
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert all plugins that are not yet in the database
        if (!empty($plugins)) {
            Plugin::insert($plugins);
        }
    }
}
