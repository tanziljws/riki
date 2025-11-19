<?php

use Illuminate\Support\Facades\Route;
use App\Models\Gallery;
use App\Models\Category;
use App\Http\Controllers\AuthController;

// =====================
// User Controllers
// =====================
use App\Http\Controllers\User\GtkController;
use App\Http\Controllers\User\GuruController;
use App\Http\Controllers\User\JurusanController;
use App\Http\Controllers\User\EskulController;
use App\Http\Controllers\User\GaleriController;
use App\Http\Controllers\User\InteractionController;

// =====================
// Admin Controllers
// =====================
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController as AdminGuru;
use App\Http\Controllers\Admin\JurusanController as AdminJurusan;
use App\Http\Controllers\Admin\EskulController as AdminEskul;
use App\Http\Controllers\Admin\GaleriController as AdminGaleri;
use App\Http\Controllers\Admin\PerangkatController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\User\AuthController as UserAuthController;

/*
||--------------------------------------------------------------------------
|| Storage Fallback Route (Prioritas Tinggi)
||--------------------------------------------------------------------------
|| Fallback jika symlink tidak bekerja atau file tidak ditemukan
|| Route ini harus diletakkan sebelum route lain untuk menghindari konflik
*/
Route::get('/storage/{path}', function ($path) {
    try {
        // Decode URL path untuk handle special characters
        $path = urldecode($path);
        
        // Normalize path - remove leading/trailing slashes
        $path = ltrim($path, '/');
        
        $filePath = storage_path('app/public/' . $path);
        
        \Log::info('Storage route called', [
            'original_path' => request()->path(),
            'decoded_path' => $path,
            'filePath' => $filePath,
            'file_exists' => file_exists($filePath),
            'is_readable' => file_exists($filePath) ? is_readable($filePath) : false,
        ]);
        
        // Security: prevent directory traversal
        $realPath = realpath($filePath);
        $realBase = realpath(storage_path('app/public'));
        
        if (!$realBase) {
            \Log::error('Storage base path not found', ['base' => storage_path('app/public')]);
            abort(500, 'Storage configuration error');
        }
        
        if (!$realPath) {
            \Log::warning('Storage file path not resolved', [
                'path' => $path,
                'filePath' => $filePath,
                'realBase' => $realBase,
                'directory_exists' => is_dir(dirname($filePath)),
            ]);
            abort(404, 'File not found');
        }
        
        if (strpos($realPath, $realBase) !== 0) {
            \Log::warning('Storage access denied - path traversal', [
                'path' => $path,
                'realPath' => $realPath,
                'realBase' => $realBase,
            ]);
            abort(404, 'Invalid path');
        }
        
        if (!is_file($realPath)) {
            \Log::warning('Storage path is not a file', [
                'path' => $path,
                'realPath' => $realPath,
                'is_file' => is_file($realPath),
                'is_dir' => is_dir($realPath),
            ]);
            abort(404, 'Not a file');
        }
        
        // Fix permission jika perlu
        $currentPerms = fileperms($realPath);
        $currentPermsOct = substr(sprintf('%o', $currentPerms), -4);
        
        if (!is_readable($realPath)) {
            \Log::warning('Storage file not readable, fixing permissions', [
                'path' => $path,
                'realPath' => $realPath,
                'current_perms' => $currentPermsOct,
            ]);
            
            // Try to fix permission
            @chmod($realPath, 0644);
            @chown($realPath, 'www-data');
            
            // Check parent directory permissions too
            $parentDir = dirname($realPath);
            @chmod($parentDir, 0755);
            @chown($parentDir, 'www-data');
            
            if (!is_readable($realPath)) {
                \Log::error('Storage file still not readable after fix', [
                    'path' => $path,
                    'realPath' => $realPath,
                    'new_perms' => substr(sprintf('%o', fileperms($realPath)), -4),
                ]);
                abort(403, 'File not readable');
            }
        }
        
        $mimeType = @mime_content_type($realPath);
        if (!$mimeType) {
            // Fallback MIME type berdasarkan extension
            $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
            ];
            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }
        
        \Log::info('Storage file serving successfully', [
            'path' => $path,
            'mimeType' => $mimeType,
            'size' => filesize($realPath),
        ]);
        
        return response()->file($realPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    } catch (\Exception $e) {
        \Log::error('Storage route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        abort(500, 'Storage error: ' . $e->getMessage());
    }
})->where('path', '.*')->name('storage');

/*
||--------------------------------------------------------------------------
|| Public Routes (User)
||--------------------------------------------------------------------------
*/

// Home
Route::get('/', function(){
    $heroSlides = Gallery::with('category')
        ->whereHas('category', function($q){
            $q->whereIn('name', ['Home','home']);
        })
        ->latest()
        ->take(5)
        ->get(['id','image','title','category_id']);
    return view('user.home', compact('heroSlides'));
})->name('home');

// Tentang Kami
Route::get('/tentang', fn () => view('user.tentang'))->name('tentang');

// Galeri
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/galeri/{id}', [GaleriController::class, 'show'])->name('galeri.show');

// Interactions API (persistent like & comments)
// State bisa diakses tanpa login (untuk lihat like/comment count)
Route::get('/galeri/{gallery}/state', [InteractionController::class, 'state'])->name('galeri.state');

// Like dan comment butuh login
Route::middleware(['auth'])->group(function(){
    Route::post('/galeri/{gallery}/like', [InteractionController::class, 'toggleLike'])->name('galeri.like');
    Route::post('/galeri/{gallery}/comments', [InteractionController::class, 'addComment'])->name('galeri.comment');
    Route::delete('/comments/{comment}', [InteractionController::class, 'deleteComment'])->name('comment.delete');
});

// Bantuan (Chat AI) — hanya untuk user yang login
Route::get('/bantuan', function(){
    if (!auth()->check()) {
        return redirect()->route('user.login')->with('status', 'Silakan login/daftar untuk menggunakan Bantuan AI.');
    }
    return view('user.bantuan');
})->name('bantuan');

// Guru, Jurusan, Eskul
Route::get('/guru', [GuruController::class, 'index'])->name('guru');
Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan');
Route::get('/eskul', [EskulController::class, 'index'])->name('eskul');

/*
||--------------------------------------------------------------------------
|| User Auth Routes (separate from admin)
||--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->group(function () {
    // Register
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');

    // Login
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');

    // Social login: Google
    Route::get('/auth/google', [UserAuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [UserAuthController::class, 'googleCallback'])->name('google.callback');

    // Logout
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function(){
        Route::get('/profile', [UserAuthController::class, 'showProfile'])->name('profile');
        Route::get('/settings', [UserAuthController::class, 'showSettings'])->name('settings');
        Route::post('/settings', [UserAuthController::class, 'updateSettings'])->name('settings.update');
        Route::post('/avatar', [UserAuthController::class, 'updateAvatar'])->name('avatar.update');
    });
});

/*
||--------------------------------------------------------------------------
|| Admin Routes (Login Required)
||--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Auth
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard + CRUD (hanya untuk yang login)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Allow GET logout to prevent 419 when directly visiting /admin/logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

        // CRUD Master Data
        Route::resource('guru', AdminGuru::class);
        Route::resource('jurusan', AdminJurusan::class);
        Route::resource('eskul', AdminEskul::class);
        Route::resource('galeri', AdminGaleri::class);

        // Aktivitas (notifikasi like & komentar)
        Route::get('/aktivitas', [ActivityController::class, 'index'])->name('aktivitas');
        Route::delete('/comments/{comment}', [ActivityController::class, 'destroyComment'])->name('comment.destroy');

        // Perangkat
        Route::get('/perangkat', [PerangkatController::class, 'index'])->name('perangkat');

        // Settings page (view)
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        // Settings (update email/password/lang)
        Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

        // Management Akun
        Route::get('/management', [AccountController::class, 'index'])->name('management');
        Route::post('/management/{user}/toggle', [AccountController::class, 'toggle'])->name('management.toggle');
        Route::delete('/management/{user}', [AccountController::class, 'destroy'])->name('management.delete');
    });
});

/*
||--------------------------------------------------------------------------
|| Default Laravel Auth Compatibility
||--------------------------------------------------------------------------
*/
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
