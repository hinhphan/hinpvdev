<x-layouts.master>
    <x-slot:title>
        {{ __('messages.posts.title') }}
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => __('messages.common.home'), 'url' => route('home')],
                ['title' => __('messages.posts.title') . ' (' . __('messages.pagination.page') . ' ' . $posts->currentPage() . ')', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">{{ __('messages.posts.title') }}</h2>
            <p class="italic text-base">{{ __('messages.posts.subtitle') }}</p>
        </div>

        @if($posts->count() > 0)
            <div class="flex flex-col gap-y-6">
                @foreach($posts as $post)
                    <x-posts.card :post="$post" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-lg text-gray-400">{{ __('messages.posts.no_posts_found') }}</p>
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
