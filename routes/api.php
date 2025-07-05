<?php
use App\Http\Controllers\Api\Tool\PlaceholdImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Tools
    // Placehold Image
    Route::post('placehold-images', [PlaceholdImageController::class, 'create']);

    // Opensource Audio

    // Opensource Image

    Route::middleware(['auth:api'])->group(function () {
        
    });
});
