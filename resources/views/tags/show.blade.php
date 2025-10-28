<x-layouts.master>
    <x-slot:title>
        {{ __('messages.tags.tagged_with') }}: {{ $tag->name }}
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => __('messages.common.home'), 'url' => route('home')],
                ['title' => __('messages.tags.title'), 'url' => route('tags.index')],
                ['title' => $tag->name, 'url' => '#'],
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">{{ __('messages.tags.tagged_with') }}: {{ $tag->name }}</h2>
            <p class="italic text-base">{{ __('messages.tags.posts_with_tag', ['tag' => $tag->name]) }}</p>
        </div>

        @if($posts->count() > 0)
            <div class="flex flex-col gap-y-6">
                @foreach($posts as $post)
                    <x-posts.card :post="$post" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-lg text-gray-400">{{ __('messages.tags.no_posts_with_tag') }}</p>
            </div>
        @endif
    </div>

    @if($posts->hasPages())
        <x-misc.pagination 
            :prevUrl="$posts->previousPageUrl()" 
            :nextUrl="$posts->nextPageUrl()" 
            :currentPage="$posts->currentPage()" 
            :totalPages="$posts->lastPage()" 
        />
    @endif

</x-layouts.master>
