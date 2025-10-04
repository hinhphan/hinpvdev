# HinPV Portfolio & Blog

![hinpv-portfolio-and-blog](https://api.hinpv.dev/pi/61)

![laravel](https://badgen.net/badge/Built%20With/Laravel/red)

My portfolio & blog website developed with Laravel. Tailwind CSS and AnimeJS is used for styling and animations. Blog contents are written in Markdown format.

## Features

- Responsive Design 📱💻
- Categories, Tags 🏷
- Search Box 👀
- SEO Friendly 🔎
- Markdown Content 📰

## Tech Stack

**Backend** - [Laravel](https://laravel.com/)  
**Frontend** - [Laravel Blade (Server-side Rendering)](https://laravel.com/docs/12.x/blade#main-content)  
**Styling** - [Tailwind CSS](https://tailwindcss.com/)  
**Animations** - [AnimeJS](https://animejs.com/)  
**Deployment** - [Tino](https://tino.vn/hosting-gia-re?php=3513)

## Running Locally

Clone the project

```bash
git clone https://github.com/hinhphan/hinpvdev.git
```

Go to the project directory

```bash
cd hinpvdev
```

Update your .env configuration

```bash
cp .env.example .env
```

Create the database

```bash
php artisan migrate
```

Generate a unique, secure application key

```bash
php artisan key:generate
```

Compile frontend assets

```bash
npm install
npm run build
```

Starts a local development server

```bash
php artisan serve
```

## References

- [Sat Naing Portfolio & Blog](https://github.com/satnaing/satnaing.dev)
- [AstroPaper](https://astro-paper.pages.dev)

Although I referred to some materials, all the code was written entirely by myself.

## Author

- [@hinpvdev](https://blog.hinpv.dev/about)