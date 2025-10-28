<x-layouts.master>
    <x-slot:title>
        Search
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[['title' => 'Home', 'url' => route('home')], ['title' => 'Search', 'url' => '#']]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Search</h2>
            <p class="italic text-base">Search any article ...</p>
        </div>

        <div>
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                <svg class="absolute left-[12px] top-[12px]" width="29" height="32" viewBox="0 0 29 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22.7658 22.636C22.2053 22.0378 21.6598 21.4224 21.1298 20.7906C20.6847 20.2866 20.4166 19.92 20.4166 19.92L17.0657 18.1373C18.4072 16.4421 19.1476 14.2599 19.148 12C19.148 6.85463 15.3902 2.66663 10.7708 2.66663C6.15134 2.66663 2.39355 6.85463 2.39355 12C2.39355 17.1453 6.15134 21.3333 10.7708 21.3333C12.8807 21.3333 14.8038 20.4533 16.2794 19.0146L17.8795 22.748C17.8795 22.748 18.2086 23.0466 18.6609 23.5426C19.1241 24.0266 19.7332 24.6813 20.3172 25.3653L21.9424 27.2213L22.6653 28.0826L25.2036 25.2546L24.4305 24.4493C23.9769 23.9533 23.3713 23.2946 22.7658 22.636ZM10.7708 18.6666C7.47136 18.6666 4.78705 15.676 4.78705 12C4.78705 8.32396 7.47136 5.33329 10.7708 5.33329C14.0702 5.33329 16.7545 8.32396 16.7545 12C16.7545 15.676 14.0702 18.6666 10.7708 18.6666Z"
                        fill="currentColor" />
                </svg>
                <input 
                    type="text" 
                    name="q"
                    value="{{ $query ?? '' }}"
                    placeholder="Search for articles..."
                    class="border border-text-base rounded-lg pl-12 py-3.5 w-full outline-0"
                    >
                </div>
            </form>            <p class="my-6">Found {{ isset($posts) ? $posts->total() : 0 }} result(s){{ isset($query) && $query ? " for '{$query}'" : '' }}</p>
        </div>

        @if(isset($posts) && $posts->count() > 0)
            <div class="flex flex-col gap-y-6">
                @foreach($posts as $post)
                    <x-posts.card :post="$post" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                @if(isset($query) && $query)
                    <p class="text-lg text-gray-400">No results found for "{{ $query }}".</p>
                @else
                    <p class="text-lg text-gray-400">Enter a search query to find articles.</p>
                @endif
            </div>
        @endif
    </div>

    @if(isset($posts) && $posts->hasPages())
        <x-misc.pagination 
            :prevUrl="$posts->previousPageUrl()" 
            :nextUrl="$posts->nextPageUrl()" 
            :currentPage="$posts->currentPage()" 
            :totalPages="$posts->lastPage()" 
        />
    @endif
</x-layouts.master>
