<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class JWTAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken(); // Lấy token từ header Authorization

        if (!$token) {
            return response()->json(['message' => 'Token không tồn tại'], 401);
        }

        try {
            // Decode JWT bằng Firebase JWT
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Convert stdClass -> array nếu muốn
            $userArray = json_decode(json_encode($decoded), true);

            // Gắn thông tin user vào request để controller dùng
            $request->attributes->set('user', $userArray);
        } catch (\Firebase\JWT\ExpiredException $e) {
            Log::warning('JWT expired: ' . $e->getMessage());
            return response()->json(['message' => 'Token đã hết hạn'], 401);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            Log::warning('JWT invalid signature: ' . $e->getMessage());
            return response()->json(['message' => 'Token không hợp lệ'], 401);
        } catch (\Exception $e) {
            Log::error('JWT decode error: ' . $e->getMessage());
            return response()->json(['message' => 'Token không hợp lệ'], 401);
        }

        return $next($request);
    }
}
