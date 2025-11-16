<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoFormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Home & Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// Tags
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tags.show');

// API endpoints
Route::get('/api/tags', [TagController::class, 'apiIndex'])->name('api.tags');

// Auto Forms API with rate limiting
// GET /auto-forms/types: 120 requests per minute (lightweight endpoint)
Route::get('/auto-forms/types', [AutoFormController::class, 'types'])
    ->middleware('throttle:120,1')
    ->name('api.auto-forms.types');

// POST /auto-forms: 60 requests per minute (resource-intensive, may use AI)
Route::post('/auto-forms', [AutoFormController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('api.auto-forms');

// Error Pages
Route::get('/not-found', function () {
    return view('errors.404');
})->name('404');

// Auth
Route::get('/login', [AuthController::class, 'getLogin'])->name('get.login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('post.login');

Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Posts Management
    Route::get('/admin/posts', [AdminController::class, 'indexPosts'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [AdminController::class, 'createPost'])->name('admin.posts.create');
    Route::post('/admin/posts', [AdminController::class, 'storePost'])->name('admin.posts.store');
    Route::get('/admin/posts/{id}/edit', [AdminController::class, 'editPost'])->name('admin.posts.edit');
    Route::put('/admin/posts/{id}', [AdminController::class, 'updatePost'])->name('admin.posts.update');
    Route::delete('/admin/posts/{id}', [AdminController::class, 'destroyPost'])->name('admin.posts.destroy');
    
    // Tags Management
    Route::get('/admin/tags', [AdminController::class, 'indexTags'])->name('admin.tags.index');
    Route::get('/admin/tags/create', [AdminController::class, 'createTag'])->name('admin.tags.create');
    Route::post('/admin/tags', [AdminController::class, 'storeTag'])->name('admin.tags.store');
    Route::get('/admin/tags/{id}/edit', [AdminController::class, 'editTag'])->name('admin.tags.edit');
    Route::put('/admin/tags/{id}', [AdminController::class, 'updateTag'])->name('admin.tags.update');
    Route::delete('/admin/tags/{id}', [AdminController::class, 'destroyTag'])->name('admin.tags.destroy');
});

Route::get('/tool-pdf', function () {
    return view('tool-pdf');
});