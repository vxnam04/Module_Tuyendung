<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $type   // 'student' hoặc 'lecturer'
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $type = null)
    {
        // Lấy token từ header Authorization: Bearer <token>
        $token = $request->bearerToken();
        Log::info('CheckUserType - token received: ' . ($token ?? 'NULL'));

        // Nếu route yêu cầu có token nhưng token không tồn tại
        if (!$token) {
            if ($type) {
                return response()->json(['message' => 'Thiếu token'], 401);
            } else {
                return $next($request); // Route public, cho qua
            }
        }

        try {
            $authServiceUrl = env('AUTH_SERVICE_URL') . '/api/v1/me';
            Log::info('Calling auth service: ' . $authServiceUrl);

            $response = Http::withToken($token)->get($authServiceUrl);

            Log::info('Auth service response status: ' . $response->status());
            Log::info('Auth service response body: ', $response->json() ?? []);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Không xác thực được user',
                    'error'   => $response->json()
                ], 401);
            }

            $user = $response->json('data');

            if (!$user || !isset($user['user_type'])) {
                return response()->json(['message' => 'User không hợp lệ'], 403);
            }

            // Nếu route yêu cầu type, kiểm tra quyền
            if ($type && $user['user_type'] !== $type) {
                return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
            }

            // Gắn thông tin user vào request
            $request->merge(['auth_user' => $user]);

            return $next($request);
        } catch (\Exception $e) {
            Log::error('CheckUserType error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi khi xác thực',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
