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
        \Illuminate\Support\Facades\Blade::directive('storage', function ($expression) {
            return "<?php echo str_replace('/storage/', '/files/', asset('storage/' . {$expression})); ?>";
        });
    }
}
