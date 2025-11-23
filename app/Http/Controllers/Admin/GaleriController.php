<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $items = Gallery::with('category')->orderBy('id', 'asc')->paginate(12);
        return view('admin.galeri.index', compact('items'));
    }

    public function create()
    {
        $preferred = ['Home','Kepala Sekolah','Guru','Jurusan','Kegiatan','Ekstrakurikuler','Lainnya'];
        // Ensure preferred categories exist so admin can pick them
        foreach ($preferred as $name) { Category::firstOrCreate(['name' => $name]); }
        $orderMap = array_flip($preferred);
        $categories = Category::whereIn('name', $preferred)->get()
            ->sortBy(function($c) use ($orderMap){ return $orderMap[$c->name]; })
            ->values();
        return view('admin.galeri.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Determine selected category
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);
        $category = Category::findOrFail($request->input('category_id'));

        if (strtolower($category->name) === 'home') {
            // Special case: Home – allow 1..5 images (batch or one-by-one), no title/description
            // Accept either images[] (multiple) or a single 'image'
            if ($request->hasFile('images')) {
                $request->validate([
                    'images' => 'required|array|min:1|max:5',
                    'images.*' => 'required|image|max:4096',
                ]);
                $files = $request->file('images');
            } else {
                $request->validate([
                    'image' => 'required|image|max:4096',
                ]);
                $files = [$request->file('image')];
            }

            foreach ($files as $idx => $img) {
                $path = $img->store('gallery', 'public');
                // Set permission untuk file yang baru di-upload (777 untuk fix 403/500)
                $fullPath = storage_path('app/public/' . $path);
                @chmod($fullPath, 0777);
                @chmod(dirname($fullPath), 0777);
                @chmod(storage_path('app/public/gallery'), 0777);
                clearstatcache(true, $fullPath);
                
                Gallery::create([
                    'title' => 'Home Slide',
                    'description' => null,
                    'category_id' => $category->id,
                    'image' => $path,
                ]);
            }
        } else {
            // Default single upload flow
            try {
                // Validasi semua field termasuk image
                $data = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:4096',
                ]);
                
                $data['category_id'] = $category->id;
                
                // Pastikan file benar-benar ada
                if (!$request->hasFile('image')) {
                    \Log::error('Gallery upload: No image file in request', [
                        'request_all' => $request->all(),
                        'request_files' => $request->allFiles(),
                    ]);
                    return back()->withErrors(['image' => 'Gambar wajib diunggah'])->withInput();
                }
                
                $uploadedFile = $request->file('image');
                
                // Upload file dan dapatkan path
                $storedPath = $uploadedFile->store('gallery', 'public');
                
                // Pastikan path tidak kosong atau "0"
                if (empty($storedPath) || $storedPath === '0' || trim($storedPath) === '') {
                    \Log::error('Gallery upload: Invalid stored path', [
                        'stored_path' => $storedPath,
                        'original_name' => $uploadedFile->getClientOriginalName(),
                    ]);
                    return back()->withErrors(['image' => 'Gagal menyimpan gambar. Path tidak valid.'])->withInput();
                }
                
                // Set path ke data array (replace validated 'image' dengan stored path)
                $data['image'] = $storedPath;
                
                // Set permission untuk file yang baru di-upload (777 untuk fix 403/500)
                $fullPath = storage_path('app/public/' . $storedPath);
                
                // Pastikan file benar-benar ada setelah upload
                if (!file_exists($fullPath)) {
                    \Log::error('Gallery upload: File not found after store', [
                        'stored_path' => $storedPath,
                        'fullPath' => $fullPath,
                    ]);
                    return back()->withErrors(['image' => 'Gagal menyimpan gambar. File tidak ditemukan setelah upload.'])->withInput();
                }
                
                @chmod($fullPath, 0777);
                @chmod(dirname($fullPath), 0777);
                @chmod(storage_path('app/public/gallery'), 0777);
                clearstatcache(true, $fullPath);
                
                // Log untuk debugging dengan detail lengkap
                \Log::info('Gallery image uploaded', [
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'size' => $uploadedFile->getSize(),
                    'stored_path' => $storedPath,
                    'fullPath' => $fullPath,
                    'exists' => file_exists($fullPath),
                    'readable' => is_readable($fullPath),
                    'permissions' => file_exists($fullPath) ? substr(sprintf('%o', fileperms($fullPath)), -4) : 'N/A',
                ]);
                
                // Create gallery dengan data yang sudah dipastikan
                $gallery = Gallery::create($data);
                
                // Log untuk memastikan data tersimpan
                \Log::info('Gallery created in database', [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'image_path_in_db' => $gallery->image,
                    'image_empty_check' => empty($gallery->image),
                    'image_path_equals_stored' => $gallery->image === $storedPath,
                    'category_id' => $gallery->category_id,
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Gallery upload validation error', [
                    'errors' => $e->errors(),
                    'request_data' => $request->except(['image']),
                ]);
                return back()->withErrors($e->errors())->withInput();
            } catch (\Exception $e) {
                \Log::error('Gallery upload exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return back()->withErrors(['image' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
            }
        }

        return redirect()->route('admin.galeri.index')->with('success', 'Foto berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = Gallery::findOrFail($id);
        $preferred = ['Home','Kepala Sekolah','Guru','Jurusan','Kegiatan','Ekstrakurikuler','Lainnya'];
        foreach ($preferred as $name) { Category::firstOrCreate(['name' => $name]); }
        $orderMap = array_flip($preferred);
        $categories = Category::whereIn('name', $preferred)->get()
            ->sortBy(function($c) use ($orderMap){ return $orderMap[$c->name]; })
            ->values();
        return view('admin.galeri.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Gallery::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('gallery', 'public');
            // Set permission untuk file yang baru di-upload (777 untuk fix 403/500)
            // Lakukan langsung tanpa banyak check untuk avoid timeout
            $fullPath = storage_path('app/public/' . $data['image']);
            @chmod($fullPath, 0777);
            @chmod(dirname($fullPath), 0777);
        }

        $item->update($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Gallery::findOrFail($id);
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.galeri.index')->with('success', 'Foto berhasil dihapus');
    }
}
