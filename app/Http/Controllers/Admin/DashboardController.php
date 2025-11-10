<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGalleries = Gallery::count();
        $totalAlbums = Category::count();
        $uploadsToday = Gallery::whereDate('created_at', now()->toDateString())->count();

        // Treat comments today as items that may need review (no explicit moderation flag in schema)
        $pendingReview = Activity::where('type', 'comment')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        // Recent activities
        $recentActivities = Activity::with(['actor:id,name', 'gallery:id,title,image', 'comment:id,text'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Uploads in the last 7 days (Sun-Sat localized order to match existing labels)
        $days = collect(range(0,6))->map(function($i){
            return now()->startOfDay()->subDays(6 - $i);
        });
        $uploads7dCounts = $days->map(function($day){
            return Gallery::whereBetween('created_at', [$day, (clone $day)->endOfDay()])->count();
        });

        // Storage usage (percentage-based for chart). Uses the filesystem where storage/app/public resides.
        $storagePath = storage_path('app/public');
        $totalBytes = @disk_total_space($storagePath) ?: 0;
        $freeBytes  = @disk_free_space($storagePath) ?: 0;
        $usedBytes  = max($totalBytes - $freeBytes, 0);
        $storageUsedPct = $totalBytes > 0 ? (int) round($usedBytes / $totalBytes * 100) : 68; // fallback to previous look
        $storageTotalPct = 100;

        return view('admin.dashboard', [
            'totalGalleries' => $totalGalleries,
            'totalAlbums' => $totalAlbums,
            'uploadsToday' => $uploadsToday,
            'pendingReview' => $pendingReview,
            'recentActivities' => $recentActivities,
            'uploads7dCounts' => $uploads7dCounts->toArray(),
            'storageUsed' => $storageUsedPct,
            'storageTotal' => $storageTotalPct,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => [
                'nullable','email',
                Rule::unique('users','email')->ignore($user?->id)
            ],
            'password' => ['nullable','string','min:6','confirmed'],
            'language' => ['nullable', Rule::in(['id','en'])],
            'brand_name' => ['nullable','string','max:60'],
            'brand_logo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if (!empty($validated['email']) && $validated['email'] !== $user->email) {
            $user->email = $validated['email'];
        }
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if ($user->isDirty()) {
            $user->save();
        }

        // Save preferences to session
        if (!empty($validated['language'])) {
            session(['locale' => $validated['language']]);
            app()->setLocale($validated['language']);
        }

        // Branding (global) – store lightweight JSON + image in storage
        $brandingPath = storage_path('app/branding.json');
        $branding = [];
        if (file_exists($brandingPath)) {
            $branding = json_decode(@file_get_contents($brandingPath), true) ?: [];
        }
        if (!empty($validated['brand_name'])) {
            $branding['brand_name'] = $validated['brand_name'];
        }
        if ($request->hasFile('brand_logo')) {
            $request->file('brand_logo')->storeAs('branding', 'logo.png', 'public');
        }
        if (!empty($branding)) {
            @file_put_contents($brandingPath, json_encode($branding, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }

        return back()->with('status', __('Pengaturan berhasil diperbarui.'));
    }

    public function settings(Request $request)
    {
        // Provide minimal context to the settings view (locale/theme via session already available)
        return view('admin.settings');
    }
}
