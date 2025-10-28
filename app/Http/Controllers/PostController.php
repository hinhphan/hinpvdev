<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of published posts.
     */
    public function index()
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    /**
     * Display the specified post by slug.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['tags', 'thumbnail', 'seoMeta'])
            ->published()
            ->firstOrFail();
        
        return view('posts.show', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
