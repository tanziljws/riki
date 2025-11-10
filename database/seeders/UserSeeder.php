<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@sekolah.sch.id'],
            [
                'name' => 'User Biasa',
                'password' => bcrypt('user123'),
                'role' => 'user'
            ]
        );
    }
}
