@extends('layouts.app')

@section('title', 'Galeri Sekolah')

@section('content')
<div class="content">
    <h3>Galeri Sekolah</h3>
    <p>Selamat datang di halaman galeri! Di sini kamu bisa lihat foto-foto kegiatan sekolah.</p>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
@endsection
