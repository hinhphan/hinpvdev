<x-layouts.master>
    <x-slot:title>
        Tạo Tag Mới
    </x-slot:title>

    <div class="px-4 pt-8 pb-10 md:pb-[92px]">
        <div class="mb-6">
            <x-misc.breadcrumb :items="[
                ['title' => 'Home', 'url' => route('home')],
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Quản lý Tags', 'url' => route('admin.tags.index')],
                ['title' => 'Tạo Tag Mới', 'url' => '#']
            ]" />

            <h2 class="font-semibold text-2xl md:text-3xl mb-2">Tạo Tag Mới</h2>
            <p class="italic text-base">Thêm tag mới cho hệ thống</p>
        </div>

        <x-cards.card class="p-6 max-w-2xl">
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                <x-misc.error-message />

                {{-- Tên Tag --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="name">Tên Tag <span class="text-red-500">*</span></x-labels.label>
                    <x-inputs.input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
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
                        value="{{ old('slug') }}" 
                        placeholder="laravel-php-web-development" 
                        required
                    />
                    <small class="text-sm text-gray-400">URL-friendly version (tự động tạo từ tên tag)</small>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4">
                    <x-buttons.primary type="submit">Tạo Tag</x-buttons.primary>
                    <a href="{{ route('admin.tags.index') }}" class="py-2 px-4 border border-accent hover:bg-accent/10 rounded">
                        Hủy
                    </a>
                </div>
            </form>
        </x-cards.card>
    </div>

    @push('scripts')
    <script>
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let manualSlug = false;

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
