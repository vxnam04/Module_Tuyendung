<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Thiếu token'], 401);
        }

        try {
            // Gọi API Auth (/me) để xác thực token
            $response = Http::withToken($token)->get(env('AUTH_SERVICE_URL') . '/api/me');

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

            // Nếu middleware yêu cầu loại user (student | lecturer)
            if ($type && $user['user_type'] !== $type) {
                return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
            }

            // Gắn user vào request để controller lấy
            $request->merge(['auth_user' => $user]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi xác thực',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
