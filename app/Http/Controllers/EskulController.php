<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use Illuminate\Http\Request;

class EskulController extends Controller
{
    // USER: hanya lihat data
    public function index()
    {
        $eskul = Eskul::all();
        return view('eskul.index', compact('eskul')); // resources/views/eskul/index.blade.php
    }

    // ADMIN: CRUD
    public function create() { return view('eskul.create'); }
    public function store(Request $request) {
        Eskul::create($request->all());
        return redirect()->route('eskul.index')->with('success', 'Eskul berhasil ditambahkan');
    }
    public function edit(Eskul $eskul) { return view('eskul.edit', compact('eskul')); }
    public function update(Request $request, Eskul $eskul) {
        $eskul->update($request->all());
        return redirect()->route('eskul.index')->with('success', 'Eskul berhasil diupdate');
    }
    public function destroy(Eskul $eskul) {
        $eskul->delete();
        return redirect()->route('eskul.index')->with('success', 'Eskul berhasil dihapus');
    }
}
