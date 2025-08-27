<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThemeColor;
use App\Services\ColorService;

class ThemeColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
    */
    public function run(): void {
        $defaults = [
            'brand'    => '#3641f5',
            'success'  => '#12b76a',  // Maps to template success colors
            'error'    => '#f04438',  // Maps to template error colors  
            'warning'  => '#f79009',  // Maps to template warning colors
            'info'     => '#0ea5e9',  // Maps to template info colors
            'secondary'=> '#667085',  // Maps to template secondary colors
        ];

        $service = new ColorService();

        foreach ($defaults as $name => $base) {
            $shades = $service->generateShades($base);

            ThemeColor::updateOrCreate(
                ['name' => $name],
                ['base_color' => $base, 'shades' => $shades]
            );
        }

        $service->generateCssFile();
    }
}
