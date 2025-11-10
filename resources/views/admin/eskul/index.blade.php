@extends('layouts.app')
@section('title', 'Daftar Eskul')

@section('content')
<div style="padding: 25px;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #1e3a8a;">Daftar Ekstrakurikuler</h2>
        <a href="{{ route('admin.eskul.create') }}" 
           style="padding: 10px 18px; background: #2563eb; color: #fff; border-radius: 10px; font-size: 14px; text-decoration: none; transition: 0.3s;"
           onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
           + Tambah Eskul
        </a>
    </div>

    {{-- Grid Card --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        @forelse($eskul as $item)
            <div style="background: #fff; border-radius: 18px; padding: 20px; text-align: center; 
                        box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer;" 
                 onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 25px rgba(37,99,235,0.15)';" 
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.08)';">

                {{-- Foto Eskul --}}
                <div style="width: 130px; height: 130px; margin: 0 auto 12px; border-radius: 50%; 
                            overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                    @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 13px; color: #888;">No Photo</span>
                    @endif
                </div>

                {{-- Nama Eskul --}}
                <h4 style="margin: 8px 0 6px; font-size: 16px; font-weight: 700; color: #1e3a8a;">
                    {{ $item->nama }}
                </h4>

                {{-- Deskripsi Penuh --}}
                <p style="margin: 0 0 12px; font-size: 14px; color: #555; line-height: 1.5; text-align: justify;">
                    {{ $item->deskripsi ?? '-' }}
                </p>

                {{-- Tombol aksi --}}
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 10px;">
                    {{-- Edit --}}
                    <a href="{{ route('admin.eskul.edit', $item->id) }}" 
                       title="Edit"
                       style="width: 36px; height: 36px; background: #f59e0b; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; text-decoration: none; transition: 0.2s;"
                       onmouseover="this.style.opacity='0.85';" onmouseout="this.style.opacity='1';">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- Hapus --}}
                    <form action="{{ route('admin.eskul.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus" 
                                style="width: 36px; height: 36px; background: #dc2626; color: #fff; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; transition: 0.2s;"
                                onmouseover="this.style.opacity='0.85';" onmouseout="this.style.opacity='1';">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center; color: #555; font-size: 14px;">Belum ada Eskul.</p>
        @endforelse
    </div>
</div>
@endsection
