<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru; // pastikan model Guru sudah ada

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all(); // ambil semua data guru
        return view('user.guru.index', compact('guru'));
    }
}
