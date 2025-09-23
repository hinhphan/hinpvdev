<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        User::create([
            'name' => fake()->name(),
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
        ]);
    }
}
