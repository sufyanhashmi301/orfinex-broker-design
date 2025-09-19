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
        $now = now();
        
        $documents = [
            [
                'title' => 'AML Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'aml_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Client Agreement',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'client_agreement',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Complaints Handling Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'complaints_handling_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Cookies Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'cookies_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'IB Partner Agreement',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'ib_partner_agreement',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Order Execution Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'order_execution_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Privacy Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'privacy_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Risk Disclosure',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'risk_disclosure',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'US Clients Policy',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'us_clients_policy',
                'is_deleteable' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Client Fund Safety',
                'link' => 'https://cdn.brokeret.com/doc/example.pdf',
                'slug' => 'client_fund_safety',
                'is_deleteable' => 0,
                'status' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($documents as $document) {
            // Check if document with this slug already exists
            $exists = DB::table('document_links')
                ->where('slug', $document['slug'])
                ->exists();

            if (!$exists) {
                DB::table('document_links')->insert($document);
                $addedCount++;
                $this->command->info("Added: {$document['title']}");
            } else {
                $skippedCount++;
                $this->command->info("Skipped (already exists): {$document['title']}");
            }
        }

        $this->command->info("Document links seeding completed. Added: {$addedCount}, Skipped: {$skippedCount}");
    }
}
