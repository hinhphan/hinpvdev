<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HinhPV' }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container max-w-3xl m-auto">
        <x-header />

        <div class="min-h-[calc(100vh-188px)] md:min-h-[calc(100vh-158px)]">
            {{ $slot }}
        </div>

        <x-footer />
    </div>
</body>
</html>