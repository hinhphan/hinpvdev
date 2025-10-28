<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Create admin user
        $this->command->info('Creating admin user...');
        User::firstOrCreate(
            ['email' => 'admin@hinpv.dev'],
            [
                'name' => 'Hinh Phan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Admin user created: admin@hinpv.dev / password');

        // Seed tags first (posts need tags)
        $this->command->newLine();
        $this->call(TagSeeder::class);

        // Seed posts (will also create SEO metas and attach tags)
        $this->command->newLine();
        $this->call(PostSeeder::class);

        $this->command->newLine();
        $this->command->info('🎉 Database seeding completed!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Admin: admin@hinpv.dev / password');
        $this->command->info('   - Tags: 20 tags created');
        $this->command->info('   - Posts: 20 published + 5 drafts');
        $this->command->info('   - SEO Metas: 25 created');
    }
}
