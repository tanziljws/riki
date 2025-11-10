<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;   // ✅ tambahin ini
use App\Models\User;                   // ✅ pastikan model User dipanggil

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan hanya ada Super Admin utama
        $admin = User::firstOrNew(['email' => 'admin@gmail.com']);
        $admin->name = 'Super Admin';
        // Jika belum punya password, set default
        if (!$admin->exists || empty($admin->password)) {
            $admin->password = Hash::make('rikimaulana123');
        }
        $admin->role = 'super';
        $admin->is_active = true;
        $admin->save();
    }
}
