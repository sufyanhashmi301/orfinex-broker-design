<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//        $this->call(AdminSeeder::class);
        $this->call(PermissionSeeder::class);
//        $this->call(GatewaySeeder::class);

        $this->call(CountriesTableSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(KYCLevelSeeder::class);
        $this->call(KycSubLevelsTableSeeder::class);
        $this->call(KycsTableSeeder::class);
        $this->call(UpdateGatewayLogoSeeder::class);
        $this->call(EmploymentSeeder::class);
        $this->call(RiskBookSeeder::class);
        $this->call(Match2PayGatewaySeeder::class);
        $this->call(EmailTemplatesSeeder::class);
//        $this->call(DefaultIbGroupSeeder::class);
        $this->call(PluginSeeder::class);
        $this->call(TicketPrioritySeeder::class);
        $this->call(TicketStatusSeeder::class);
        $this->call(DocumentLinkSeeder::class);
        $this->call(RateSeeder::class);


    }
}
