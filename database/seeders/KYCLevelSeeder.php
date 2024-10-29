<?php
namespace Database\Seeders;

use App\Enums\KycLevelSlug;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KYCLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kyc_levels')->truncate();
        DB::table('kyc_sub_levels')->truncate();
        DB::table('kycs')->truncate();
        // DB::table('kyc_level_settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('kyc_levels')->insert([
            [
                'name' => 'Level 1',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => KycLevelSlug::LEVEL1,
                'description' => 'Email and Phone verification required',
            ],
            [
                'name' => 'Level 2',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => KycLevelSlug::LEVEL2,
                'description' => 'ID verification method',
            ],
            [
                'name' => 'Level 3',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => KycLevelSlug::LEVEL3,
                'description' => 'Additional verification requirements',
            ],
        ]);

        // Call the other seeders
        $this->call(KycSubLevelsTableSeeder::class);
        $this->call(KycsTableSeeder::class);
    }
}
