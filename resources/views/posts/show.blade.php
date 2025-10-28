<x-layouts.master>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>

    <div class="relative px-4">
        <div class="flex flex-col gap-y-2 py-8">
            <a href="{{ route('posts.index') }}" class="flex items-center gap-x-[3px] group/icon w-fit">
                <svg width="14" height="21" viewBox="0 0 14 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:stroke-accent" d="M9.25 5L4 10.25L9.25 15.5" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Go Back
            </a>
            <h2 class="font-bold text-2xl text-accent">{{ $post->title }}</h2>
            <x-misc.datetime 
                datetime="{{ $post->published_at->format('M d, Y | g:i A') }}" 
                datetimeAttr="{{ $post->published_at->toIso8601String() }}" 
            />
        </div>

        {{-- Post Content (Markdown rendered as HTML) --}}
        <div class="prose prose-lg prose-invert max-w-none mb-8 overflow-x-auto">
            {!! markdown_to_html($post->content) !!}
        </div>

        {{-- Tags --}}
        @if($post->tags->count() > 0)
            <div class="flex flex-wrap gap-x-3 py-8">
                @foreach($post->tags as $tag)
                    <x-misc.tag name="{{ $tag->name }}" url="{{ route('tags.show', ['slug' => $tag->slug]) }}" />
                @endforeach
            </div>
        @endif

        <div class="flex flex-col md:flex-row items-center md:justify-between gap-y-6 md:gap-y-0 mb-12">
            <div>
                <p class="italic text-base leading-[150%] mb-[7px]">Share this post on:</p>
                <x-misc.social-shares />
            </div>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="flex items-center md:self-end gap-x-2 group/icon w-fit cursor-pointer hover:text-accent transition-colors">
                <svg width="21" height="14" viewBox="0 0 21 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:stroke-accent" d="M16 9.25L10.75 4L5.5 9.25" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back to Top
            </button>
        </div>
    </div>

</x-layouts.master>
