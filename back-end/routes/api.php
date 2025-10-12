<?php

use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobPostIndustryController;
use App\Http\Controllers\Api\JobPostController;

// Route test kết nối
Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel OK']);
});

// =========================
// Protected routes (JWT)
// =========================
Route::middleware(['jwt.auth'])->group(function () {

    // =========================
    // Job Posts
    // =========================
    Route::get('/job-posts-list', [JobPostController::class, 'index']);              // Lấy danh sách job
    Route::get('/job-posts-detail/{id}', [JobPostController::class, 'show']);       // Chi tiết job
    Route::post('/job-posts', [JobPostController::class, 'store']);                 // Tạo job
    Route::put('/job-posts/{id}', [JobPostController::class, 'update']);            // Update job
    Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']);        // Xóa job

    // =========================
    // Job Application - Student
    // =========================
    Route::post('/job/apply', [JobApplicationController::class, 'apply']);           // Sinh viên nộp CV
    Route::get('/job/getallcv', [JobApplicationController::class, 'getAll']);        // (cũ) có thể dùng cho test
    Route::get('/job/getcv_apply', [JobApplicationController::class, 'getcvnew']);  // Lấy CV mới nhất
    Route::get('/job/myapplications', [JobApplicationController::class, 'getMyApplications']); // Lấy tất cả CV của student
    // Lấy 8 ngành nghề có nhiều người ứng tuyển nhất
    Route::get('/job/topindustries', [JobApplicationController::class, 'getTopIndustries']);

    // =========================
    // Job Application - Lecturer
    // =========================
    Route::get('/job/applications', [JobApplicationController::class, 'getAlllecturer']); // Lấy tất cả ứng viên
    Route::get('/job/{id}/applicants', [JobApplicationController::class, 'getApplicants']); // Danh sách ứng viên theo job
    Route::put('/job/{id}/status', [JobApplicationController::class, 'updateStatus']);    // Cập nhật trạng thái CV

    // =========================
    // Job Metadata
    // =========================
    Route::get('/jobs/search', [JobPostController::class, 'search']);          // Search job
    Route::get('/positions', [JobPostController::class, 'positions']);         // Vị trí chuyên môn
    Route::get('/industries', [JobPostController::class, 'industries']);       // Ngành nghề
    Route::get('/experiences', [JobPostController::class, 'experiences']);     // Kinh nghiệm
    Route::get('/levels', [JobPostController::class, 'levels']);               // Cấp bậc
    Route::get('/location', [JobPostController::class, 'location']);           // Địa điểm
});
