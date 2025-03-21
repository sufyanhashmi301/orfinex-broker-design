<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class NewPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
        ];
        foreach ($permissions as $permission) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission['name'], 'guard_name' => 'admin'],
                    ['category' => $permission['category']]
                );
            }
        }
    }
}
