<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\SeoMeta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating posts...');

        // Create 20 published posts
        Post::factory()
            ->count(20)
            ->published()
            ->create()
            ->each(function ($post) {
                // Attach 2-5 random tags to each post
                $tags = Tag::inRandomOrder()->limit(rand(2, 5))->pluck('id');
                $post->tags()->attach($tags);

                // Create SEO meta for each post
                SeoMeta::create([
                    'post_id' => $post->id,
                    'meta_title' => $post->title,
                    'meta_description' => $post->excerpt,
                    'meta_keywords' => $tags->count() > 0 
                        ? Tag::whereIn('id', $tags)->pluck('name')->implode(', ')
                        : null,
                ]);
            });

        // Create 5 draft posts
        Post::factory()
            ->count(5)
            ->draft()
            ->create()
            ->each(function ($post) {
                // Attach 1-3 random tags to draft posts
                $tags = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id');
                $post->tags()->attach($tags);

                // Create SEO meta for draft posts too
                SeoMeta::create([
                    'post_id' => $post->id,
                    'meta_title' => $post->title,
                    'meta_description' => $post->excerpt,
                ]);
            });

        $this->command->info('Posts seeded successfully!');
        $this->command->info('Created 20 published posts and 5 draft posts.');
    }
}
