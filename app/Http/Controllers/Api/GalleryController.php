<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('category')->get();
        return response()->json($galleries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $gallery = Gallery::create($validated);

        return response()->json([
            'message' => 'Gallery berhasil dibuat',
            'data' => $gallery
        ], 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json($gallery);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $gallery->update($request->all());

        return response()->json([
            'message' => 'Gallery berhasil diupdate',
            'data' => $gallery
        ]);
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return response()->json(['message' => 'Gallery berhasil dihapus']);
    }
}
