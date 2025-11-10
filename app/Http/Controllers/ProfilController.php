<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        // Ambil semua data guru dari database
        $gurus = Guru::all();

        // Kirim ke view profil.blade.php
        return view('profil', compact('gurus'));
    }
}
