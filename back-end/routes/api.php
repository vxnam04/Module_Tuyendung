<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobPostIndustryController;
use App\Http\Controllers\Api\JobPostAddressController;
use App\Http\Controllers\JobPostController;

// ===== ROUTES PUBLIC (không cần middleware) =====



// ===== ROUTES YÊU CẦU ĐĂNG NHẬP (JWT) =====
Route::middleware(['jwt.auth'])->group(function () {

    Route::get('/ping', function () {
        return response()->json(['message' => 'Laravel OK']);
    });
    // Job post tạo mới (vẫn cần check role)
    Route::post('/job-posts', [JobPostController::class, 'store'])
        ->middleware('checkUser:lecturer');

    Route::get('/job-industries', [JobPostIndustryController::class, 'index']);
    Route::get('/locations', [JobPostAddressController::class, 'index']);
    Route::get('/job-posts', [JobPostController::class, 'index']);
    Route::get('/job-posts/{id}', [JobPostController::class, 'show']);
});
