<?php
use App\Http\Controllers\Api\Tool\PlaceholdImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Tools
    Route::prefix('tools')->group(function () {
        // Placehold Image
        
        // Opensource Audio

        // Opensource Image
    });

    Route::middleware(['auth:api'])->group(function () {
        
    });
});
