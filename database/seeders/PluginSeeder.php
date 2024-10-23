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
        $zohoPageSenseExists = Plugin::where('name', 'Zoho PageSense')->exists(); // Check if Zoho PageSense exists

        $plugins = [];

        // Insert Custom Chat plugin if it doesn't exist
        if (!$customChatExists) {
            $plugins[] = [
                'icon' => 'global/plugin/tawk.png',
                'type' => 'system',
                'name' => 'Custom Chat',
                'description' => 'Free Instant Messaging system',
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
                'icon' => asset('crm-assets/integration-logo/png/salesiq.png'),
                'type' => 'system',
                'name' => 'Zoho SalesIQ',
                'description' => 'Zoho SalesIQ live chat and visitor tracking software',
                'data' => json_encode([
                    'script' => <<<'EOD'
                                var $zoho=$zoho || {};
                                $zoho.salesiq = $zoho.salesiq || {
                                    widgetcode: "siq0d84b4a936b62d007a8b69171ad0bda856c528f3a2459c49b38109540292025a",
                                    values: {},
                                    ready: function(){}
                                };
                                var d=document;
                                s=d.createElement("script");
                                s.type="text/javascript";
                                s.id="zsiqscript";
                                s.defer=true;
                                s.src="https://salesiq.zohopublic.com/widget";
                                t=d.getElementsByTagName("script")[0];
                                t.parentNode.insertBefore(s,t);
                                EOD,
                ]),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert Zoho PageSense plugin if it doesn't exist
        if (!$zohoPageSenseExists) {
            $plugins[] = [
                'icon' => asset('crm-assets/integration-logo/svg/pagesense.svg'),
                'type' => 'system',
                'name' => 'Zoho PageSense',
                'description' => 'Zoho PageSense for heatmaps and visitor tracking',
                'data' => json_encode([
                    'script' => '<script src="https://cdn.pagesense.io/js/x9systems/ed4a374cd3b2492d9cb0b8d5291a4b0c.js"></script>'
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