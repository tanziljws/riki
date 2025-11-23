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
            // Default single upload flow - GUNAKAN CARA YANG SAMA PERSIS SEPERTI GURU CONTROLLER
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|max:4096',
            ]);

            $data = $request->only(['title', 'description']);
            $data['category_id'] = $category->id;

            // Upload foto jika ada - PERSIS SEPERTI GURU CONTROLLER
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // DEBUG: Log sebelum store
                \Log::info('Gallery upload: Before store', [
                    'has_file' => true,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'temp_path' => $file->getRealPath(),
                    'temp_exists' => file_exists($file->getRealPath()),
                ]);
                
                // Coba store
                $storedPath = $file->store('gallery', 'public');
                
                // DEBUG: Log setelah store
                \Log::info('Gallery upload: After store', [
                    'stored_path' => $storedPath,
                    'stored_path_type' => gettype($storedPath),
                    'stored_path_empty' => empty($storedPath),
                    'stored_path_equals_zero' => $storedPath === '0',
                    'stored_path_equals_zero_string' => $storedPath === '0',
                    'stored_path_length' => strlen($storedPath ?? ''),
                ]);
                
                // VALIDASI: Jika path "0" atau kosong, gunakan cara alternatif
                if (empty($storedPath) || $storedPath === '0' || trim($storedPath) === '') {
                    \Log::warning('Gallery upload: store() returned invalid path, trying alternative method');
                    
                    // Coba gunakan Storage facade langsung
                    try {
                        $storedPath = Storage::disk('public')->putFile('gallery', $file);
                        \Log::info('Gallery upload: Storage::putFile() returned', [
                            'path' => $storedPath,
                            'empty' => empty($storedPath),
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Gallery upload: Storage::putFile() failed', [
                            'error' => $e->getMessage(),
                        ]);
                        
                        // Coba cara manual dengan move_uploaded_file
                        $galleryDir = storage_path('app/public/gallery');
                        if (!is_dir($galleryDir)) {
                            @mkdir($galleryDir, 0777, true);
                        }
                        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                        $destination = $galleryDir . '/' . $fileName;
                        
                        if (@move_uploaded_file($file->getRealPath(), $destination)) {
                            $storedPath = 'gallery/' . $fileName;
                            @chmod($destination, 0777);
                            \Log::info('Gallery upload: move_uploaded_file() succeeded', [
                                'path' => $storedPath,
                            ]);
                        } else {
                            \Log::error('Gallery upload: All methods failed');
                            return back()->withErrors(['image' => 'Gagal menyimpan gambar. Silakan coba lagi.'])->withInput();
                        }
                    }
                }
                
                $data['image'] = $storedPath;
                
                // Set permission
                $fullPath = storage_path('app/public/' . $data['image']);
                if (file_exists($fullPath)) {
                    @chmod($fullPath, 0777);
                    @chmod(dirname($fullPath), 0777);
                }
            }

            // DEBUG: Log sebelum create
            \Log::info('Gallery upload: Before create', [
                'data' => $data,
                'image_path' => $data['image'] ?? 'NOT SET',
            ]);

            $gallery = Gallery::create($data);
            
            // DEBUG: Log setelah create
            \Log::info('Gallery upload: After create', [
                'id' => $gallery->id,
                'image_path_in_db' => $gallery->image,
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
