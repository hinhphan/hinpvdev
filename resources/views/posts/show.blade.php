<x-layouts.master>
    <x-slot:title>
        Post Detail
    </x-slot:title>

    <div class="relative px-4">
        <div class="flex flex-col gap-y-2 py-8">
            <a href="#" class="flex items-center gap-x-[3px] group/icon w-fit">
                <svg width="14" height="21" viewBox="0 0 14 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:stroke-accent" d="M9.25 5L4 10.25L9.25 15.5" stroke="#EAEDF3"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Go Back
            </a>
            <h2 class="font-bold text-2xl text-accent">Dynamic OG image generation in AstroPaper blog posts</h2>
            <x-misc.datetime />
        </div>

        <div class="h-[500px]">
            Post content
        </div>

        <ul class="flex flex-wrap gap-x-3 py-8">
            <li>
                <x-misc.tag />
            </li>
            <li>
                <x-misc.tag />
            </li>
            <li>
                <x-misc.tag />
            </li>
        </ul>

        <div class="flex flex-col md:flex-row items-center md:justify-between gap-y-6 md:gap-y-0 mb-12">
            <div>
                <p class="italic text-base leading-[150%] mb-[7px]">Share this post on:</p>
                <x-misc.social-shares />
            </div>
            <a href="#" class="flex items-center md:self-end gap-x-2 group/icon w-fit">
                <svg width="21" height="14" viewBox="0 0 21 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:stroke-accent" d="M16 9.25L10.75 4L5.5 9.25" stroke="#EAEDF3"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back to Top
            </a>
        </div>
    </div>

</x-layouts.master>
