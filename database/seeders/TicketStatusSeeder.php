<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_statuses')->truncate();
        DB::table('ticket_statuses')->insert([
            [
                'name' => 'Open',
                'status_type' => 'open',
            ],
            [
                'name' => 'On Hold',
                'status_type' => 'on-hold',
            ],
            [
                'name' => 'Closed',
                'status_type' => 'closed',
            ],
        ]);
    }
}
