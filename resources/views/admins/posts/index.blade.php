<x-layouts.master>
    <x-slot:title>
        Quản lý bài viết
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Danh sách bài viết', 'url' => '#']
            ]" />

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-2xl md:text-3xl mb-2">Quản lý bài viết</h2>
                    <p class="italic text-base">Tất cả bài viết trên blog.</p>
                </div>
                <a href="{{ route('admin.posts.create') }}" class="bg-accent py-2 px-4 rounded hover:opacity-90">
                    + Tạo mới
                </a>
            </div>
        </div>

        <x-misc.success-message />
        <x-misc.error-message />

        @if($posts->count() > 0)
            <x-cards.card class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-accent">
                                <th class="text-left py-3 px-2">Tiêu đề</th>
                                <th class="text-left py-3 px-2">Trạng thái</th>
                                <th class="text-left py-3 px-2">Ngày xuất bản</th>
                                <th class="text-right py-3 px-2">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr class="border-b border-accent/30 hover:bg-accent/5">
                                    <td class="py-3 px-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-accent hover:underline" target="_blank">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-2">
                                        @if($post->status === \App\Models\Post::STATUS['PUBLISHED'])
                                            <span class="inline-block px-2 py-1 text-xs bg-green-500/20 text-green-500 rounded">
                                                Đã xuất bản
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs bg-gray-500/20 text-gray-500 rounded">
                                                Nháp
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-2">
                                        {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-blue-500 hover:underline text-sm">
                                                Sửa
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline text-sm">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-cards.card>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @else
            <x-cards.card class="p-6">
                <div class="text-center py-12">
                    <p class="text-lg text-gray-400 mb-4">Chưa có bài viết nào.</p>
                    <a href="{{ route('admin.posts.create') }}" class="bg-accent py-2 px-4 rounded hover:opacity-90">
                        Tạo bài viết đầu tiên
                    </a>
                </div>
            </x-cards.card>
        @endif
    </div>
</x-layouts.master>
