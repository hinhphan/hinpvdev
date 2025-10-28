<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use App\Models\Tag;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admins.dashboard');
    }

    public function createPost()
    {
        return view('admins.posts.create');
    }

    public function storePost(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:5120', // 5MB max
            'tags' => 'nullable|string',
            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_image' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Handle thumbnail upload
            $thumbnailId = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $path = $thumbnail->store('thumbnails', 'public');
                
                $thumbnailFile = File::create([
                    'filename' => basename($path),
                    'path' => $path,
                    'mime' => $thumbnail->getMimeType(),
                    'size' => $thumbnail->getSize(),
                    'original_name' => $thumbnail->getClientOriginalName(),
                    'disk' => 'public',
                ]);
                $thumbnailId = $thumbnailFile->id;
            }

            // Create post
            $post = Post::create([
                'slug' => $validated['slug'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'thumbnail_id' => $thumbnailId,
                'status' => $validated['status'],
                'published_at' => $validated['published_at'] ?? ($validated['status'] == 1 ? now() : null),
            ]);

            // Handle tags
            if (!empty($validated['tags'])) {
                $tagNames = array_map('trim', explode(',', $validated['tags']));
                foreach ($tagNames as $tagName) {
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate([
                            'slug' => \Illuminate\Support\Str::slug($tagName),
                        ], [
                            'name' => $tagName,
                        ]);
                        
                        // Attach tag to post
                        DB::table('post_tags')->insert([
                            'post_id' => $post->id,
                            'tag_id' => $tag->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Handle OG image upload
            $ogImageId = null;
            if ($request->hasFile('og_image')) {
                $ogImage = $request->file('og_image');
                $path = $ogImage->store('og-images', 'public');
                
                $ogImageFile = File::create([
                    'filename' => basename($path),
                    'path' => $path,
                    'mime' => $ogImage->getMimeType(),
                    'size' => $ogImage->getSize(),
                    'original_name' => $ogImage->getClientOriginalName(),
                    'disk' => 'public',
                ]);
                $ogImageId = $ogImageFile->id;
            }

            // Create SEO meta
            SeoMeta::create([
                'post_id' => $post->id,
                'meta_title' => $validated['meta_title'] ?? $validated['title'],
                'meta_description' => $validated['meta_description'] ?? $validated['excerpt'],
                'meta_keywords' => $validated['meta_keywords'],
                'canonical_url' => $validated['canonical_url'],
                'og_image_id' => $ogImageId,
            ]);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Bài viết đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function indexPosts()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(15);
        return view('admins.posts.index', compact('posts'));
    }

    public function editPost($id)
    {
        $post = Post::with(['tags'])->findOrFail($id);
        return view('admins.posts.edit', compact('post'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:5120',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_image' => 'nullable|image|max:5120',
            'remove_thumbnail' => 'nullable|in:0,1',
            'remove_og_image' => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            // Handle thumbnail removal
            if ($request->input('remove_thumbnail') == '1' && $post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail->path);
                $post->thumbnail->delete();
                $validated['thumbnail_id'] = null;
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $path = $thumbnail->store('thumbnails', 'public');
                
                $thumbnailFile = File::create([
                    'filename' => basename($path),
                    'path' => $path,
                    'mime' => $thumbnail->getMimeType(),
                    'size' => $thumbnail->getSize(),
                    'original_name' => $thumbnail->getClientOriginalName(),
                    'disk' => 'public',
                ]);
                $validated['thumbnail_id'] = $thumbnailFile->id;
            }

            // Update post
            $post->update([
                'slug' => $validated['slug'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'thumbnail_id' => $validated['thumbnail_id'] ?? $post->thumbnail_id,
                'status' => $validated['status'],
                'published_at' => $validated['published_at'] ?? ($validated['status'] == 1 ? now() : null),
            ]);

            // Sync tags
            if (isset($validated['tags'])) {
                $tagIds = [];
                $tagNames = array_map('trim', explode(',', $validated['tags']));
                foreach ($tagNames as $tagName) {
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate([
                            'slug' => \Illuminate\Support\Str::slug($tagName),
                        ], [
                            'name' => $tagName,
                        ]);
                        $tagIds[] = $tag->id;
                    }
                }
                DB::table('post_tags')->where('post_id', $post->id)->delete();
                foreach ($tagIds as $tagId) {
                    DB::table('post_tags')->insert([
                        'post_id' => $post->id,
                        'tag_id' => $tagId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update or create SEO meta
            $seoData = [
                'meta_title' => $validated['meta_title'] ?? $validated['title'],
                'meta_description' => $validated['meta_description'] ?? $validated['excerpt'],
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'canonical_url' => $validated['canonical_url'] ?? null,
            ];

            // Handle OG image removal
            if ($request->input('remove_og_image') == '1' && $post->seoMeta && $post->seoMeta->ogImage) {
                Storage::disk('public')->delete($post->seoMeta->ogImage->path);
                $post->seoMeta->ogImage->delete();
                $seoData['og_image_id'] = null;
            }

            if ($request->hasFile('og_image')) {
                $ogImage = $request->file('og_image');
                $path = $ogImage->store('og-images', 'public');
                
                $ogImageFile = File::create([
                    'filename' => basename($path),
                    'path' => $path,
                    'mime' => $ogImage->getMimeType(),
                    'size' => $ogImage->getSize(),
                    'original_name' => $ogImage->getClientOriginalName(),
                    'disk' => 'public',
                ]);
                $seoData['og_image_id'] = $ogImageFile->id;
            }

            SeoMeta::updateOrCreate(
                ['post_id' => $post->id],
                $seoData
            );

            DB::commit();

            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài viết đã được cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function destroyPost($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài viết đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // Tags Management
    public function indexTags()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('name')
            ->paginate(20);
        
        return view('admins.tags.index', compact('tags'));
    }

    public function createTag()
    {
        return view('admins.tags.create');
    }

    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
        ]);

        try {
            Tag::create($validated);

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag đã được tạo thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function editTag($id)
    {
        $tag = Tag::withCount('posts')->findOrFail($id);
        return view('admins.tags.edit', compact('tag'));
    }

    public function updateTag(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $id,
        ]);

        try {
            $tag->update($validated);

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function destroyTag($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            
            // Check if tag has posts
            if ($tag->posts()->count() > 0) {
                return back()
                    ->withErrors(['error' => 'Không thể xóa tag này vì đang được sử dụng trong ' . $tag->posts()->count() . ' bài viết.']);
            }
            
            $tag->delete();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
}

