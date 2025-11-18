<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(12);
        return view('admin.accounts.index', compact('users'));
    }

    public function toggle(User $user)
    {
        // Lindungi akun khusus 'maulana'
        if (strtolower($user->name) === 'maulana') {
            return redirect()->back()->with('status', 'Akun ini tidak dapat diubah.');
        }
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('status', 'Status akun diperbarui.');
    }

    public function destroy(User $user)
    {
        // Lindungi akun khusus 'maulana'
        if (strtolower($user->name) === 'maulana') {
            return redirect()->back()->with('status', 'Akun ini tidak dapat dihapus.');
        }
        // Nonaktifkan dan soft-delete
        $user->is_active = false;
        $user->save();
        $user->delete();

        return redirect()->back()->with('status', 'Akun berhasil dinonaktifkan dan dihapus dari daftar.');
    }
}
