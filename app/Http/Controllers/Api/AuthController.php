<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // cek kredensial
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // ambil user yang sedang login
        $user = Auth::user();

        // blokir jika akun non-aktif
        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            return response()->json(['message' => 'Akses telah diblokir. Akun Anda dinonaktifkan.'], 403);
        }

        // hapus token lama agar hanya 1 aktif
        $user->tokens()->delete();

        // buat token baru dengan kemampuan sesuai role
        $token = $user->createToken('api-token', [$user->role])->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        // hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
