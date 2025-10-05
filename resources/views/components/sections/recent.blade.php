<div class="relative">
    <div class="px-4 pt-12 pb-[25px] md:py-12">
        <h3 class="font-semibold text-2xl mb-[21px]">Recent Posts</h3>
        <div class="flex flex-col gap-y-6">
            <x-posts.card />
            <x-posts.card />
            <x-posts.card />
        </div>
    </div>

    <div class="flex justify-center pt-8 md:pt-0 pb-8">
        <a href="#" class="flex items-center gap-x-1 group/icon font-normal text-base">
            All Posts
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    class="group-hover/icon:fill-accent"
                    d="M11.793 17.293L13.207 18.707L19.914 12L13.207 5.29297L11.793 6.70697L16.086 11H6.5V13H16.086L11.793 17.293Z"
                    fill="#EAEDF3" />
            </svg>
        </a>
    </div>

    <x-misc.devider-bottom />
</div>
