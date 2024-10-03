<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiskBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risk_books')->truncate();
        DB::table('risk_books')->insert([
            [
                'name' => 'A-Book',
            ],
            [
                'name' => 'B-Book',
            ],
            [
                'name' => 'Hybrid',
            ],
            [
                'name' => 'Demo',
            ],
            [
                'name' => 'Un-Assigned',
            ],
        ]);
    }
}
