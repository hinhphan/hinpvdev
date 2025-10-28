<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of all tags.
     */
    public function index()
    {
        $tags = Tag::withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->having('posts_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        return view('tags.index', compact('tags'));
    }

    /**
     * Display posts for a specific tag.
     */
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $posts = Post::published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('tags.show', compact('tag', 'posts'));
    }
}
