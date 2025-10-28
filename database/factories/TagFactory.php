<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'TypeScript', 'Vue.js', 'React', 
            'Tailwind CSS', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 
            'Git', 'API', 'REST', 'GraphQL', 'Testing', 'Security',
            'Performance', 'DevOps', 'AWS', 'Azure', 'Firebase',
            'Node.js', 'Python', 'Rust', 'Go', 'Web Development',
            'Mobile Development', 'UI/UX', 'Design Patterns', 'Architecture'
        ];
        
        $name = fake()->unique()->randomElement($tags);
        
        return [
            'slug' => Str::slug($name),
            'name' => $name,
        ];
    }
}
