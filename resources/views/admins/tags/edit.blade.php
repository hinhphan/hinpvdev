<x-layouts.master>
    <x-slot:title>
        Chỉnh sửa Tag
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Quản lý Tags', 'url' => route('admin.tags.index')],
                ['title' => 'Chỉnh sửa Tag', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Chỉnh sửa Tag</h2>
            <p class="italic text-base">Cập nhật thông tin tag: <strong>{{ $tag->name }}</strong></p>
        </div>

        <x-cards.card class="p-6 max-w-2xl">
            <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-misc.error-message />

                {{-- Thông tin sử dụng --}}
                @if($tag->posts_count > 0)
                    <div class="mb-6 p-4 bg-accent/10 border border-accent/30 rounded">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">Thông tin sử dụng</span>
                        </div>
                        <p class="text-sm">
                            Tag này đang được sử dụng trong <strong>{{ $tag->posts_count }}</strong> bài viết.
                            <a href="{{ route('tags.show', $tag->slug) }}" class="text-accent hover:underline ml-2" target="_blank">
                                Xem các bài viết →
                            </a>
                        </p>
                    </div>
                @endif

                {{-- Tên Tag --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="name">Tên Tag <span class="text-red-500">*</span></x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $tag->name) }}" 
                        placeholder="Ví dụ: Laravel, PHP, Web Development" 
                        required
                    />
                    <small class="text-sm text-gray-400">Tên hiển thị của tag</small>
                </div>

                {{-- Slug --}}
                <div class="flex flex-col gap-y-2 mb-6">
                    <x-labels.label for="slug">Slug <span class="text-red-500">*</span></x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="slug" 
                        id="slug" 
                        value="{{ old('slug', $tag->slug) }}" 
                        placeholder="laravel-php-web-development" 
                        required
                    />
                    <small class="text-sm text-gray-400">URL-friendly version (thay đổi slug sẽ ảnh hưởng đến URLs)</small>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4">
                    <x-buttons.primary type="submit">Cập nhật Tag</x-buttons.primary>
                    <a href="{{ route('admin.tags.index') }}" class="py-2 px-4 border border-accent hover:bg-accent/10 rounded">
                        Hủy
                    </a>
                    <form action="{{ route('admin.tags.destroy', $tag->id) }}" 
                          method="POST" 
                          class="ml-auto"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="py-2 px-4 bg-red-600 text-white hover:bg-red-700 rounded"
                                {{ $tag->posts_count > 0 ? 'disabled title="Không thể xóa tag đang có bài viết"' : '' }}>
                            Xóa Tag
                        </button>
                    </form>
                </div>
            </form>
        </x-cards.card>
    </div>

    @push('scripts')
    <script>
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let manualSlug = true; // On edit, assume manual slug unless changed

        slugInput.addEventListener('input', () => {
            manualSlug = slugInput.value.length > 0;
        });

        nameInput.addEventListener('input', (e) => {
            if (!manualSlug) {
                const slug = e.target.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
                    .replace(/đ/g, 'd')
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special chars
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(/-+/g, '-') // Replace multiple - with single -
                    .replace(/^-+|-+$/g, ''); // Remove leading/trailing -
                
                slugInput.value = slug;
            }
        });
    </script>
    @endpush
</x-layouts.master>
