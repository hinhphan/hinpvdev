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
                    <div class="relative border-2 border-dashed border-accent rounded-lg p-4 hover:border-accent/70 transition-colors" id="thumbnail-dropzone">
                        <input 
                            type="file" 
                            name="thumbnail" 
                            id="thumbnail" 
                            accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        />
                        <div class="text-center pointer-events-none">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-400">
                                <span class="font-semibold">Click để chọn</span> hoặc kéo thả ảnh vào đây
                            </p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF tối đa 10MB</p>
                        </div>
                    </div>
                    <div id="thumbnail-preview" class="hidden mt-2">
                        <img src="" alt="Preview" class="w-48 h-auto border border-accent rounded" />
                        <button type="button" id="thumbnail-remove" class="mt-2 text-sm text-red-500 hover:text-red-700">Xóa ảnh</button>
                    </div>
                    <small class="text-sm text-gray-400">Ảnh thumbnail hiển thị trong danh sách bài viết</small>
                </div>

                {{-- Tags --}}
                <div class="flex flex-col gap-y-2 mb-4">
                    <x-labels.label for="tags-input">Tags</x-labels.label>
                    <div class="border border-accent p-2 rounded min-h-[42px] cursor-text" id="tags-container">
                        <div class="flex flex-wrap gap-2 items-center" id="tags-display">
                            <!-- Tags will be displayed here -->
                        </div>
                        <input 
                            type="text" 
                            id="tags-input" 
                            class="border-0 outline-none bg-transparent w-full mt-1"
                            placeholder="Nhập tag và nhấn Enter..." 
                            autocomplete="off"
                        />
                        <input type="hidden" name="tags" id="tags-hidden" value="{{ old('tags') }}">
                    </div>
                    <!-- Suggestions dropdown -->
                    <div id="tags-suggestions" class="hidden border border-accent rounded mt-1 bg-card max-h-48 overflow-y-auto">
                        <!-- Suggestions will be displayed here -->
                    </div>
                    <small class="text-sm text-gray-400">Nhập và nhấn Enter hoặc dấu phẩy để thêm tag</small>
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
                        <x-labels.label for="meta-keywords-input">Meta Keywords</x-labels.label>
                        <div class="border border-accent p-2 rounded min-h-[42px] cursor-text" id="meta-keywords-container">
                            <div class="flex flex-wrap gap-2 items-center" id="meta-keywords-display">
                                <!-- Keywords will be displayed here -->
                            </div>
                            <input 
                                type="text" 
                                id="meta-keywords-input" 
                                class="border-0 outline-none bg-transparent w-full mt-1"
                                placeholder="Nhập keyword và nhấn Enter..." 
                                autocomplete="off"
                            />
                            <input type="hidden" name="meta_keywords" id="meta-keywords-hidden" value="{{ old('meta_keywords') }}">
                        </div>
                        <small class="text-sm text-gray-400">Nhập và nhấn Enter hoặc dấu phẩy để thêm keyword</small>
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
                        <div class="relative border-2 border-dashed border-accent rounded-lg p-4 hover:border-accent/70 transition-colors" id="og-image-dropzone">
                            <input 
                                type="file" 
                                name="og_image" 
                                id="og_image" 
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            />
                            <div class="text-center pointer-events-none">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-400">
                                    <span class="font-semibold">Click để chọn</span> hoặc kéo thả ảnh vào đây
                                </p>
                                <p class="text-xs text-gray-500 mt-1">1200x630px khuyến nghị</p>
                            </div>
                        </div>
                        <div id="og-image-preview" class="hidden mt-2">
                            <img src="" alt="Preview" class="w-64 h-auto border border-accent rounded" />
                            <button type="button" id="og-image-remove" class="mt-2 text-sm text-red-500 hover:text-red-700">Xóa ảnh</button>
                        </div>
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

            @push('styles')
            {{-- Font Awesome 4.7 for EasyMDE icons (compatible version) --}}
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            {{-- EasyMDE Markdown editor (CDN) --}}
            <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
            <style>
                /* Match site theme */
                .EasyMDEContainer .CodeMirror,
                .EasyMDEContainer .editor-toolbar {
                    background: var(--color-card);
                    color: var(--color-text-base);
                    border-color: var(--color-border);
                }
                .EasyMDEContainer .editor-toolbar a,
                .EasyMDEContainer .editor-toolbar button {
                    color: var(--color-text-base) !important;
                    opacity: 0.95;
                    font-size: 16px;
                    border-radius: 6px;
                    transition: background-color .2s ease, color .2s ease, opacity .2s ease;
                }
                /* Hover: subtle contrast background so icons don't disappear */
                .EasyMDEContainer .editor-toolbar a:hover,
                .EasyMDEContainer .editor-toolbar button:hover {
                    background-color: var(--color-accent) !important;
                    color: #ffffff !important;
                    opacity: 1;
                }
                /* Ensure icon glyphs inside hover buttons are also white */
                .EasyMDEContainer .editor-toolbar a:hover i,
                .EasyMDEContainer .editor-toolbar button:hover i {
                    color: #ffffff !important;
                }
                /* Active: solid accent background with white icon for strong contrast */
                .EasyMDEContainer .editor-toolbar a.active,
                .EasyMDEContainer .editor-toolbar button.active,
                .EasyMDEContainer .editor-toolbar a:active,
                .EasyMDEContainer .editor-toolbar button:active {
                    background-color: var(--color-accent) !important;
                    color: #ffffff !important;
                    opacity: 1;
                }
                /* Ensure icon glyphs inside active buttons are also white */
                .EasyMDEContainer .editor-toolbar a.active i,
                .EasyMDEContainer .editor-toolbar button.active i {
                    color: #ffffff !important;
                }
                /* Keyboard focus: visible outline */
                .EasyMDEContainer .editor-toolbar a:focus-visible {
                    outline: 2px solid var(--color-accent);
                    outline-offset: 2px;
                }
                .EasyMDEContainer .editor-toolbar i.separator {
                    border-color: var(--color-border);
                    opacity: .6;
                }
                .EasyMDEContainer .CodeMirror {
                    min-height: 420px;
                }
                /* Hide horizontal scrollbar and prevent overflow */
                .EasyMDEContainer .CodeMirror-scroll {
                    overflow-x: hidden !important;
                }
                .EasyMDEContainer .CodeMirror-hscrollbar {
                    display: none !important;
                }
                /* Ensure FA icons have expected size even with FA4 (auto-loaded) */
                .EasyMDEContainer .editor-toolbar .fa {
                    font-size: 16px;
                    line-height: 1;
                }
                /* Preview uses our prose styles */
                .EasyMDEContainer .editor-preview,
                .EasyMDEContainer .editor-preview-side {
                    padding: 1rem 1.25rem;
                }
                /* Light mode specifics (variables already adapt) */
                html.light .EasyMDEContainer .CodeMirror,
                html.light .EasyMDEContainer .editor-toolbar {
                    background: var(--color-card);
                    color: var(--color-text-base);
                }
            </style>
            @endpush

    @push('scripts')
    <!-- EasyMDE Markdown editor -->
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <script>
        // Initialize EasyMDE on the content textarea
        const contentTextarea = document.getElementById('content');
        const easyMDE = new EasyMDE({
            element: contentTextarea,
            spellChecker: false,
            status: false,
            forceSync: true,
            // Font Awesome 4.7 loaded manually above for reliability
            autoDownloadFontAwesome: false,
            autosave: {
                enabled: true,
                uniqueId: 'post-create-new',
                delay: 1000,
            },
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image', 'table', 'code', '|',
                'horizontal-rule', 'clean-block', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                {
                    name: 'guide',
                    action: 'https://www.markdownguide.org/cheat-sheet/',
                    className: 'fa fa-question-circle',
                    title: 'Markdown Guide'
                }
            ],
            previewClass: ['prose', 'max-w-none'],
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            }
        });

        // Ensure textarea has latest value on submit
        const formEl = contentTextarea.closest('form');
        formEl.addEventListener('submit', () => {
            contentTextarea.value = easyMDE.value();
        });
    </script>
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

        // Image drag & drop with preview
        function setupImageDropzone(dropzoneId, inputId, previewId, removeButtonId) {
            const dropzone = document.getElementById(dropzoneId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const removeButton = document.getElementById(removeButtonId);
            const previewImg = preview.querySelector('img');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight dropzone when dragging over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('border-accent', 'bg-accent/5');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('border-accent', 'bg-accent/5');
                }, false);
            });

            // Handle dropped files
            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    input.files = files;
                    handleFiles(files);
                }
            }, false);

            // Handle file selection via input
            input.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });

            function handleFiles(files) {
                if (files.length === 0) return;
                
                const file = files[0];
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Vui lòng chọn file ảnh (PNG, JPG, GIF)');
                    return;
                }

                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Kích thước ảnh không được vượt quá 10MB');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    dropzone.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }

            // Remove image
            removeButton.addEventListener('click', () => {
                input.value = '';
                previewImg.src = '';
                preview.classList.add('hidden');
                dropzone.classList.remove('hidden');
            });
        }

        // Setup both image inputs
        setupImageDropzone('thumbnail-dropzone', 'thumbnail', 'thumbnail-preview', 'thumbnail-remove');
        setupImageDropzone('og-image-dropzone', 'og_image', 'og-image-preview', 'og-image-remove');

        // Tags input with autocomplete
        const tagsInput = document.getElementById('tags-input');
        const tagsDisplay = document.getElementById('tags-display');
        const tagsHidden = document.getElementById('tags-hidden');
        const tagsContainer = document.getElementById('tags-container');
        const tagsSuggestions = document.getElementById('tags-suggestions');
        let tags = [];
        let availableTags = [];

        // Fetch available tags from server
        async function fetchAvailableTags() {
            try {
                const response = await fetch('/api/tags');
                if (response.ok) {
                    availableTags = await response.json();
                }
            } catch (error) {
                console.error('Error fetching tags:', error);
            }
        }

        // Initialize tags from old input
        if (tagsHidden.value) {
            tags = tagsHidden.value.split(',').map(t => t.trim()).filter(t => t);
            renderTags();
        }

        // Focus input when clicking container
        tagsContainer.addEventListener('click', () => {
            tagsInput.focus();
        });

        // Handle input
        tagsInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            
            if (query.length > 0) {
                // Filter suggestions
                const suggestions = availableTags.filter(tag => 
                    tag.toLowerCase().includes(query) && !tags.includes(tag)
                );
                
                if (suggestions.length > 0) {
                    renderSuggestions(suggestions);
                    tagsSuggestions.classList.remove('hidden');
                } else {
                    tagsSuggestions.classList.add('hidden');
                }
            } else {
                tagsSuggestions.classList.add('hidden');
            }
        });

        // Handle keydown
        tagsInput.addEventListener('keydown', (e) => {
            const value = e.target.value.trim();
            
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                if (value) {
                    addTag(value);
                    tagsInput.value = '';
                    tagsSuggestions.classList.add('hidden');
                }
            } else if (e.key === 'Backspace' && !value && tags.length > 0) {
                removeTag(tags.length - 1);
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!tagsContainer.contains(e.target) && !tagsSuggestions.contains(e.target)) {
                tagsSuggestions.classList.add('hidden');
            }
        });

        function addTag(tag) {
            tag = tag.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
                updateHiddenInput();
            }
        }

        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
            updateHiddenInput();
        }

        function renderTags() {
            tagsDisplay.innerHTML = tags.map((tag, index) => `
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-accent/20 text-accent rounded-full text-sm">
                    ${tag}
                    <button type="button" onclick="removeTagByIndex(${index})" class="hover:text-accent/70">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            `).join('');
        }

        function renderSuggestions(suggestions) {
            tagsSuggestions.innerHTML = suggestions.map(tag => `
                <div class="px-4 py-2 hover:bg-accent/10 cursor-pointer" onclick="selectSuggestion('${tag}')">
                    ${tag}
                </div>
            `).join('');
        }

        function updateHiddenInput() {
            tagsHidden.value = tags.join(', ');
        }

        // Global functions for inline handlers
        window.removeTagByIndex = removeTag;
        window.selectSuggestion = (tag) => {
            addTag(tag);
            tagsInput.value = '';
            tagsSuggestions.classList.add('hidden');
            tagsInput.focus();
        };

        // Fetch tags on load
        fetchAvailableTags();

        // Meta Keywords input (same functionality as tags)
        const metaKeywordsInput = document.getElementById('meta-keywords-input');
        const metaKeywordsDisplay = document.getElementById('meta-keywords-display');
        const metaKeywordsHidden = document.getElementById('meta-keywords-hidden');
        const metaKeywordsContainer = document.getElementById('meta-keywords-container');
        let metaKeywords = [];

        // Initialize keywords from old input
        if (metaKeywordsHidden.value) {
            metaKeywords = metaKeywordsHidden.value.split(',').map(k => k.trim()).filter(k => k);
            renderMetaKeywords();
        }

        // Focus input when clicking container
        metaKeywordsContainer.addEventListener('click', () => {
            metaKeywordsInput.focus();
        });

        // Handle keydown
        metaKeywordsInput.addEventListener('keydown', (e) => {
            const value = e.target.value.trim();
            
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                if (value) {
                    addMetaKeyword(value);
                    metaKeywordsInput.value = '';
                }
            } else if (e.key === 'Backspace' && !value && metaKeywords.length > 0) {
                removeMetaKeyword(metaKeywords.length - 1);
            }
        });

        function addMetaKeyword(keyword) {
            keyword = keyword.toLowerCase();
            if (keyword && !metaKeywords.includes(keyword)) {
                metaKeywords.push(keyword);
                renderMetaKeywords();
                updateMetaKeywordsHidden();
            }
        }

        function removeMetaKeyword(index) {
            metaKeywords.splice(index, 1);
            renderMetaKeywords();
            updateMetaKeywordsHidden();
        }

        function renderMetaKeywords() {
            metaKeywordsDisplay.innerHTML = metaKeywords.map((keyword, index) => `
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-accent/20 text-accent rounded-full text-sm">
                    ${keyword}
                    <button type="button" onclick="removeMetaKeywordByIndex(${index})" class="hover:text-accent/70">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            `).join('');
        }

        function updateMetaKeywordsHidden() {
            metaKeywordsHidden.value = metaKeywords.join(', ');
        }

        window.removeMetaKeywordByIndex = removeMetaKeyword;
    </script>
    @endpush
</x-layouts.master>
