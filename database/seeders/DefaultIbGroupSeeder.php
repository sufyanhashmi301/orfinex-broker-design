<?php

namespace Database\Seeders;

use App\Models\IBGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultIbGroupSeeder extends Seeder
{
    public function run()
    {
        DB::table('ib_groups')->truncate();

        IBGroup::firstOrCreate(
            ['name' => 'Default IB'], // Ensure uniqueness based on the 'name' column
            ['status' => 1] // Set any other attributes for the default group
        );
    }
}
