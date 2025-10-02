<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobPostIndustryController;
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

    // routes applycv
    Route::post('/job/apply', [JobApplicationController::class, 'apply']);           // Student apply job
    Route::get('/job/getallcv', [JobApplicationController::class, 'getAll']);           // get cv moi nhat
    Route::get('/job/getcv_apply', [JobApplicationController::class, 'getcvnew']);           // getallcv
    Route::get('/job/{id}/applicants', [JobApplicationController::class, 'getApplicants']); // Lecturer xem ứng viên
    Route::put('/job/application/{id}/status', [JobApplicationController::class, 'updateStatus']); // Cập nhật trạng thái


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
