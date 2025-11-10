<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login (khusus admin).
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.login'); // resources/views/admin/login.blade.php
    }

    /**
     * Proses login admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cegah login jika akun dinonaktifkan
            $user = Auth::user();
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akses telah diblokir. Akun Anda dinonaktifkan.'])->withInput();
            }

            // Simpan waktu login terakhir
            $user->last_login_at = now();
            $user->save();

            // Redirect ke dashboard admin
            // Paksa default Bahasa Indonesia setiap kali login (user masih bisa ganti di Pengaturan)
            $request->session()->put('locale', 'id');
            app()->setLocale('id');

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Logout admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Kembali ke halaman login admin
        return redirect()->route('admin.login');
    }
}
