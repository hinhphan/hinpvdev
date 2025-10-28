<?php

return [
    // Navigation
    'nav' => [
        'home' => 'Trang chủ',
        'about' => 'Giới thiệu',
        'posts' => 'Bài viết',
        'tags' => 'Thẻ',
        'search' => 'Tìm kiếm',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'dashboard' => 'Bảng điều khiển',
    ],

    // Home Page
    'home' => [
        'title' => 'Chào mừng đến với blog của tôi',
        'subtitle' => 'Chia sẻ kiến thức, kinh nghiệm và đam mê lập trình',
        'latest_posts' => 'Bài viết mới nhất',
        'recent_posts' => 'Bài viết gần đây',
        'featured' => 'Nổi bật',
        'view_all_posts' => 'Xem tất cả bài viết',
        'all_posts' => 'Tất cả bài viết',
        'read_more' => 'Đọc thêm',
        'no_posts' => 'Chưa có bài viết nào.',
        'no_recent_posts' => 'Chưa có bài viết gần đây.',
    ],

    // About Page
    'about' => [
        'title' => 'Giới thiệu',
        'subtitle' => 'Tìm hiểu thêm về tôi',
    ],

    // Posts Page
    'posts' => [
        'title' => 'Bài viết',
        'subtitle' => 'Khám phá các bài viết và hướng dẫn',
        'published_on' => 'Xuất bản ngày',
        'updated_on' => 'Cập nhật ngày',
        'read_time' => 'phút đọc',
        'share' => 'Chia sẻ bài viết này trên:',
        'related_posts' => 'Bài viết liên quan',
        'back_to_posts' => 'Quay lại danh sách',
        'back_to_top' => 'Lên đầu trang',
        'no_posts_found' => 'Không tìm thấy bài viết nào.',
    ],

    // Tags Page
    'tags' => [
        'title' => 'Thẻ',
        'subtitle' => 'Duyệt theo chủ đề',
        'posts_count' => ':count bài viết',
        'tagged_with' => 'Bài viết với thẻ',
        'posts_with_tag' => 'Tất cả bài viết với thẻ ":tag"',
        'all_tags' => 'Tất cả thẻ',
        'no_tags' => 'Chưa có tag nào được sử dụng.',
        'no_posts_with_tag' => 'Chưa có bài viết nào với tag này.',
    ],

    // Search Page
    'search' => [
        'title' => 'Tìm kiếm',
        'subtitle' => 'Tìm kiếm bài viết',
        'placeholder' => 'Tìm kiếm bài viết...',
        'button' => 'Tìm kiếm',
        'no_results' => 'Không tìm thấy kết quả nào.',
        'enter_query' => 'Nhập từ khóa để tìm kiếm bài viết.',
        'results_count' => 'Tìm thấy :count kết quả',
    ],

    // Auth
    'auth' => [
        'login' => 'Đăng nhập',
        'email' => 'Email',
        'email_placeholder' => 'Nhập email của bạn',
        'password' => 'Mật khẩu',
        'password_placeholder' => 'Nhập mật khẩu của bạn',
        'remember_me' => 'Ghi nhớ đăng nhập',
        'login_button' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'welcome_back' => 'Chào mừng trở lại',
        'login_to_continue' => 'Đăng nhập để tiếp tục vào bảng điều khiển',
    ],

    // Admin Dashboard
    'admin' => [
        'dashboard' => 'Bảng điều khiển',
        'manage_content' => 'Quản lý nội dung blog của bạn',
        'manage_posts' => 'Quản lý bài viết',
        'create_new_post' => '+ Tạo bài viết mới',
        'posts_list' => 'Danh sách bài viết',
        'manage_tags' => 'Quản lý tags',
        'account' => 'Tài khoản',
        
        // Posts Management
        'posts' => [
            'title' => 'Quản lý bài viết',
            'subtitle' => 'Tất cả bài viết của bạn',
            'create' => 'Tạo bài viết mới',
            'create_subtitle' => 'Viết một bài blog mới',
            'edit' => 'Chỉnh sửa bài viết',
            'edit_subtitle' => 'Cập nhật thông tin bài viết',
            'post_title' => 'Tiêu đề',
            'slug' => 'Slug',
            'status' => 'Trạng thái',
            'published_date' => 'Ngày xuất bản',
            'actions' => 'Thao tác',
            'edit_button' => 'Sửa',
            'delete_button' => 'Xóa',
            'draft' => 'Nháp',
            'published' => 'Đã xuất bản',
            'no_posts' => 'Chưa có bài viết nào.',
            'create_first' => 'Tạo bài viết đầu tiên',
            'success_created' => 'Bài viết đã được tạo thành công!',
            'success_updated' => 'Bài viết đã được cập nhật thành công!',
            'success_deleted' => 'Bài viết đã được xóa thành công!',
            'confirm_delete' => 'Bạn có chắc chắn muốn xóa bài viết này?',
        ],

        // Post Form
        'post_form' => [
            'title' => 'Tiêu đề bài viết',
            'title_placeholder' => 'Nhập tiêu đề bài viết',
            'slug' => 'Slug',
            'slug_placeholder' => 'bai-viet-cua-toi',
            'slug_help' => 'URL-friendly version của tiêu đề (vd: my-post-title)',
            'excerpt' => 'Trích dẫn ngắn',
            'excerpt_placeholder' => 'Tóm tắt ngắn gọn về bài viết...',
            'excerpt_help' => 'Mô tả ngắn hiển thị trong danh sách bài viết',
            'content' => 'Nội dung (Markdown)',
            'content_placeholder' => '# Tiêu đề bài viết

Viết nội dung bằng Markdown ở đây...

## Code example
```php
echo \'Hello World\';
```

## Math formula
$$
E = mc^2
$$',
            'content_help' => 'Hỗ trợ Markdown, code syntax highlighting (Shikijs), và công thức toán (KaTeX)',
            'current_thumbnail' => 'Ảnh đại diện hiện tại',
            'thumbnail' => 'Ảnh đại diện',
            'change_thumbnail' => 'Thay đổi ảnh đại diện',
            'thumbnail_help' => 'Ảnh thumbnail hiển thị trong danh sách bài viết',
            'click_or_drag' => 'Click để chọn hoặc kéo thả ảnh vào đây',
            'image_format' => 'PNG, JPG, GIF tối đa 10MB',
            'remove_image' => 'Xóa ảnh',
            'will_be_removed' => 'Sẽ bị xóa',
            'undo' => 'Hoàn tác',
            'tags' => 'Tags',
            'tags_placeholder' => 'Nhập tag và nhấn Enter...',
            'tags_help' => 'Nhập và nhấn Enter hoặc dấu phẩy để thêm tag',
            'seo_meta' => 'SEO Meta Tags (Tùy chọn)',
            'meta_title' => 'Meta Title',
            'meta_title_placeholder' => 'Tiêu đề SEO (mặc định dùng tiêu đề bài viết)',
            'meta_description' => 'Meta Description',
            'meta_description_placeholder' => 'Mô tả cho công cụ tìm kiếm...',
            'meta_keywords' => 'Meta Keywords',
            'meta_keywords_placeholder' => 'Nhập keyword và nhấn Enter...',
            'meta_keywords_help' => 'Nhập và nhấn Enter hoặc dấu phẩy để thêm keyword',
            'canonical_url' => 'Canonical URL',
            'canonical_url_placeholder' => 'https://blog.hinpv.dev/posts/my-post',
            'og_image' => 'OG Image',
            'change_og_image' => 'Thay đổi OG Image',
            'current_og_image' => 'OG Image hiện tại',
            'og_image_help' => 'Ảnh hiển thị khi share trên mạng xã hội (1200x630px khuyến nghị)',
            'og_image_size' => '1200x630px khuyến nghị',
            'status_label' => 'Trạng thái',
            'status_draft' => 'Nháp',
            'status_published' => 'Xuất bản',
            'published_at' => 'Ngày xuất bản',
            'published_at_help' => 'Để trống sẽ dùng thời gian hiện tại',
            'create_button' => 'Tạo bài viết',
            'update_button' => 'Cập nhật bài viết',
            'cancel_button' => 'Hủy',
            'delete_post_button' => 'Xóa bài viết',
            'required' => 'Bắt buộc',
        ],

        // Tags Management
        'tags' => [
            'title' => 'Quản lý Tags',
            'subtitle' => 'Quản lý các tag cho bài viết',
            'create' => 'Tạo Tag Mới',
            'create_subtitle' => 'Thêm tag mới cho hệ thống',
            'edit' => 'Chỉnh sửa Tag',
            'edit_subtitle' => 'Cập nhật thông tin tag',
            'name' => 'Tên Tag',
            'slug' => 'Slug',
            'posts_count' => 'Số bài viết',
            'created_date' => 'Ngày tạo',
            'actions' => 'Thao tác',
            'edit_button' => 'Sửa',
            'delete_button' => 'Xóa',
            'no_tags' => 'Chưa có tag nào.',
            'create_first' => 'Tạo tag đầu tiên',
            'success_created' => 'Tag đã được tạo thành công!',
            'success_updated' => 'Tag đã được cập nhật thành công!',
            'success_deleted' => 'Tag đã được xóa thành công!',
            'confirm_delete' => 'Bạn có chắc chắn muốn xóa tag này?',
            'cannot_delete' => 'Không thể xóa tag này vì đang được sử dụng trong {count} bài viết.',
            'cannot_delete_tooltip' => 'Không thể xóa tag đang có bài viết',
            'usage_info' => 'Thông tin sử dụng',
            'used_in_posts' => 'Tag này đang được sử dụng trong <strong>{count}</strong> bài viết.',
            'view_posts' => 'Xem các bài viết',
            'slug_change_warning' => 'URL-friendly version (thay đổi slug sẽ ảnh hưởng đến URLs)',
        ],

        // Tag Form
        'tag_form' => [
            'name' => 'Tên Tag',
            'name_placeholder' => 'Ví dụ: Laravel, PHP, Web Development',
            'name_help' => 'Tên hiển thị của tag',
            'slug' => 'Slug',
            'slug_placeholder' => 'laravel-php-web-development',
            'slug_help' => 'URL-friendly version (tự động tạo từ tên tag)',
            'create_button' => 'Tạo Tag',
            'update_button' => 'Cập nhật Tag',
            'cancel_button' => 'Hủy',
            'delete_button' => 'Xóa Tag',
        ],
    ],

    // Common
    'common' => [
        'home' => 'Trang chủ',
        'back' => 'Quay lại',
        'save' => 'Lưu',
        'cancel' => 'Hủy',
        'delete' => 'Xóa',
        'edit' => 'Sửa',
        'create' => 'Tạo',
        'update' => 'Cập nhật',
        'actions' => 'Thao tác',
        'required' => 'Bắt buộc',
        'optional' => 'Tùy chọn',
        'loading' => 'Đang tải...',
        'error' => 'Lỗi',
        'success' => 'Thành công',
    ],

    // Footer
    'footer' => [
        'copyright' => '© :year :name. Tất cả quyền được bảo lưu.',
        'made_with' => 'Được tạo với ❤️',
    ],

    // Errors
    'errors' => [
        '404_title' => 'Không tìm thấy trang',
        '404_message' => 'Xin lỗi, trang bạn đang tìm kiếm không tồn tại.',
        'back_to_home' => 'Về trang chủ',
    ],

    // Pagination
    'pagination' => [
        'previous' => 'Trước',
        'next' => 'Sau',
        'page' => 'Trang',
        'showing' => 'Hiển thị',
        'to' => 'đến',
        'of' => 'trong tổng số',
        'results' => 'kết quả',
    ],
];
