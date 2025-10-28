<x-layouts.master>
    <x-slot:title>
        Dashboard
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Dashboard</h2>
            <p class="italic text-base">Quản lý nội dung blog của bạn.</p>
        </div>

        <x-misc.success-message />

        <x-cards.card class="p-6 mb-6">
            <h3 class="font-semibold text-xl mb-4">Quản lý bài viết</h3>
            
            <div class="flex flex-col gap-4">
                <a href="{{ route('admin.posts.create') }}" class="bg-accent py-2 px-4 rounded text-center hover:opacity-90">
                    + Tạo bài viết mới
                </a>
                
                <a href="{{ route('admin.posts.index') }}" class="border border-accent py-2 px-4 rounded text-center hover:bg-accent/10">
                    Danh sách bài viết
                </a>
                
                <a href="#" class="border border-accent py-2 px-4 rounded text-center hover:bg-accent/10">
                    Quản lý tags
                </a>
            </div>
        </x-cards.card>

        <x-cards.card class="p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">Tài khoản</h3>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="border border-accent py-2 px-4 rounded hover:bg-accent/10">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </x-cards.card>
    </div>
</x-layouts.master>
