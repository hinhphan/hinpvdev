<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured posts (latest 3 published posts)
        $featuredPosts = Post::published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get recent posts (next 5 published posts)
        $recentPosts = Post::published()
            ->orderBy('published_at', 'desc')
            ->skip(3)
            ->limit(5)
            ->get();
        
        return view('home', compact('featuredPosts', 'recentPosts'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the search page.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $posts = Post::published()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%");
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('search', compact('posts', 'query'));
    }
}
