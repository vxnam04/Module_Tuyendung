<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobPostIndustryController;
use App\Http\Controllers\Api\JobPostAddressController;
use App\Http\Controllers\JobPostController;

// Route test kết nối
Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel OK']);
});

// ===== ROUTES PUBLIC (không cần token) =====
Route::get('/job-industries', [JobPostIndustryController::class, 'index']);
Route::get('/locations', [JobPostAddressController::class, 'index']);
// Route::get('/job-posts', [JobPostController::class, 'index']);
// Route::get('/job-posts/{id}', [JobPostController::class, 'show']);


Route::middleware(['checkUser:lecturer'])->group(function () {
    Route::get('/job-posts', [JobPostController::class, 'index']);     // GET /api/job
    Route::post('/job-posts', [JobPostController::class, 'store']);    // POST /api/job
    Route::get('/job-posts/{id}', [JobPostController::class, 'show']); // GET /api/job/{id}
    Route::put('/job-posts/{id}', [JobPostController::class, 'update']); // PUT /api/job/{id}
    Route::delete('/job-posts/{id}', [JobPostController::class, 'destroy']); // DELETE /api/job/{id}
});
