<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\BantuanController;

// -------------------------
// Auth (semua bisa akses)
// -------------------------
Route::post('/login', [AuthController::class, 'login']);
// Bantuan (Chat AI via Gemini) - publik, kunci ada di server
Route::post('/bantuan/chat', [BantuanController::class, 'chat']);

// -------------------------
// Butuh login (admin / user)
// -------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ----- khusus admin -----
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Halo admin 👑']);
        });

        // CRUD kategori (hanya admin)
        Route::apiResource('categories', CategoryController::class);
    });

    // ----- khusus user -----
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return response()->json(['message' => 'Halo user 🙋‍♂️']);
        });
    });

    // galleries → bisa diakses semua (admin & user)
    Route::apiResource('galleries', GalleryController::class);

    // gurus → bisa diakses semua (admin & user)
    Route::apiResource('gurus', GuruController::class);
});
