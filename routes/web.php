<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/posts', function () {
    return view('posts.index');
});

Route::get('/posts/{slug}', function ($slug) {
    return view('posts.show', ['slug' => $slug]);
});