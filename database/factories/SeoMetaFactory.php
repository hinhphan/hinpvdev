<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeoMeta>
 */
class SeoMetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meta_title' => fake()->sentence(6),
            'meta_description' => fake()->paragraph(2),
            'meta_keywords' => implode(', ', fake()->words(8)),
            'canonical_url' => fake()->url(),
        ];
    }
}
