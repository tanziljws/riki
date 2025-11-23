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
        // Log request untuk debugging
        \Log::info('Gallery API store request', [
            'has_file' => $request->hasFile('image'),
            'all_files' => array_keys($request->allFiles()),
            'content_type' => $request->header('Content-Type'),
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'image_type' => gettype($request->input('image')),
        ]);

        // Check if image is sent as base64 string instead of file
        $imageInput = $request->input('image');
        if (is_string($imageInput) && !$request->hasFile('image')) {
            // Handle base64 image
            if (preg_match('/^data:image\/(\w+);base64,/', $imageInput, $matches)) {
                \Log::info('Gallery API: Detected base64 image');
                $imageData = base64_decode(substr($imageInput, strpos($imageInput, ',') + 1));
                $extension = $matches[1];
                $fileName = uniqid() . '.' . $extension;
                $storedPath = 'gallery/' . $fileName;
                $fullPath = storage_path('app/public/' . $storedPath);
                
                // Ensure directory exists
                if (!file_exists(storage_path('app/public/gallery'))) {
                    mkdir(storage_path('app/public/gallery'), 0755, true);
                }
                
                file_put_contents($fullPath, $imageData);
                @chmod($fullPath, 0644);
                
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'category_id' => 'required|exists:categories,id',
                ]);
                $validated['image'] = $storedPath;
                
                $gallery = Gallery::create($validated);
                
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
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'category_id' => 'required|exists:categories,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Gallery API validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['image']),
            ]);
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        // Handle file upload - GUNAKAN CARA YANG SAMA SEPERTI GURU/JURUSAN CONTROLLER
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            
            \Log::info('Gallery API file upload detected', [
                'original_name' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'is_valid' => $uploadedFile->isValid(),
            ]);
            
            if (!$uploadedFile->isValid()) {
                \Log::error('Gallery API: Invalid file upload', [
                    'error' => $uploadedFile->getError(),
                    'error_message' => $uploadedFile->getErrorMessage(),
                ]);
                return response()->json([
                    'message' => 'File upload tidak valid',
                    'error' => 'Invalid file upload'
                ], 422);
            }
            
            // Store file - GUNAKAN CARA YANG SAMA SEPERTI GURU CONTROLLER
            try {
                $storedPath = $uploadedFile->store('gallery', 'public');
            } catch (\Exception $e) {
                \Log::error('Gallery API: Exception during store', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'Gagal menyimpan gambar. Error: ' . $e->getMessage(),
                    'error' => 'Storage exception'
                ], 500);
            }
            
            \Log::info('Gallery API file stored', [
                'stored_path' => $storedPath,
                'stored_path_type' => gettype($storedPath),
                'stored_path_length' => is_string($storedPath) ? strlen($storedPath) : 'N/A',
                'full_path' => storage_path('app/public/' . $storedPath),
            ]);
            
            // Validate stored path - JANGAN PERNAH TERIMA PATH "0" ATAU KOSONG
            $storedPathStr = (string) $storedPath;
            if (empty($storedPath) || $storedPath === '0' || $storedPath === 0 || trim($storedPathStr) === '' || strlen($storedPathStr) < 5 || !str_starts_with($storedPathStr, 'gallery/')) {
                \Log::error('Gallery API upload: store() returned invalid path', [
                    'stored_path' => $storedPath,
                    'stored_path_type' => gettype($storedPath),
                    'stored_path_string' => $storedPathStr,
                    'stored_path_length' => strlen($storedPathStr),
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'file_size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'is_valid' => $uploadedFile->isValid(),
                ]);
                
                // Hapus file jika ada yang terbuat
                if ($storedPath && $storedPath !== '0' && Storage::disk('public')->exists($storedPath)) {
                    Storage::disk('public')->delete($storedPath);
                }
                
                return response()->json([
                    'message' => 'Gagal menyimpan gambar. Path tidak valid.',
                    'error' => 'Invalid file path returned',
                    'debug' => [
                        'stored_path' => $storedPath,
                        'stored_path_type' => gettype($storedPath),
                        'storage_exists' => $storedPath ? Storage::disk('public')->exists($storedPath) : false,
                    ]
                ], 422);
            }
            
            // Verify file exists after storage
            $fullPath = storage_path('app/public/' . $storedPath);
            if (!file_exists($fullPath)) {
                \Log::error('Gallery API: File not found after store', [
                    'stored_path' => $storedPath,
                    'full_path' => $fullPath,
                    'storage_exists' => Storage::disk('public')->exists($storedPath),
                    'directory_exists' => is_dir(dirname($fullPath)),
                    'directory_writable' => is_writable(dirname($fullPath)),
                ]);
                
                // Hapus record yang mungkin terbuat
                if (Storage::disk('public')->exists($storedPath)) {
                    Storage::disk('public')->delete($storedPath);
                }
                
                return response()->json([
                    'message' => 'Gagal menyimpan gambar. File tidak ditemukan setelah upload.',
                    'error' => 'File not found after storage',
                    'debug' => [
                        'stored_path' => $storedPath,
                        'full_path' => $fullPath,
                        'file_exists' => file_exists($fullPath),
                    ]
                ], 422);
            }
            
            // Set permission untuk file yang baru di-upload
            @chmod($fullPath, 0644);
            @chmod(dirname($fullPath), 0755);
            @chmod(storage_path('app/public/gallery'), 0755);
            
            $validated['image'] = $storedPath;
        } else {
            \Log::error('Gallery API: No file in request', [
                'has_file' => $request->hasFile('image'),
                'all_files' => $request->allFiles(),
                'request_keys' => array_keys($request->all()),
            ]);
            return response()->json([
                'message' => 'Gambar wajib diunggah',
                'error' => 'No image file provided',
                'debug' => [
                    'has_file' => $request->hasFile('image'),
                    'content_type' => $request->header('Content-Type'),
                ]
            ], 422);
        }

        // Create gallery
        try {
            $gallery = Gallery::create($validated);
            \Log::info('Gallery created successfully', [
                'id' => $gallery->id,
                'image_path' => $gallery->image,
            ]);
        } catch (\Exception $e) {
            \Log::error('Gallery API: Failed to create gallery', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Delete uploaded file if gallery creation fails
            if (isset($storedPath) && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }
            return response()->json([
                'message' => 'Gagal membuat gallery',
                'error' => $e->getMessage()
            ], 500);
        }

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
