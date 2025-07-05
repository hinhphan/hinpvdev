<?php

use App\Http\Controllers\Api\Tool\PlaceholdImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Shortlink
// Placehold Image
Route::get('pi', [PlaceholdImageController::class, 'preview'])->name('get.placehold.image.preview');
