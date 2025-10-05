<x-layouts.master>
    <x-slot:title>
        404 Not Found
    </x-slot:title>

    <div class="flex flex-col justify-center items-center min-h-[calc(100vh-188px)] md:min-h-[calc(100vh-158px)]">
        <h2 class="font-bold text-9xl leading-[128px] text-accent">404</h2>
        <p class="mb-5">¯\_(ツ)_/¯</p>
        <p class="text-3xl mb-6">Page Not Found</p>
        <a href="{{ route('home') }}" class="text-lg underline decoration-dashed underline-offset-4">Go back home</a>
    </div>
</x-layouts.master>