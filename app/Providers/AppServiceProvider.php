<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        // Force HTTPS selalu (untuk Railway production)
        // Cek jika request sudah HTTPS atau ada header dari proxy
        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' 
            || request()->server('HTTPS') === 'on'
            || request()->secure()
            || env('APP_ENV') === 'production'
            || str_contains(env('APP_URL', ''), 'https://')) {
            URL::forceScheme('https');
        }
        
        // Override asset() helper untuk storage files - gunakan /files/ sebagai ganti /storage/
        // Ini untuk bypass 403 error di Railway
        // Buat helper function untuk storage asset
        if (!function_exists('storage_asset')) {
            function storage_asset($path) {
                $url = asset('storage/' . ltrim($path, '/'));
                return str_replace('/storage/', '/files/', $url);
            }
        }
        
        // Override response untuk otomatis replace /storage/ dengan /files/ di semua HTML response
        // Ini untuk bypass 403 error di Railway tanpa perlu update semua view
        $this->app->bind(\Illuminate\Contracts\Http\Kernel::class, function ($app) {
            $kernel = new \App\Http\Kernel($app, $app['router']);
            $kernel->pushMiddleware(\App\Http\Middleware\ReplaceStoragePath::class);
            return $kernel;
        });
    }
}
