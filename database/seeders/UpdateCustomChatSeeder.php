<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Plugin;
use Illuminate\Database\Seeder;

class UpdateCustomChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plugin = Plugin::where('name', 'Custom Chat')->first();

        if ($plugin) {
            $plugin->update([
                'data' => json_encode([
                    'style' => '<link rel="stylesheet" href="https://example.com/static/css/main.css" />',
                    'script' => '<script src="https://example.com/static/js/main.js"></script>',
                ]),
            ]);
        }
    }
}
