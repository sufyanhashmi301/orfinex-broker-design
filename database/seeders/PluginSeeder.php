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
        $chat = Plugin::where('name', 'Custom Chat')->exists();
        if (!$chat) {
            Plugin::insert([
                [
                    'icon' => 'global/plugin/tawk.png',
                    'type' => 'system',
                    'name' => 'Custom Chat',
                    'description' => 'Free Instant Messaging system',
                    'data' => json_encode([
                        'style' => "<link rel='stylesheet' href='https://primexbroker.online/static/css/main.css' />",
                        'script' => "<script src='https://primexbroker.online/static/js/main.js'></script>"
                    ]),
                    'status' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                // You can add more entries here...
            ]);
        }
    }
}
