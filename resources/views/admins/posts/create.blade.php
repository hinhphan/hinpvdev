<x-layouts.master>
    <x-slot:title>
        Tạo bài viết mới
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Tạo bài viết', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Tạo bài viết mới</h2>
            <p class="italic text-base">Viết một bài blog mới cho website của bạn.</p>
        </div>

        <x-cards.card class="p-6">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-misc.error-message />

                {{-- Tiêu đề --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="title">Tiêu đề bài viết <span class="text-red-500">*</span></x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}" 
                        placeholder="Nhập tiêu đề bài viết" 
                        required
                    />
                </div>

                {{-- Slug --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="slug">Slug <span class="text-red-500">*</span></x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="slug" 
                        id="slug" 
                        value="{{ old('slug') }}" 
                        placeholder="bai-viet-cua-toi" 
                        required
                    />
                    <small class="text-sm text-gray-400">URL-friendly version của tiêu đề (vd: my-post-title)</small>
                </div>

                {{-- Excerpt --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="excerpt">Trích dẫn ngắn</x-labels.label>
                    <textarea 
                        name="excerpt" 
                        id="excerpt" 
                        rows="3"
                        class="border border-accent p-2 accent-accent"
                        placeholder="Tóm tắt ngắn gọn về bài viết..."
                    >{{ old('excerpt') }}</textarea>
                    <small class="text-sm text-gray-400">Mô tả ngắn hiển thị trong danh sách bài viết</small>
                </div>

                {{-- Nội dung Markdown --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="content">Nội dung (Markdown) <span class="text-red-500">*</span></x-labels.label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="20"
                        class="border border-accent p-2 accent-accent font-mono text-sm"
                        placeholder="# Tiêu đề bài viết

Viết nội dung bằng Markdown ở đây...

## Code example
\`\`\`php
echo 'Hello World';
\`\`\`

## Math formula
$$
E = mc^2
$$"
                        required
                    >{{ old('content') }}</textarea>
                    <small class="text-sm text-gray-400">Hỗ trợ Markdown, code syntax highlighting (Shikijs), và công thức toán (KaTeX)</small>
                </div>

                {{-- Thumbnail --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="thumbnail">Ảnh đại diện</x-labels.label>
                    <x-inputs.input 
                        type="file" 
                        name="thumbnail" 
                        id="thumbnail" 
                        accept="image/*"
                        class="p-2"
                    />
                    <small class="text-sm text-gray-400">Ảnh thumbnail hiển thị trong danh sách bài viết</small>
                </div>

                {{-- Tags --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="tags">Tags</x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="tags" 
                        id="tags" 
                        value="{{ old('tags') }}" 
                        placeholder="laravel, php, web-development" 
                    />
                    <small class="text-sm text-gray-400">Các tag cách nhau bởi dấu phẩy</small>
                </div>

                {{-- SEO Meta --}}
                <div class="border border-accent p-4 mb-4">
                    <h3 class="font-semibold text-lg mb-4">SEO Meta Tags (Tùy chọn)</h3>

                    <div class="flex flex-col gap-y-2 mb-4">
                        <x-labels.label for="meta_title">Meta Title</x-labels.label>
                        <x-inputs.input 
                            type="text" 
                            name="meta_title" 
                            id="meta_title" 
                            value="{{ old('meta_title') }}" 
                            placeholder="Tiêu đề SEO (mặc định dùng tiêu đề bài viết)" 
                        />
                    </div>

                    <div class="flex flex-col gap-y-2 mb-4">
                        <x-labels.label for="meta_description">Meta Description</x-labels.label>
                        <textarea 
                            name="meta_description" 
                            id="meta_description" 
                            rows="2"
                            class="border border-accent p-2 accent-accent"
                            placeholder="Mô tả cho công cụ tìm kiếm..."
                        >{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-y-2 mb-4">
                        <x-labels.label for="meta_keywords">Meta Keywords</x-labels.label>
                        <x-inputs.input 
                            type="text" 
                            name="meta_keywords" 
                            id="meta_keywords" 
                            value="{{ old('meta_keywords') }}" 
                            placeholder="keyword1, keyword2, keyword3" 
                        />
                    </div>

                    <div class="flex flex-col gap-y-2 mb-4">
                        <x-labels.label for="canonical_url">Canonical URL</x-labels.label>
                        <x-inputs.input 
                            type="url" 
                            name="canonical_url" 
                            id="canonical_url" 
                            value="{{ old('canonical_url') }}" 
                            placeholder="https://blog.hinpv.dev/posts/my-post" 
                        />
                    </div>

                    <div class="flex flex-col gap-y-2">
                        <x-labels.label for="og_image">OG Image</x-labels.label>
                        <x-inputs.input 
                            type="file" 
                            name="og_image" 
                            id="og_image" 
                            accept="image/*"
                            class="p-2"
                        />
                        <small class="text-sm text-gray-400">Ảnh hiển thị khi share trên mạng xã hội (1200x630px khuyến nghị)</small>
                    </div>
                </div>

                {{-- Status và Publish Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="flex flex-col gap-y-2">
                        <x-labels.label for="status">Trạng thái <span class="text-red-500">*</span></x-labels.label>
                        <select 
                            name="status" 
                            id="status"
                            class="border border-accent p-2 accent-accent"
                            required
                        >
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nháp</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Xuất bản</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-y-2">
                        <x-labels.label for="published_at">Ngày xuất bản</x-labels.label>
                        <x-inputs.input 
                            type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            value="{{ old('published_at') }}" 
                        />
                        <small class="text-sm text-gray-400">Để trống sẽ dùng thời gian hiện tại</small>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4">
                    <x-buttons.primary type="submit">Tạo bài viết</x-buttons.primary>
                    <a href="{{ route('dashboard') }}" class="py-2 px-4 border border-accent hover:bg-accent/10 rounded">
                        Hủy
                    </a>
                </div>
            </form>
        </x-cards.card>
    </div>

    @push('scripts')
    <script>
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        let manualSlug = false;

        slugInput.addEventListener('input', () => {
            manualSlug = slugInput.value.length > 0;
        });

        titleInput.addEventListener('input', (e) => {
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

        // Toggle datetime-local visibility based on status
        const statusSelect = document.getElementById('status');
        const publishedAtGroup = document.getElementById('published_at').closest('.flex');
        
        statusSelect.addEventListener('change', (e) => {
            if (e.target.value === '1') {
                publishedAtGroup.classList.remove('opacity-50');
                document.getElementById('published_at').disabled = false;
            } else {
                publishedAtGroup.classList.add('opacity-50');
                document.getElementById('published_at').disabled = true;
            }
        });

        // Trigger on load
        statusSelect.dispatchEvent(new Event('change'));
    </script>
    @endpush
</x-layouts.master>
