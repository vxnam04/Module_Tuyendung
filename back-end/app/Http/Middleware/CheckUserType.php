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
        dd(1);

        $token = $request->bearerToken();
        \Log::info('CheckUserType - token: ' . ($token ?? 'NULL'));

        $token = 'FAKE_TEST_TOKEN_12345';

        dd(2);
        $authServiceUrl = env('AUTH_SERVICE_URL', 'http://localhost:8090') . '/api/v1/me';
        \Log::info('Auth service URL: ' . $authServiceUrl);

        try {
            $response = Http::withToken($token)->get($authServiceUrl);

            if ($response->failed()) {
                return response()->json(['message' => 'Không xác thực được user'], 401);
            }

            $user = $response->json('data', null);

            if (!$user || !isset($user['user_type'])) {
                \Log::error('User data invalid: ' . json_encode($response->json()));
                return response()->json(['message' => 'User không hợp lệ'], 403);
            }

            if ($type && $user['user_type'] !== $type) {
                return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
            }

            $request->merge(['auth_user' => $user]);
            return $next($request);
        } catch (\Exception $e) {

            dd(3);
            \Log::error('CheckUserType error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi xác thực',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
