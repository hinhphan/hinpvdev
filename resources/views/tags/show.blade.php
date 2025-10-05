<x-layouts.master>
    <x-slot:title>
        Tag Detail
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Tags', 'url' => route('tags.index')],
                ['title' => 'Php', 'url' => '#'],
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Tag: FAQ</h2>
            <p class="italic text-base">All the articles with the tag "FAQ".</p>
        </div>

        <div class="flex flex-col gap-y-6">
            <x-posts.card />
            <x-posts.card />
            <x-posts.card />
        </div>
    </div>

    <x-misc.pagination :currentPage="1" :totalPages="6" prevUrl="#" nextUrl="#" />

</x-layouts.master>
