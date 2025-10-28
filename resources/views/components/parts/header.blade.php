<div class="group/header flex flex-col md:flex-row md:items-center justify-between px-4 py-0 md:py-9 relative">
    <div class="flex items-center justify-between py-4 md:py-0">
        {{-- Logo --}}
        <h1 class="font-semibold md:font-bold text-xl md:text-2xl leading-[26px]">
            <a href="{{ route('home') }}">AstroPaper</a>
        </h1>

        {{-- Mobile menu toggle --}}
        <label for="menu-toggle" class="md:hidden">
            <input type="checkbox" id="menu-toggle" class="peer/menu hidden" />

            <svg class="peer-checked/menu:hidden" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_150_3193)">
                    <path d="M4 6H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_150_3193">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg>

            <svg class="peer-not-checked/menu:hidden" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_156_5659)">
                    <path d="M18 6.00003L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 6.00003L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_156_5659">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg>

        </label>
    </div>
    <div class="hidden group-has-[#menu-toggle:checked]/header:flex md:flex flex-col md:flex-row items-center gap-x-5 gap-y-6 py-4 md:py-0">
        {{-- Navigation Links --}}
        <ul class="flex flex-col md:flex-row items-center gap-x-5 gap-y-6">
            <li>
                <a href="{{ route('posts.index') }}" class="hover:underline hover:decoration-wavy font-medium text-base md:text-lg">{{ __('messages.nav.posts') }}</a>
            </li>
            <li>
                <a href="{{ route('tags.index') }}" class="hover:underline hover:decoration-wavy font-medium text-base md:text-lg">{{ __('messages.nav.tags') }}</a>
            </li>
            <li>
                <a href="{{ route('about') }}" class="hover:underline hover:decoration-wavy font-medium text-base md:text-lg">{{ __('messages.nav.about') }}</a>
            </li>
        </ul>

        <div class="flex items-center justify-center gap-x-[42px] md:gap-x-5">
            {{-- Search Icon --}}
            <a href="{{ route('search') }}" class="group/icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:fill-accent transition-colors" d="M19.023 16.977C18.5546 16.5284 18.0988 16.0669 17.656 15.593C17.284 15.215 17.06 14.94 17.06 14.94L14.26 13.603C15.381 12.3316 15.9997 10.695 16 9C16 5.141 12.86 2 9 2C5.14 2 2 5.141 2 9C2 12.859 5.14 16 9 16C10.763 16 12.37 15.34 13.603 14.261L14.94 17.061C14.94 17.061 15.215 17.285 15.593 17.657C15.98 18.02 16.489 18.511 16.977 19.024L18.335 20.416L18.939 21.062L21.06 18.941L20.414 18.337C20.035 17.965 19.529 17.471 19.023 16.977ZM9 14C6.243 14 4 11.757 4 9C4 6.243 6.243 4 9 4C11.757 4 14 6.243 14 9C14 11.757 11.757 14 9 14Z" fill="currentColor"/>
                </svg>
            </a>

            {{-- Dark Mode Toggle --}}
            <button id="theme-toggle" class="group/icon" title="Toggle theme">
                {{-- Moon Icon (shown in light mode to switch to dark) --}}
                <svg id="theme-toggle-dark-icon" class="hidden" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:fill-accent transition-colors" d="M20.742 13.045C20.0643 13.225 19.3662 13.3161 18.665 13.316C16.53 13.316 14.525 12.486 13.019 10.98C12.0301 9.98535 11.3191 8.74886 10.9569 7.39379C10.5948 6.03872 10.5941 4.6124 10.955 3.257C11.0001 3.08753 10.9998 2.90919 10.9542 2.73987C10.9086 2.57055 10.8192 2.4162 10.6951 2.2923C10.571 2.16841 10.4165 2.07932 10.2471 2.03399C10.0777 1.98865 9.8994 1.98865 9.73 2.034C8.03316 2.48618 6.48507 3.37663 5.241 4.616C1.343 8.514 1.343 14.859 5.241 18.759C6.16753 19.6907 7.26964 20.4294 8.48354 20.9323C9.69745 21.4352 10.999 21.6924 12.313 21.689C13.6266 21.6927 14.9279 21.4357 16.1415 20.9329C17.3551 20.4302 18.4569 19.6916 19.383 18.76C20.6233 17.5157 21.5142 15.9668 21.966 14.269C22.0109 14.0996 22.0105 13.9214 21.9649 13.7522C21.9193 13.583 21.8301 13.4287 21.7062 13.3048C21.5823 13.1809 21.428 13.0917 21.2588 13.0461C21.0896 13.0005 20.9114 13.0001 20.742 13.045ZM17.97 17.346C17.229 18.0911 16.3475 18.6818 15.3767 19.084C14.4058 19.4862 13.3649 19.6918 12.314 19.689C11.2628 19.6916 10.2215 19.4858 9.25033 19.0835C8.27916 18.6811 7.39739 18.0902 6.656 17.345C3.538 14.226 3.538 9.15 6.656 6.031C7.25851 5.42917 7.9541 4.92841 8.716 4.548C8.60448 5.98706 8.80496 7.43323 9.30373 8.78769C9.80251 10.1421 10.5878 11.373 11.606 12.396C12.6268 13.4174 13.8573 14.2049 15.2123 14.704C16.5673 15.2032 18.0146 15.4021 19.454 15.287C19.0715 16.0476 18.5706 16.7426 17.97 17.346Z" fill="currentColor"/>
                </svg>
                
                {{-- Sun Icon (shown in dark mode to switch to light) --}}
                <svg id="theme-toggle-light-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="group-hover/icon:fill-accent transition-colors" d="M12 18C8.68629 18 6 15.3137 6 12C6 8.68629 8.68629 6 12 6C15.3137 6 18 8.68629 18 12C18 15.3137 15.3137 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16ZM11 1H13V4H11V1ZM11 20H13V23H11V20ZM3.51472 4.92893L4.92893 3.51472L7.05025 5.63604L5.63604 7.05025L3.51472 4.92893ZM16.9497 18.364L18.364 16.9497L20.4853 19.0711L19.0711 20.4853L16.9497 18.364ZM19.0711 3.51472L20.4853 4.92893L18.364 7.05025L16.9497 5.63604L19.0711 3.51472ZM5.63604 16.9497L7.05025 18.364L4.92893 20.4853L3.51472 19.0711L5.63604 16.9497ZM23 11V13H20V11H23ZM4 11V13H1V11H4Z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
    <x-misc.devider-bottom />
</div>

@push('scripts')
<script>
    // Dark mode toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Check for saved theme preference or default to 'dark'
    const currentTheme = localStorage.getItem('theme') || 'dark';
    
    // Set initial theme
    if (currentTheme === 'light') {
        document.documentElement.classList.add('light');
        themeToggleDarkIcon.classList.remove('hidden');
        themeToggleLightIcon.classList.add('hidden');
    } else {
        document.documentElement.classList.remove('light');
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleDarkIcon.classList.add('hidden');
    }

    // Toggle theme on button click
    themeToggleBtn.addEventListener('click', function() {
        // Toggle icons
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // Toggle theme class
        if (document.documentElement.classList.contains('light')) {
            document.documentElement.classList.remove('light');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.add('light');
            localStorage.setItem('theme', 'light');
        }
    });
</script>
@endpush