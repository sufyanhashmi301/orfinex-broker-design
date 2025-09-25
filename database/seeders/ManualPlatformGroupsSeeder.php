<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformGroup;

class ManualPlatformGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            $groupName = 'test ' . $i;

            $exists = PlatformGroup::where('group', $groupName)
                ->where('source_type', 'manual')
                ->exists();

            if ($exists) {
                continue;
            }

            $group = new PlatformGroup();
            $group->risk_book_id = 0;
            $group->group_id = null; // manual groups have no MT5 group id
            $group->group = $groupName;
            $group->currency = 'USD';
            $group->currencyDigits = '2';
            $group->trader_type = 'manual';
            $group->source_type = 'manual';
            $group->status = 1;
            $group->save();
        }
    }
}


