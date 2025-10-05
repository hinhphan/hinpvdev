<x-layouts.master>
    <x-slot:title>
        Tags
    </x-slot:title>

    <div class="px-4 pt-8 pb-12">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Tags', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-3xl mb-2">Tags</h2>
            <p class="italic text-base">All the tags used in posts.</p>
        </div>

        <div class="flex flex-wrap gap-x-8 gap-y-[18px]">
            <x-misc.tag name="hinpv" url="{{ route('tags.show', ['slug' => 'hinpv']) }}" />
            <x-misc.tag name="laravel" url="{{ route('tags.show', ['slug' => 'laravel']) }}" />
            <x-misc.tag name="php" url="{{ route('tags.show', ['slug' => 'php']) }}" />
            <x-misc.tag name="javascript" url="{{ route('tags.show', ['slug' => 'javascript']) }}" />
            <x-misc.tag name="vue" url="{{ route('tags.show', ['slug' => 'vue']) }}" />
            <x-misc.tag name="react" url="{{ route('tags.show', ['slug' => 'react']) }}" />
            <x-misc.tag name="svelte" url="{{ route('tags.show', ['slug' => 'svelte']) }}" />
            <x-misc.tag name="nextjs" url="{{ route('tags.show', ['slug' => 'nextjs']) }}" />
            <x-misc.tag name="astro" url="{{ route('tags.show', ['slug' => 'astro']) }}" />
            <x-misc.tag name="remix" url="{{ route('tags.show', ['slug' => 'remix']) }}" />
        </div>
    </div>
</x-layouts.master>
