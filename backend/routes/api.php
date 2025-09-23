<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Tool\PlaceholdImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Login
    Route::post('login', [AuthController::class, 'login']);
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Tools
    // Placehold Image
    Route::post('placehold-images', [PlaceholdImageController::class, 'create']);
    Route::get('placehold-images/random', [PlaceholdImageController::class, 'random']);

    // Opensource Audio

    // Opensource Image

    Route::middleware(['auth:api'])->group(function () {
        // Me
        Route::group(['prefix' => 'me'], function () {
            Route::get('/basic-info', [MeController::class, 'basicInfo']);
        });
    });
});
