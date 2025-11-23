<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('category')->get()->map(function ($gallery) {
            $imageUrl = $gallery->image ? asset('storage/' . $gallery->image) : null;
            if ($imageUrl && (config('app.env') === 'production' || env('FORCE_HTTPS', false))) {
                $imageUrl = str_replace('http://', 'https://', $imageUrl);
            }
            
            return [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image,
                'image_url' => $imageUrl,
                'category_id' => $gallery->category_id,
                'category' => $gallery->category,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at,
            ];
        });
        
        return response()->json($galleries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $storedPath = $uploadedFile->store('gallery', 'public');
            
            // Validate stored path
            if (empty($storedPath) || $storedPath === '0' || trim($storedPath) === '') {
                \Log::error('Gallery API upload: store() returned invalid path', [
                    'stored_path' => $storedPath,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'file_size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType(),
                ]);
                return response()->json([
                    'message' => 'Gagal menyimpan gambar. Silakan coba lagi.',
                    'error' => 'Invalid file path returned'
                ], 422);
            }
            
            // Set permission untuk file yang baru di-upload
            $fullPath = storage_path('app/public/' . $storedPath);
            if (file_exists($fullPath)) {
                @chmod($fullPath, 0644);
                @chmod(dirname($fullPath), 0755);
                @chmod(storage_path('app/public/gallery'), 0755);
            }
            
            $validated['image'] = $storedPath;
        } else {
            return response()->json([
                'message' => 'Gambar wajib diunggah',
                'error' => 'No image file provided'
            ], 422);
        }

        $gallery = Gallery::create($validated);

        // Get full URL for the image
        $imageUrl = asset('storage/' . $gallery->image);
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
        }

        return response()->json([
            'message' => 'Gallery berhasil dibuat',
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image,
                'image_url' => $imageUrl,
                'category_id' => $gallery->category_id,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at,
            ]
        ], 201);
    }

    public function show(Gallery $gallery)
    {
        $imageUrl = $gallery->image ? asset('storage/' . $gallery->image) : null;
        if ($imageUrl && (config('app.env') === 'production' || env('FORCE_HTTPS', false))) {
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
        }
        
        return response()->json([
            'id' => $gallery->id,
            'title' => $gallery->title,
            'description' => $gallery->description,
            'image_path' => $gallery->image,
            'image_url' => $imageUrl,
            'category_id' => $gallery->category_id,
            'category' => $gallery->category,
            'created_at' => $gallery->created_at,
            'updated_at' => $gallery->updated_at,
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        // Handle file upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image && \Storage::disk('public')->exists($gallery->image)) {
                \Storage::disk('public')->delete($gallery->image);
            }
            
            $uploadedFile = $request->file('image');
            $storedPath = $uploadedFile->store('gallery', 'public');
            
            // Validate stored path
            if (empty($storedPath) || $storedPath === '0' || trim($storedPath) === '') {
                \Log::error('Gallery API update: store() returned invalid path', [
                    'stored_path' => $storedPath,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                ]);
                return response()->json([
                    'message' => 'Gagal menyimpan gambar. Silakan coba lagi.',
                    'error' => 'Invalid file path returned'
                ], 422);
            }
            
            // Set permission untuk file yang baru di-upload
            $fullPath = storage_path('app/public/' . $storedPath);
            if (file_exists($fullPath)) {
                @chmod($fullPath, 0644);
                @chmod(dirname($fullPath), 0755);
            }
            
            $validated['image'] = $storedPath;
        }

        $gallery->update($validated);

        // Get full URL for the image
        $imageUrl = asset('storage/' . $gallery->image);
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
        }

        return response()->json([
            'message' => 'Gallery berhasil diupdate',
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image,
                'image_url' => $imageUrl,
                'category_id' => $gallery->category_id,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at,
            ]
        ]);
    }

    public function destroy(Gallery $gallery)
    {
        // Delete image file
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        $gallery->delete();

        return response()->json(['message' => 'Gallery berhasil dihapus']);
    }
}
