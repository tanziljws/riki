@extends('layouts.app')

@section('title', 'Daftar Jurusan')

@section('content')
<h1>Daftar Jurusan</h1>

@if($jurusans->isEmpty())
    <p>Belum ada jurusan yang tersedia.</p>
@else
    <div class="row g-4">
        @foreach($jurusans as $jurusan)
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 h-100 text-center p-3">
                    <img src="{{ asset('storage/' . $jurusan->logo) }}" 
                         alt="Logo {{ $jurusan->nama }}" 
                         class="img-fluid mx-auto mb-3" 
                         style="max-height:120px; object-fit:contain;">
                    <h5 class="fw-bold">{{ $jurusan->nama }}</h5>
                    <p class="text-muted">{{ $jurusan->singkatan }}</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('jurusan.edit', $jurusan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus jurusan ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
