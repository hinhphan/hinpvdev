<x-layouts.master>
    <x-slot:title>
        Quản lý Tags
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <x-misc.breadcrumb :items="[
                    ['title' => 'Home', 'url' => route('home')],
                    ['title' => 'Dashboard', 'url' => route('dashboard')],
                    ['title' => 'Quản lý Tags', 'url' => '#']
                ]" />

                <h2 class="font-semibold text-2xl md:text-3xl mb-2">Quản lý Tags</h2>
                <p class="italic text-base">Quản lý các tag cho bài viết</p>
            </div>
            
            <a href="{{ route('admin.tags.create') }}" class="py-2 px-4 bg-accent text-white hover:bg-accent/90 rounded">
                Tạo Tag Mới
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-500/20 border border-green-500 text-green-500 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/20 border border-red-500 text-red-500 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-cards.card class="p-6">
            @if($tags->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-accent">
                            <tr>
                                <th class="text-left py-3 px-4">Tên Tag</th>
                                <th class="text-left py-3 px-4">Slug</th>
                                <th class="text-center py-3 px-4">Số bài viết</th>
                                <th class="text-center py-3 px-4">Ngày tạo</th>
                                <th class="text-center py-3 px-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tags as $tag)
                                <tr class="border-b border-accent/20 hover:bg-accent/5">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-accent/20 text-accent rounded-full text-sm">
                                            {{ $tag->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-400">{{ $tag->slug }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($tag->posts_count > 0)
                                            <a href="{{ route('tags.show', $tag->slug) }}" class="text-accent hover:underline" target="_blank">
                                                {{ $tag->posts_count }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">0</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center text-gray-400">
                                        {{ $tag->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.tags.edit', $tag->id) }}" 
                                               class="py-1 px-3 bg-accent/20 text-accent hover:bg-accent/30 rounded text-sm">
                                                Sửa
                                            </a>
                                            <form action="{{ route('admin.tags.destroy', $tag->id) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="py-1 px-3 bg-red-600/20 text-red-500 hover:bg-red-600/30 rounded text-sm"
                                                        {{ $tag->posts_count > 0 ? 'disabled title="Không thể xóa tag đang có bài viết"' : '' }}>
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

                <div class="mt-6">
                    {{ $tags->links() }}
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <p class="mb-4">Chưa có tag nào.</p>
                    <a href="{{ route('admin.tags.create') }}" class="text-accent hover:underline">
                        Tạo tag đầu tiên →
                    </a>
                </div>
            @endif
        </x-cards.card>
    </div>
</x-layouts.master>
