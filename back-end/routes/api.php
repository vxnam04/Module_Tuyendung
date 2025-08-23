<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Đây là nơi định nghĩa tất cả các route API
| Sử dụng JWT Auth cho bảo mật
|--------------------------------------------------------------------------
*/


// ===== ROUTES YÊU CẦU ĐĂNG NHẬP (JWT) =====
Route::middleware(['jwt.auth'])->group(function () {

    // Lấy thông tin user hiện tại
    Route::get('/me', [AuthController::class, 'me']);

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout']);

    // DEVICE ROUTES
    Route::prefix('device')->group(function () {
        Route::post('/register', [DeviceController::class, 'register']); // Thêm thiết bị mới
        Route::get('/list', [DeviceController::class, 'list']);           // Danh sách thiết bị
        Route::get('/{device_id}', [DeviceController::class, 'show']);    // Xem chi tiết thiết bị
    });
});

// routes/api.php
Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel OK']);
});
