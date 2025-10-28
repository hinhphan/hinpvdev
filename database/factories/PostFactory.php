<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $publishedAt = fake()->boolean(80) ? fake()->dateTimeBetween('-1 year', 'now') : null;
        
        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => $this->generateMarkdownContent(),
            'excerpt' => fake()->paragraph(3),
            'status' => $publishedAt ? Post::STATUS['PUBLISHED'] : Post::STATUS['DRAFT'],
            'published_at' => $publishedAt,
        ];
    }

    /**
     * Generate sample markdown content with rich elements
     */
    private function generateMarkdownContent(): string
    {
        $paragraphs = [];
        
        // Introduction
        $paragraphs[] = "## Introduction\n\n" . fake()->paragraph(5);
        
        // Add an image
        $paragraphs[] = "![Sample Image](https://picsum.photos/800/400?random=" . fake()->numberBetween(1, 100) . ")";
        $paragraphs[] = "*Figure 1: " . fake()->sentence(4) . "*";
        
        // Main content sections
        for ($i = 1; $i <= fake()->numberBetween(2, 3); $i++) {
            $paragraphs[] = "## " . fake()->sentence(3) . "\n\n" . fake()->paragraph(6);
            
            // Add links in paragraph
            $paragraphs[] = fake()->sentence(4) . " [Read more about this topic](" . fake()->url() . ") " . fake()->sentence(5);
            
            // Code block
            if (fake()->boolean(70)) {
                $language = fake()->randomElement(['php', 'javascript', 'python', 'bash', 'sql']);
                $code = $this->generateCodeSample($language);
                $paragraphs[] = "### Code Example\n\n```{$language}\n{$code}\n```";
            }
            
            $paragraphs[] = fake()->paragraph(4);
            
            // Unordered list
            if (fake()->boolean(60)) {
                $paragraphs[] = "### Key Points\n";
                $listItems = [];
                for ($j = 0; $j < fake()->numberBetween(3, 6); $j++) {
                    $listItems[] = "- " . fake()->sentence();
                }
                $paragraphs[] = implode("\n", $listItems);
            }
            
            // Ordered list
            if (fake()->boolean(50)) {
                $paragraphs[] = "### Step-by-Step Guide\n";
                $orderedItems = [];
                for ($j = 1; $j <= fake()->numberBetween(3, 5); $j++) {
                    $orderedItems[] = "{$j}. " . fake()->sentence();
                }
                $paragraphs[] = implode("\n", $orderedItems);
            }
            
            // Table
            if (fake()->boolean(50)) {
                $tableRows = [];
                $tableRows[] = "### Comparison Table";
                $tableRows[] = "";
                $tableRows[] = "| Feature | Description | Status |";
                $tableRows[] = "|---------|-------------|--------|";
                for ($j = 0; $j < fake()->numberBetween(3, 5); $j++) {
                    $feature = fake()->word();
                    $desc = fake()->sentence(3);
                    $status = fake()->randomElement(['✅ Active', '⚠️ Beta', '🚀 Coming Soon']);
                    $tableRows[] = "| {$feature} | {$desc} | {$status} |";
                }
                $paragraphs[] = implode("\n", $tableRows);
            }
            
            // Blockquote
            if (fake()->boolean(40)) {
                $paragraphs[] = "> " . fake()->sentence(12) . "\n>\n> — " . fake()->name();
            }
        }
        
        // YouTube embed section
        if (fake()->boolean(60)) {
            $videoIds = ['dQw4w9WgXcQ', 'jNQXAC9IVRw', 'L_LUpnjgPso', '9bZkp7q19f0', 'kJQP7kiw5Fk', 'ZZ5LpwO-An4'];
            $videoId = fake()->randomElement($videoIds);
            $paragraphs[] = "## Video Tutorial\n\nWatch this video to learn more:\n\n<iframe width=\"100%\" height=\"500\" src=\"https://www.youtube.com/embed/{$videoId}\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
        }
        
        // Embedded content (iframe simulation using link)
        if (fake()->boolean(40)) {
            $paragraphs[] = "## External Resources\n\nCheck out these useful resources:";
            $paragraphs[] = "- [CodePen Demo](https://codepen.io/example)";
            $paragraphs[] = "- [GitHub Repository](" . fake()->url() . ")";
            $paragraphs[] = "- [Stack Overflow Discussion](https://stackoverflow.com/questions/example)";
        }
        
        // Task list
        if (fake()->boolean(40)) {
            $paragraphs[] = "## Implementation Checklist\n";
            $paragraphs[] = "- [x] " . fake()->sentence();
            $paragraphs[] = "- [x] " . fake()->sentence();
            $paragraphs[] = "- [ ] " . fake()->sentence();
            $paragraphs[] = "- [ ] " . fake()->sentence();
        }
        
        // Horizontal rule
        $paragraphs[] = "---";
        
        // Conclusion with emphasis
        $paragraphs[] = "## Conclusion\n\n" . fake()->paragraph(4);
        $paragraphs[] = "**Key Takeaway:** " . fake()->sentence(8);
        $paragraphs[] = "*Remember:* " . fake()->sentence(6);
        
        // Related links
        $paragraphs[] = "### Further Reading\n";
        for ($i = 0; $i < 3; $i++) {
            $paragraphs[] = "- [" . fake()->sentence(4) . "](" . fake()->url() . ")";
        }
        
        return implode("\n\n", $paragraphs);
    }

    /**
     * Generate sample code for different languages
     */
    private function generateCodeSample(string $language): string
    {
        $samples = [
            'php' => "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Example extends Model\n{\n    protected \$fillable = ['name', 'description'];\n    \n    public function processData()\n    {\n        return \$this->where('status', 'active')\n            ->orderBy('created_at', 'desc')\n            ->get();\n    }\n}",
            
            'javascript' => "const fetchData = async () => {\n    try {\n        const response = await fetch('/api/data');\n        const data = await response.json();\n        \n        // Process data\n        const processed = data.map(item => ({\n            id: item.id,\n            name: item.name.toUpperCase()\n        }));\n        \n        return processed;\n    } catch (error) {\n        console.error('Error:', error);\n        throw error;\n    }\n};",
            
            'python' => "def calculate_statistics(numbers):\n    \"\"\"\n    Calculate statistics for a list of numbers\n    \"\"\"\n    total = sum(numbers)\n    count = len(numbers)\n    average = total / count if count > 0 else 0\n    \n    return {\n        'sum': total,\n        'count': count,\n        'average': average,\n        'max': max(numbers) if numbers else None,\n        'min': min(numbers) if numbers else None\n    }\n\nresult = calculate_statistics([1, 2, 3, 4, 5])\nprint(f'Statistics: {result}')",
            
            'bash' => "#!/bin/bash\n\n# Deployment script\nset -e\n\necho \"Starting deployment...\"\n\n# Pull latest code\ngit pull origin main\n\n# Install dependencies\ncomposer install --no-dev --optimize-autoloader\nnpm ci\nnpm run build\n\n# Run migrations\nphp artisan migrate --force\n\n# Clear and cache\nphp artisan config:cache\nphp artisan route:cache\nphp artisan view:cache\n\necho \"✅ Deployment completed!\"",
            
            'sql' => "-- Query to get user statistics\nSELECT \n    u.id,\n    u.name,\n    u.email,\n    COUNT(p.id) as post_count,\n    MAX(p.created_at) as last_post_date\nFROM users u\nLEFT JOIN posts p ON u.id = p.user_id\nWHERE u.status = 'active'\n    AND u.created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)\nGROUP BY u.id, u.name, u.email\nHAVING post_count > 5\nORDER BY post_count DESC\nLIMIT 10;",
        ];
        
        return $samples[$language] ?? "// Sample code\nconsole.log('Hello World');";
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Post::STATUS['PUBLISHED'],
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the post is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Post::STATUS['DRAFT'],
            'published_at' => null,
        ]);
    }
}
