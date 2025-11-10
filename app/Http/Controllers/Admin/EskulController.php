<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eskul;

class EskulController extends Controller
{
    public function index()
    {
        $eskul = Eskul::all(); // ambil semua eskul
        return view('admin.eskul.index', compact('eskul'));
    }

    public function create()
    {
        return view('admin.eskul.create'); // form tambah eskul
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('eskul', 'public');
        }

        Eskul::create($data);
        return redirect()->route('admin.eskul.index')->with('success', 'Eskul berhasil ditambahkan!');
    }

    public function edit(Eskul $eskul)
    {
        return view('admin.eskul.edit', compact('eskul')); // form edit
    }

    public function update(Request $request, Eskul $eskul)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($eskul->foto) {
                \Storage::disk('public')->delete($eskul->foto);
            }
            $data['foto'] = $request->file('foto')->store('eskul', 'public');
        }

        $eskul->update($data);
        return redirect()->route('admin.eskul.index')->with('success', 'Eskul berhasil diupdate!');
    }

    public function destroy(Eskul $eskul)
    {
        if ($eskul->foto) {
            \Storage::disk('public')->delete($eskul->foto);
        }
        $eskul->delete();
        return redirect()->route('admin.eskul.index')->with('success', 'Eskul berhasil dihapus!');
    }
}
