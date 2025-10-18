<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Thêm thông tin user vào token
        $payload = [
            'iss' => "laravel-jwt", // Issuer
            'sub' => $user->id,     // Subject = user id
            'email' => $user->email,
            'username' => $user->username ?? null,
            'full_name' => $user->name,
            'user_type' => $user->user_type ?? 'student', // 👈 thêm loại user
            'is_admin' => (bool)($user->is_admin ?? false), // 👈 thêm quyền admin
            'iat' => time(),        // Issued at
            'exp' => time() + 60 * 60 // Expiration = 1h
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ]);
    }
}
