<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteBlockioGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deleted = DB::table('gateways')->where('gateway_code', 'blockio')->delete();

        if ($deleted) {
            echo "Record with gateway_code 'blockio' has been deleted.\n";
        } else {
            echo "No record found with gateway_code 'blockio'.\n";
        }
    }
}
