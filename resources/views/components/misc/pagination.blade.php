<div class="flex justify-center items-center gap-x-4 pb-8 md:py-8">
    @if($prevUrl)
        <a href="{{ $prevUrl }}" class="flex items-center group/icon">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_134_1634)">
                    <path class="group-hover/icon:stroke-accent" d="M5.5 12H19.5" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path class="group-hover/icon:stroke-accent" d="M5.5 12L11.5 18" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path class="group-hover/icon:stroke-accent" d="M5.5 12L11.5 6" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_134_1634">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)" />
                    </clipPath>
                </defs>
            </svg>
            Prev
        </a>
    @else
        <span class="flex items-center opacity-50 cursor-not-allowed">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_134_1634)">
                    <path d="M5.5 12H19.5" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M5.5 12L11.5 18" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M5.5 12L11.5 6" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_134_1634">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)" />
                    </clipPath>
                </defs>
            </svg>
            Prev
        </span>
    @endif
    
    <span>{{ $currentPage }}/{{ $totalPages }}</span>
    
    @if($nextUrl)
        <a href="{{ $nextUrl }}" class="flex items-center group/icon">
            Next
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_134_1642)">
                    <path class="group-hover/icon:stroke-accent" d="M5.5 12H19.5" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path class="group-hover/icon:stroke-accent" d="M13.5 18L19.5 12" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path class="group-hover/icon:stroke-accent" d="M13.5 6L19.5 12" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_134_1642">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)" />
                    </clipPath>
                </defs>
            </svg>
        </a>
    @else
        <span class="flex items-center opacity-50 cursor-not-allowed">
            Next
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_134_1642)">
                    <path d="M5.5 12H19.5" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M13.5 6L19.5 12" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M13.5 6L19.5 12" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_134_1642">
                        <rect width="24" height="24" fill="white" transform="translate(0.5)" />
                    </clipPath>
                </defs>
            </svg>
        </span>
    @endif
</div>
