<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jurusan; // pastikan ada model Jurusan

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all(); // ambil semua data jurusan
        return view('user.jurusan.index', compact('jurusan'));
    }
}
