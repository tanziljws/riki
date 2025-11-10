<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;

class GtkSeeder extends Seeder
{
    public function run(): void
    {
        Gtk::create(['nama' => 'Rudi Santoso', 'jabatan' => 'Guru Matematika']);
        Gtk::create(['nama' => 'Siti Aminah', 'jabatan' => 'Guru Bahasa Indonesia']);
    }
}
