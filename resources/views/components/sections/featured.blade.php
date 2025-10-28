<div class="relative px-4 pt-12 pb-[25px] md:py-12">
    <h3 class="font-semibold text-2xl mb-[21px]">{{ __('messages.home.featured') }}</h3>
    
    @if($posts->count() > 0)
        <div class="flex flex-col gap-y-6">
            @foreach($posts as $post)
                <x-posts.card :post="$post" />
            @endforeach
        </div>
    @else
        <p class="text-gray-400 text-center py-8">{{ __('messages.home.no_posts') }}</p>
    @endif
    
    <x-misc.devider-bottom />
</div>