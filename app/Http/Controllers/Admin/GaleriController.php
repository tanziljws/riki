<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                // Gunakan cara yang sama seperti GuruController/JurusanController
                $path = $img->store('gallery', 'public');
                
                // Set permission untuk file yang baru di-upload
                $fullPath = storage_path('app/public/' . $path);
                if (file_exists($fullPath)) {
                    @chmod($fullPath, 0777);
                    @chmod(dirname($fullPath), 0777);
                }
                
                Gallery::create([
                    'title' => 'Home Slide',
                    'description' => null,
                    'category_id' => $category->id,
                    'image' => $path,
                ]);
            }
        } else {
            // Default single upload flow - GUNAKAN CARA YANG SAMA SEPERTI GURU/JURUSAN CONTROLLER
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|max:4096',
            ]);
            
            $data['category_id'] = $category->id;
            
            // Upload file menggunakan cara yang SAMA PERSIS seperti GuruController
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                $storedPath = $uploadedFile->store('gallery', 'public');
                
                // PASTIKAN path tidak kosong atau "0" sebelum disimpan
                if (empty($storedPath) || $storedPath === '0' || trim($storedPath) === '') {
                    \Log::error('Gallery upload: store() returned invalid path', [
                        'stored_path' => $storedPath,
                        'original_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getMimeType(),
                    ]);
                    return back()->withErrors(['image' => 'Gagal menyimpan gambar. Silakan coba lagi.'])->withInput();
                }
                
                // Set path ke data array
                $data['image'] = $storedPath;
                
                // Set permission untuk file yang baru di-upload (AGGRESIF untuk fix 403/500)
                $fullPath = storage_path('app/public/' . $storedPath);
                if (file_exists($fullPath)) {
                    @chmod($fullPath, 0777);
                    @chmod(dirname($fullPath), 0777);
                    @chmod(storage_path('app/public/gallery'), 0777);
                    clearstatcache(true, $fullPath);
                    
                    // Log untuk debugging
                    \Log::info('Gallery image stored successfully', [
                        'path' => $storedPath,
                        'fullPath' => $fullPath,
                        'exists' => file_exists($fullPath),
                        'readable' => is_readable($fullPath),
                        'size' => filesize($fullPath),
                    ]);
                } else {
                    \Log::error('Gallery image file not found after store', [
                        'path' => $storedPath,
                        'fullPath' => $fullPath,
                        'storage_exists' => Storage::disk('public')->exists($storedPath),
                    ]);
                    return back()->withErrors(['image' => 'Gagal menyimpan gambar. File tidak ditemukan.'])->withInput();
                }
            } else {
                \Log::error('Gallery upload: No file in request', [
                    'has_file' => $request->hasFile('image'),
                    'all_files' => $request->allFiles(),
                ]);
                return back()->withErrors(['image' => 'Gambar wajib diunggah'])->withInput();
            }
            
            $gallery = Gallery::create($data);
            
            // Log untuk memastikan path tersimpan dengan benar
            \Log::info('Gallery created in database', [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'image_path_in_db' => $gallery->image,
                'image_path_valid' => !empty($gallery->image) && $gallery->image !== '0',
            ]);
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
