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

// Storage route - serve files from storage/app/public
// Route ini digunakan karena Apache di Railway tidak bisa serve file dari symlink dengan benar
Route::get('/storage/{path}', function ($path) {
    try {
        // Decode path jika ada encoding
        $path = urldecode($path);
        
        // Security: prevent directory traversal
        $filePath = storage_path('app/public/' . $path);
        $realPath = realpath($filePath);
        $storagePath = realpath(storage_path('app/public'));
        
        if (!$realPath || !$storagePath || strpos($realPath, $storagePath) !== 0) {
            \Log::warning("Storage route: Path traversal attempt or invalid path", ['path' => $path, 'realPath' => $realPath]);
            abort(404);
        }
        
        if (!file_exists($realPath) || !is_file($realPath)) {
            \Log::warning("Storage route: File not found", ['path' => $path, 'realPath' => $realPath]);
            abort(404);
        }
        
        // Set permission untuk memastikan file readable (777 untuk fix 403)
        @chmod($realPath, 0777);
        
        // Set permission untuk parent directory juga
        $parentDir = dirname($realPath);
        @chmod($parentDir, 0777);
        
        // Clear stat cache untuk memastikan permission update terdeteksi
        clearstatcache(true, $realPath);
        
        // Cek apakah file readable
        if (!is_readable($realPath)) {
            \Log::error("Storage route: File not readable after chmod", ['path' => $path, 'realPath' => $realPath, 'perms' => substr(sprintf('%o', fileperms($realPath)), -4)]);
            abort(500, 'File not readable');
        }
        
        // Gunakan Storage facade untuk serve file (lebih reliable)
        if (\Storage::disk('public')->exists($path)) {
            return \Storage::disk('public')->response($path);
        }
        
        // Fallback: serve langsung dengan file_get_contents
        $content = file_get_contents($realPath);
        if ($content === false) {
            \Log::error("Storage route: Cannot read file content", ['path' => $path, 'realPath' => $realPath]);
            abort(500, 'Cannot read file');
        }
        
        $mimeType = mime_content_type($realPath) ?: 'application/octet-stream';
        
        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($content))
            ->header('Cache-Control', 'public, max-age=31536000');
            
    } catch (\Exception $e) {
        \Log::error("Storage route error", ['path' => $path ?? 'unknown', 'error' => $e->getMessage()]);
        abort(500, 'Error serving file');
    }
})->where('path', '.*')->name('storage');

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
