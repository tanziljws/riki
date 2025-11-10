<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gtk;

class GtkController extends Controller
{
    public function index()
    {
        $gtks = Gtk::all(); // ambil semua GTK
        return view('profil.index', compact('gtks')); // sesuaikan folder blade
    }

    public function create()
    {
        return view('profil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('gtks', 'public');
        }

        Gtk::create($data);

        return redirect()->route('gtk.index')->with('success', 'GTK berhasil ditambahkan');
    }

    public function edit($id)
    {
        $gtk = Gtk::findOrFail($id);
        return view('profil.edit', compact('gtk'));
    }

    public function update(Request $request, $id)
    {
        $gtk = Gtk::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('gtks', 'public');
        }

        $gtk->update($data);

        return redirect()->route('gtk.index')->with('success', 'GTK berhasil diupdate');
    }

    public function destroy($id)
    {
        $gtk = Gtk::findOrFail($id);
        $gtk->delete();

        return redirect()->route('gtk.index')->with('success', 'GTK berhasil dihapus');
    }
}
