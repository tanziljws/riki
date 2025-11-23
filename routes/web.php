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
use Illuminate\Support\Facades\Storage;

/*
||||--------------------------------------------------------------------------
|||| Storage Route - HARUS DI ATAS SEMUA ROUTE LAIN
||||--------------------------------------------------------------------------
*/

// Test endpoint untuk memastikan route bekerja
Route::get('/test-storage-route', function () {
    return response()->json(['status' => 'ok', 'message' => 'Storage route is accessible']);
});

// Helper function untuk serve file dari storage
$serveStorageFile = function ($path) {
    try {
        // Validasi path - jika kosong atau "0", return 404
        if (empty($path) || $path === '0' || trim($path) === '') {
            abort(404);
        }
        
        $path = urldecode($path);
        $filePath = storage_path('app/public/' . $path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public'));
        
        if (!$realPath || !$storagePath || strpos($realPath, $storagePath) !== 0) {
            abort(404);
        }
        
        if (!file_exists($realPath) || !is_file($realPath)) {
            abort(404);
        }
        
        // Set permission untuk memastikan file readable (777 untuk fix 500)
        @chmod($realPath, 0777);
        @chmod(dirname($realPath), 0777);
        @chmod(storage_path('app/public/gallery'), 0777);
        clearstatcache(true, $realPath);
        
        // Coba gunakan Storage facade dulu
        if (Storage::disk('public')->exists($path)) {
            try {
                return Storage::disk('public')->response($path);
            } catch (\Exception $e) {
                // Fallback jika Storage facade gagal
            }
        }
        
        // Fallback: gunakan file_get_contents
        $content = @file_get_contents($realPath);
        if ($content === false) {
            abort(500, 'Cannot read file');
        }
        
        $mimeType = @mime_content_type($realPath) ?: 'application/octet-stream';
        
        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($content))
            ->header('Cache-Control', 'public, max-age=31536000');
    } catch (\Exception $e) {
        abort(500, 'Error serving file');
    }
};

// Alternative route dengan path berbeda untuk bypass 403
// Route ini menggunakan /files/ sebagai ganti /storage/
Route::get('/files/{path}', $serveStorageFile)->where('path', '.*')->name('files');

// Storage route - serve files from storage/app/public
// Route ini HARUS di atas semua route lain untuk memastikan tidak terblokir
Route::get('/storage/{path}', $serveStorageFile)->where('path', '.*')->name('storage');

/*
|||--------------------------------------------------------------------------
||| Public Routes (User)
|||--------------------------------------------------------------------------
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
|||--------------------------------------------------------------------------
||| User Auth Routes (separate from admin)
|||--------------------------------------------------------------------------
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
|||--------------------------------------------------------------------------
||| Admin Routes (Login Required)
|||--------------------------------------------------------------------------
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
|||--------------------------------------------------------------------------
||| Default Laravel Auth Compatibility
|||--------------------------------------------------------------------------
*/
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
