<x-layouts.master>
    <x-slot:title>
        {{ __('messages.about.title') }}
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[['title' => __('messages.common.home'), 'url' => route('home')], ['title' => __('messages.about.title'), 'url' => '#']]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">{{ __('messages.about.title') }}</h2>
        </div>

        <div>
            <p class="mb-5">AstroPaper is a minimal, responsive and SEO-friendly Astro blog theme. I designed and crafted this based
                on my personal blog.</p>
            <p class="mb-5">This theme is aimed to be accessible out of the box. Light and dark mode are supported by default and
                additional color schemes can also be configured.</p>
            <p class="mb-5">This theme is self-documented _ which means articles/posts in this theme can also be considered as
                documentations. So, see the documentation for more info.</p>
            
            <div class="flex justify-center">
                <img src="{{ asset('images/dev.svg') }}" alt="Developer working on a laptop" width="368" height="290">
            </div>

            <h3 class="font-bold text-2xl pt-3.5 pb-5">Tech Stack</h3>
            <p class="mb-5">This theme is written in vanilla JavaScript (+ TypeScript for type checking) and a little bit of ReactJS for some interactions. TailwindCSS is used for styling; and Markdown is used for blog contents.</p>

            <h3 class="font-bold text-2xl pt-3.5 pb-5">Features</h3>
            <p class="mb-5">Here are certain features of this site.</p>

            <ul class="list-disc pl-4 leading-[36px] mb-5">
                <li>fully responsive and accessible</li>
                <li>SEO-friendly</li>
                <li>light & dark mode</li>
                <li>fuzzy search</li>
                <li>super fast performance</li>
                <li>draft posts</li>
                <li>pagination</li>
                <li>sitemap & rss feed</li>
                <li>highly customizable</li>
            </ul>

            <p>
                If you like this theme, you can star/contribute to the <a href="#" class="underline decoration-dashed underline-offset-4">repo</a>.<br />
                Or you can even give any feedback via my <a href="#" class="underline decoration-dashed underline-offset-4">email</a>.
            </p>
        </div>
    </div>
</x-layouts.master>
