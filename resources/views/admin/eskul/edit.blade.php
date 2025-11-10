@extends('layouts.app')
@section('title', 'Edit Eskul')

@section('content')
<div style="max-width: 550px; margin: auto; padding: 30px;">
    <div style="background: #fff; border-radius: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); padding: 35px;">
        <h2 style="color: #1e3a8a; text-align: center; margin-bottom: 30px; font-weight: 700; font-size: 24px;">
            Edit Eskul
        </h2>

        <form action="{{ route('admin.eskul.update', $eskul->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Eskul --}}
            <div style="margin-bottom: 22px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">Nama Eskul</label>
                <input type="text" name="nama" value="{{ $eskul->nama }}" placeholder="Masukkan nama eskul" 
                       style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d1d5db; font-size: 14px; transition: all .2s;"
                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.2)';"
                       onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                       required>
            </div>

            {{-- Deskripsi --}}
            <div style="margin-bottom: 22px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">Deskripsi</label>
                <textarea name="deskripsi" rows="4" placeholder="Masukkan deskripsi eskul" 
                          style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d1d5db; font-size: 14px; transition: all .2s;"
                          onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.2)';"
                          onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                          required>{{ $eskul->deskripsi }}</textarea>
            </div>

            {{-- Foto --}}
            <div style="margin-bottom: 28px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">Foto Eskul</label>
                @if($eskul->foto)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/' . $eskul->foto) }}" alt="{{ $eskul->nama }}" style="width: 120px; border-radius: 12px;">
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" 
                       style="width: 100%; padding: 10px; font-size: 14px; border-radius: 12px; border: 1px solid #d1d5db; background:#f9fafb;">
            </div>

            {{-- Tombol --}}
            <div style="display: flex; gap: 15px;">
                <button type="submit" 
                        style="flex: 1; padding: 14px; background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s;"
                        onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
                    Simpan
                </button>
                <a href="{{ route('admin.eskul.index') }}" 
                   style="flex: 1; padding: 14px; background: #e5e7eb; color: #374151; border-radius: 12px; font-weight: 700; font-size: 16px; text-align: center; text-decoration: none; transition: all .3s;"
                   onmouseover="this.style.background='#d1d5db';"
                   onmouseout="this.style.background='#e5e7eb';">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
