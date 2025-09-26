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
    Route::get('/job-posts-detail/{id}', [JobPostController::class, 'show']);
    Route::post('/job-posts', [JobPostController::class, 'store']);
    Route::put('/job-posts/{id}', [JobPostController::class, 'update']);
    Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']);
    // Vị trí chuyên môn, Kiểu IT suport hay dev 
    Route::get('/positions', [JobPostController::class, 'positions']);
    // Ngành nghề
    Route::get('/industries', [JobPostController::class, 'industries']);
    // Kinh nghiệm
    Route::get('/experiences', [JobPostController::class, 'experiences']);
    // Cấp bậc kiểu inter, trưởng phòng 
    Route::get('/levels', [JobPostController::class, 'levels']);
    // Địa điểm
    Route::get('/location', [JobPostController::class, 'location']);
});
