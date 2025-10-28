# Database Seeding Guide

## Quick Start

### Fresh Database (Recommended)
```powershell
# Drop all tables, re-run migrations, and seed
php artisan migrate:fresh --seed
```

### Seed Only (Keep existing data)
```powershell
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=TagSeeder
php artisan db:seed --class=PostSeeder
```

## What Gets Seeded

### 1. Admin User
- **Email**: `admin@hinpv.dev`
- **Password**: `password`
- ✅ Email verified

### 2. Tags (20 items)
- Laravel, PHP, JavaScript, TypeScript
- Vue.js, React, Tailwind CSS
- MySQL, PostgreSQL, Redis, Docker
- Git, API, REST, GraphQL
- Testing, Security, Performance
- DevOps, Web Development

### 3. Posts (25 items)
- **Published**: 20 posts with random publish dates in the past year
- **Drafts**: 5 draft posts
- Each post includes:
  - Markdown content with headings, paragraphs, code blocks, and lists
  - Excerpt (summary)
  - 2-5 random tags
  - SEO metadata

### 4. SEO Metas (25 items)
- One SEO meta for each post
- Includes meta title, description, and keywords

## Factory Features

### Post Factory
```php
// Create published posts
Post::factory()->published()->count(10)->create();

// Create draft posts
Post::factory()->draft()->count(5)->create();

// Create with specific data
Post::factory()->create([
    'title' => 'My Custom Title',
    'status' => Post::STATUS['PUBLISHED'],
]);
```

### Markdown Content
Posts are generated with realistic Markdown content including:
- Multiple sections with headings
- Paragraphs of lorem text
- Code blocks (PHP, JavaScript, Python, Bash)
- Bullet lists
- Introduction and conclusion sections

## Resetting Database

```powershell
# WARNING: This will delete all data!
php artisan migrate:fresh --seed
```

## Testing Data

After seeding, you can:
- Login at `/login` with `admin@hinpv.dev` / `password`
- View posts at `/posts`
- View tags at `/tags`
- Access admin dashboard at `/dashboard`

## Customization

Edit seeders in `database/seeders/`:
- `DatabaseSeeder.php` - Main seeder orchestrator
- `TagSeeder.php` - Create tags
- `PostSeeder.php` - Create posts with relationships

Edit factories in `database/factories/`:
- `PostFactory.php` - Customize post generation
- `TagFactory.php` - Customize tag generation
- `SeoMetaFactory.php` - Customize SEO meta generation

## Tips

1. **Fresh start**: Always use `migrate:fresh --seed` for clean slate
2. **Custom counts**: Edit `PostSeeder.php` to change number of posts
3. **Real content**: Replace faker content with your own in factories
4. **Images**: Factories don't create images yet - upload manually or extend factories
