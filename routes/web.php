<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/posts', function () {
    return view('posts.index');
})->name('posts.index');

Route::get('/posts/{slug}', function ($slug) {
    return view('posts.show', ['slug' => $slug]);
})->name('posts.show');

Route::get('/tags', function () {
    return view('tags.index');
})->name('tags.index');

Route::get('/tags/{slug}', function ($slug) {
    return view('tags.show', ['slug' => $slug]);
})->name('tags.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/search', function () {
    return view('search');
})->name('search');

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
});

Route::get('/tool-pdf', function () {
    return view('tool-pdf');
});