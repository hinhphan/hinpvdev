<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role
        Role::query()->delete();
        Role::insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Administrator with full permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Guest',
                'description' => 'Guest user with limited permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
