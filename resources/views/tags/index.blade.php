<x-layouts.master>
    <x-slot:title>
        {{ __('messages.tags.title') }}
    </x-slot:title>

    <div class="px-4 pt-8 pb-12">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => __('messages.common.home'), 'url' => route('home')],
                ['title' => __('messages.tags.title'), 'url' => '#']
            ]" />

            <h2 class="font-semibold text-3xl mb-2">{{ __('messages.tags.title') }}</h2>
            <p class="italic text-base">{{ __('messages.tags.subtitle') }}</p>
        </div>

        @if($tags->count() > 0)
            <div class="flex flex-wrap gap-x-8 gap-y-[18px]">
                @foreach($tags as $tag)
                    <x-misc.tag 
                        name="{{ $tag->name }} ({{ $tag->posts_count }})" 
                        url="{{ route('tags.show', ['slug' => $tag->slug]) }}" 
                    />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-lg text-gray-400">{{ __('messages.tags.no_tags') }}</p>
            </div>
        @endif
    </div>
</x-layouts.master>
