<article>
    {{-- Post Title --}}
    <h4 class="font-medium text-lg text-accent mb-1.5">
        <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="hover:underline decoration-dashed underline-offset-4">
            {{ $post->title }}
        </a>
    </h4>

    {{-- Post Meta --}}
    <div class="mb-1.5">
        <x-misc.datetime 
            datetime="{{ $post->published_at ? $post->published_at->format('M d, Y | g:i A') : 'Draft' }}" 
            datetimeAttr="{{ $post->published_at ? $post->published_at->toIso8601String() : '' }}" 
        />
    </div>

    {{-- Post Excerpt --}}
    @if($post->excerpt)
        <p class="font-normal text-base">{{ $post->excerpt }}</p>
    @endif
</article>
