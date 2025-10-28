<?php

return [
    // Navigation
    'nav' => [
        'home' => 'Home',
        'about' => 'About',
        'posts' => 'Posts',
        'tags' => 'Tags',
        'search' => 'Search',
        'login' => 'Login',
        'logout' => 'Logout',
        'dashboard' => 'Dashboard',
    ],

    // Home Page
    'home' => [
        'title' => 'Welcome to my blog',
        'subtitle' => 'Sharing knowledge, experience and passion for programming',
        'latest_posts' => 'Latest Posts',
        'recent_posts' => 'Recent Posts',
        'featured' => 'Featured',
        'view_all_posts' => 'View all posts',
        'all_posts' => 'All Posts',
        'read_more' => 'Read more',
        'no_posts' => 'No posts yet.',
        'no_recent_posts' => 'No recent posts yet.',
    ],

    // About Page
    'about' => [
        'title' => 'About Me',
        'subtitle' => 'Get to know me better',
    ],

    // Posts Page
    'posts' => [
        'title' => 'Posts',
        'subtitle' => 'Explore articles and tutorials',
        'published_on' => 'Published on',
        'updated_on' => 'Updated on',
        'read_time' => 'minutes read',
        'share' => 'Share this post on:',
        'related_posts' => 'Related posts',
        'back_to_posts' => 'Back to list',
        'back_to_top' => 'Back to top',
        'no_posts_found' => 'No posts found.',
    ],

    // Tags Page
    'tags' => [
        'title' => 'Tags',
        'subtitle' => 'Browse by topic',
        'posts_count' => ':count posts',
        'tagged_with' => 'Posts tagged with',
        'posts_with_tag' => 'All articles with the tag ":tag"',
        'all_tags' => 'All tags',
        'no_tags' => 'No tags used yet.',
        'no_posts_with_tag' => 'No posts with this tag yet.',
    ],

    // Search Page
    'search' => [
        'title' => 'Search',
        'subtitle' => 'Search articles',
        'placeholder' => 'Search for articles...',
        'button' => 'Search',
        'no_results' => 'No results found.',
        'enter_query' => 'Enter a search query to find articles.',
        'results_count' => 'Found :count result(s)',
    ],

    // Auth
    'auth' => [
        'login' => 'Login',
        'email' => 'Email',
        'email_placeholder' => 'Enter your email',
        'password' => 'Password',
        'password_placeholder' => 'Enter your password',
        'remember_me' => 'Remember me',
        'login_button' => 'Login',
        'logout' => 'Logout',
        'welcome_back' => 'Welcome back',
        'login_to_continue' => 'Login to continue to dashboard',
    ],

    // Admin Dashboard
    'admin' => [
        'dashboard' => 'Dashboard',
        'manage_content' => 'Manage your blog content',
        'manage_posts' => 'Manage Posts',
        'create_new_post' => '+ Create new post',
        'posts_list' => 'Posts list',
        'manage_tags' => 'Manage tags',
        'account' => 'Account',
        
        // Posts Management
        'posts' => [
            'title' => 'Manage Posts',
            'subtitle' => 'All your blog posts',
            'create' => 'Create New Post',
            'create_subtitle' => 'Write a new blog post',
            'edit' => 'Edit Post',
            'edit_subtitle' => 'Update post information',
            'post_title' => 'Post Title',
            'slug' => 'Slug',
            'status' => 'Status',
            'published_date' => 'Published Date',
            'actions' => 'Actions',
            'edit_button' => 'Edit',
            'delete_button' => 'Delete',
            'draft' => 'Draft',
            'published' => 'Published',
            'no_posts' => 'No posts yet.',
            'create_first' => 'Create your first post',
            'success_created' => 'Post created successfully!',
            'success_updated' => 'Post updated successfully!',
            'success_deleted' => 'Post deleted successfully!',
            'confirm_delete' => 'Are you sure you want to delete this post?',
        ],

        // Post Form
        'post_form' => [
            'title' => 'Post Title',
            'title_placeholder' => 'Enter post title',
            'slug' => 'Slug',
            'slug_placeholder' => 'my-post-title',
            'slug_help' => 'URL-friendly version of the title (e.g., my-post-title)',
            'excerpt' => 'Short Excerpt',
            'excerpt_placeholder' => 'Brief summary of the post...',
            'excerpt_help' => 'Short description displayed in post lists',
            'content' => 'Content (Markdown)',
            'content_placeholder' => '# Post Title

Write your content in Markdown here...

## Code example
```php
echo \'Hello World\';
```

## Math formula
$$
E = mc^2
$$',
            'content_help' => 'Supports Markdown, code syntax highlighting (Shikijs), and math formulas (KaTeX)',
            'current_thumbnail' => 'Current Thumbnail',
            'thumbnail' => 'Thumbnail',
            'change_thumbnail' => 'Change Thumbnail',
            'thumbnail_help' => 'Thumbnail image displayed in post lists',
            'click_or_drag' => 'Click to select or drag and drop image here',
            'image_format' => 'PNG, JPG, GIF max 10MB',
            'remove_image' => 'Remove image',
            'will_be_removed' => 'Will be removed',
            'undo' => 'Undo',
            'tags' => 'Tags',
            'tags_placeholder' => 'Enter tag and press Enter...',
            'tags_help' => 'Enter and press Enter or comma to add tag',
            'seo_meta' => 'SEO Meta Tags (Optional)',
            'meta_title' => 'Meta Title',
            'meta_title_placeholder' => 'SEO title (defaults to post title)',
            'meta_description' => 'Meta Description',
            'meta_description_placeholder' => 'Description for search engines...',
            'meta_keywords' => 'Meta Keywords',
            'meta_keywords_placeholder' => 'Enter keyword and press Enter...',
            'meta_keywords_help' => 'Enter and press Enter or comma to add keyword',
            'canonical_url' => 'Canonical URL',
            'canonical_url_placeholder' => 'https://blog.hinpv.dev/posts/my-post',
            'og_image' => 'OG Image',
            'change_og_image' => 'Change OG Image',
            'current_og_image' => 'Current OG Image',
            'og_image_help' => 'Image displayed when shared on social media (1200x630px recommended)',
            'og_image_size' => '1200x630px recommended',
            'status_label' => 'Status',
            'status_draft' => 'Draft',
            'status_published' => 'Published',
            'published_at' => 'Published Date',
            'published_at_help' => 'Leave empty to use current time',
            'create_button' => 'Create Post',
            'update_button' => 'Update Post',
            'cancel_button' => 'Cancel',
            'delete_post_button' => 'Delete Post',
            'required' => 'Required',
        ],

        // Tags Management
        'tags' => [
            'title' => 'Manage Tags',
            'subtitle' => 'Manage tags for posts',
            'create' => 'Create New Tag',
            'create_subtitle' => 'Add new tag to system',
            'edit' => 'Edit Tag',
            'edit_subtitle' => 'Update tag information',
            'name' => 'Tag Name',
            'slug' => 'Slug',
            'posts_count' => 'Posts Count',
            'created_date' => 'Created Date',
            'actions' => 'Actions',
            'edit_button' => 'Edit',
            'delete_button' => 'Delete',
            'no_tags' => 'No tags yet.',
            'create_first' => 'Create your first tag',
            'success_created' => 'Tag created successfully!',
            'success_updated' => 'Tag updated successfully!',
            'success_deleted' => 'Tag deleted successfully!',
            'confirm_delete' => 'Are you sure you want to delete this tag?',
            'cannot_delete' => 'Cannot delete this tag because it is being used in {count} posts.',
            'cannot_delete_tooltip' => 'Cannot delete tag with posts',
            'usage_info' => 'Usage Information',
            'used_in_posts' => 'This tag is being used in <strong>{count}</strong> posts.',
            'view_posts' => 'View posts',
            'slug_change_warning' => 'URL-friendly version (changing slug will affect URLs)',
        ],

        // Tag Form
        'tag_form' => [
            'name' => 'Tag Name',
            'name_placeholder' => 'e.g., Laravel, PHP, Web Development',
            'name_help' => 'Display name of the tag',
            'slug' => 'Slug',
            'slug_placeholder' => 'laravel-php-web-development',
            'slug_help' => 'URL-friendly version (auto-generated from tag name)',
            'create_button' => 'Create Tag',
            'update_button' => 'Update Tag',
            'cancel_button' => 'Cancel',
            'delete_button' => 'Delete Tag',
        ],
    ],

    // Common
    'common' => [
        'home' => 'Home',
        'back' => 'Back',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'create' => 'Create',
        'update' => 'Update',
        'actions' => 'Actions',
        'required' => 'Required',
        'optional' => 'Optional',
        'loading' => 'Loading...',
        'error' => 'Error',
        'success' => 'Success',
    ],

    // Footer
    'footer' => [
        'copyright' => '© :year :name. All rights reserved.',
        'made_with' => 'Made with ❤️',
    ],

    // Errors
    'errors' => [
        '404_title' => 'Page Not Found',
        '404_message' => 'Sorry, the page you are looking for could not be found.',
        'back_to_home' => 'Back to Home',
    ],

    // Pagination
    'pagination' => [
        'previous' => 'Previous',
        'next' => 'Next',
        'page' => 'Page',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'results' => 'results',
    ],
];
