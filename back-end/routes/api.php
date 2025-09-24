<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobPostIndustryController;
use App\Http\Controllers\Api\JobPostAddressController;
use App\Http\Controllers\Api\JobPostController;

// Route test kết nối
Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel OK']);
});



// Protected routes
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/job-posts-list', [JobPostController::class, 'index']);
    Route::post('/job-posts', [JobPostController::class, 'store']);
    Route::put('/job-posts/{id}', [JobPostController::class, 'update']);
    Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']);

    Route::get('/positions', [JobPostController::class, 'positions']);
    // Route::get('/industries', [JobPostController::class, 'industries']);
    Route::get('/experiences', [JobPostController::class, 'experiences']);
    Route::get('/levels', [JobPostController::class, 'levels']);
});
