<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_links')->truncate();
        DB::table('document_links')->insert([
            [
                'title' => 'AML Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Client Agreement',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Complaints Handling Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Cookies Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'IB Partner Agreement',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Order Execution Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Privacy Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Risk Disclosure',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
            [
                'title' => 'US Clients Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'status' => 1,
            ],
        ]);
    }
}
