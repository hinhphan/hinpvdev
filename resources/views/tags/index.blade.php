<x-layouts.master>
    <x-slot:title>
        Tags
    </x-slot:title>

    <div class="px-4 pt-8 pb-12">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => '#'],
                ['title' => 'Tags', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-3xl mb-2">Tags</h2>
            <p class="italic text-base">All the tags used in posts.</p>
        </div>

        <div class="flex flex-wrap gap-x-8 gap-y-[18px]">
            <x-misc.tag name="hinpv" url="#" />
            <x-misc.tag name="laravel" url="#" />
            <x-misc.tag name="php" url="#" />
            <x-misc.tag name="javascript" url="#" />
            <x-misc.tag name="vue" url="#" />
            <x-misc.tag name="react" url="#" />
            <x-misc.tag name="svelte" url="#" />
            <x-misc.tag name="nextjs" url="#" />
            <x-misc.tag name="astro" url="#" />
            <x-misc.tag name="remix" url="#" />
        </div>
    </div>
</x-layouts.master>
