<article>
    {{-- Post Title --}}
    <h4 class="font-medium text-lg text-accent mb-1.5">
        <a href="{{ route('posts.show', ['slug' => 'astro-paper-4-0']) }}" class="hover:underline decoration-dashed underline-offset-4">
            AstroPaper 4.0
        </a>
    </h4>

    {{-- Post Meta --}}
    <div class="mb-1.5">
        <x-misc.datetime datetime="Jan 4, 2024 | 9:30 AM" datetimeAttr="2024-01-04 09:30" />
    </div>

    {{-- Post Excerpt --}}
    <p class="font-normal text-base">AstroPaper v4: ensuring a smoother and more feature-rich blogging experience.</p>
</article>
