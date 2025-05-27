<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateForexSchemaCategory extends Command
{
    protected $signature = 'forex:update-category';
    protected $description = 'Update Forex Schema account_category_id based on country value';

    public function handle()
    {
        DB::beginTransaction();

        try {
            // Update Global accounts where country = ["All"]
            DB::table('forex_schemas')
                ->where(function ($query) {
                    $query->whereJsonContains('country', 'All')
                        ->orWhereNull('country');
                })
                ->update(['account_category_id' => 1, 'is_global' => 1]);

            // Update Country & Tags where country does not contain "All"
            DB::table('forex_schemas')
                ->where(function ($query) {
                    $query->whereJsonDoesntContain('country', 'All');
                })
                ->update(['account_category_id' => 2]);

            DB::commit();
            $this->info('Forex Schema categories updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to update categories: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
