<x-layouts.master>
    <x-slot:title>
        Home
    </x-slot:title>

    <x-sections.hero />

    <x-sections.featured :posts="$featuredPosts" />
    
    <x-sections.recent :posts="$recentPosts" />
</x-layouts.master>