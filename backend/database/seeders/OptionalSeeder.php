<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionalSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthSeeder::class,
            UserRoleSeeder::class,
        ]);
    }
}
