<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['slug' => 'laravel', 'name' => 'Laravel'],
            ['slug' => 'php', 'name' => 'PHP'],
            ['slug' => 'javascript', 'name' => 'JavaScript'],
            ['slug' => 'typescript', 'name' => 'TypeScript'],
            ['slug' => 'vuejs', 'name' => 'Vue.js'],
            ['slug' => 'react', 'name' => 'React'],
            ['slug' => 'tailwind-css', 'name' => 'Tailwind CSS'],
            ['slug' => 'mysql', 'name' => 'MySQL'],
            ['slug' => 'postgresql', 'name' => 'PostgreSQL'],
            ['slug' => 'redis', 'name' => 'Redis'],
            ['slug' => 'docker', 'name' => 'Docker'],
            ['slug' => 'git', 'name' => 'Git'],
            ['slug' => 'api', 'name' => 'API'],
            ['slug' => 'rest', 'name' => 'REST'],
            ['slug' => 'graphql', 'name' => 'GraphQL'],
            ['slug' => 'testing', 'name' => 'Testing'],
            ['slug' => 'security', 'name' => 'Security'],
            ['slug' => 'performance', 'name' => 'Performance'],
            ['slug' => 'devops', 'name' => 'DevOps'],
            ['slug' => 'web-development', 'name' => 'Web Development'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                ['name' => $tag['name']]
            );
        }

        $this->command->info('Tags seeded successfully!');
    }
}
