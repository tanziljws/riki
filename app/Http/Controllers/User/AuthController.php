<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Redirect to Google's OAuth consent screen (shows account chooser).
     */
    public function googleRedirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()->route('user.login')->withErrors(['email' => 'Login Google gagal. Coba lagi.']);
        }

        // Find or create local user by email
        $user = User::where('email', $googleUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'User'),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        // Block if inactive
        if (isset($user->is_active) && !$user->is_active) {
            return redirect()->route('user.login')->withErrors(['email' => 'Akses telah diblokir. Akun Anda dinonaktifkan.']);
        }

        Auth::login($user, true);
        request()->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'is_active' => true,
        ]);

        return redirect()
            ->route('user.login')
            ->with('status', 'Registrasi berhasil. Silakan login untuk melanjutkan.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akses telah diblokir. Akun Anda dinonaktifkan.'])->onlyInput('email');
            }
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showSettings(Request $request)
    {
        return view('user.auth.settings', ['user' => $request->user()]);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update basic fields
        $user->name = $data['name'];
        $user->email = $data['email'];

        // If changing password, verify current password
        if (!empty($data['password'])) {
            if (empty($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return redirect()->route('user.settings')->with('status', 'Profil berhasil diperbarui.');
    }

    public function showProfile(Request $request)
    {
        return view('user.auth.profile', ['user' => $request->user()]);
    }

    /**
     * Update user avatar image.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        // store avatar in public disk
        $path = $request->file('avatar')->store('avatars', 'public');

        // optionally delete old avatar if exists and on our storage
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            // ignore errors if cannot delete
            try { Storage::disk('public')->delete($user->avatar); } catch (\Throwable $e) {}
        }

        $user->avatar = $path;
        $user->save();

        return redirect()->route('user.profile')->with('status', 'Foto profil berhasil diperbarui.');
    }
}
