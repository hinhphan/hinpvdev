<x-layouts.master>
    <x-slot:title>
        {{ __('messages.admin.dashboard') }}
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <h2 class="font-semibold text-2xl md:text-3xl mb-2">{{ __('messages.admin.dashboard') }}</h2>
            <p class="italic text-base">{{ __('messages.admin.manage_content') }}</p>
        </div>

        <x-misc.success-message />

        <x-cards.card class="p-6 mb-6">
            <h3 class="font-semibold text-xl mb-4">{{ __('messages.admin.manage_posts') }}</h3>
            
            <div class="flex flex-col gap-4">
                <a href="{{ route('admin.posts.create') }}" class="bg-accent py-2 px-4 rounded text-center hover:opacity-90">
                    {{ __('messages.admin.create_new_post') }}
                </a>
                
                <a href="{{ route('admin.posts.index') }}" class="border border-accent py-2 px-4 rounded text-center hover:bg-accent/10">
                    {{ __('messages.admin.posts_list') }}
                </a>
                
                <a href="{{ route('admin.tags.index') }}" class="border border-accent py-2 px-4 rounded text-center hover:bg-accent/10">
                    {{ __('messages.admin.manage_tags') }}
                </a>
            </div>
        </x-cards.card>

        <x-cards.card class="p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">{{ __('messages.admin.account') }}</h3>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="border border-accent py-2 px-4 rounded hover:bg-accent/10">
                        {{ __('messages.nav.logout') }}
                    </button>
                </form>
            </div>
        </x-cards.card>
    </div>
</x-layouts.master>
