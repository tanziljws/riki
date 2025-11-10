@extends('layouts.app')
@section('title', __('admin.majors.edit_title'))

@section('content')
<div style="padding: 25px;">

    {{-- Header --}}
    <div style="margin-bottom: 25px;">
        <h3 style="font-size: 22px; font-weight: 700; color: #1e3a8a;">{{ __('admin.majors.edit_title') }}</h3>
    </div>

    {{-- Form --}}
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST" enctype="multipart/form-data" style="display:grid;gap:14px;max-width:100%;width:100%;box-sizing:border-box">
            @csrf
            @method('PUT')

            {{-- Nama Jurusan --}}
            <div class="fi">
                <label class="lb">{{ __('admin.majors.form.name') }}</label>
                <input class="ip" type="text" name="nama" required value="{{ old('nama', $jurusan->nama) }}">
            </div>

            {{-- Deskripsi --}}
            <div class="fi">
                <label class="lb">{{ __('admin.majors.form.description') }}</label>
                <textarea class="ip" name="deskripsi" rows="3">{{ old('deskripsi', $jurusan->deskripsi) }}</textarea>
            </div>

            {{-- Foto / Logo --}}
            <div class="fi">
                <label class="lb">{{ __('admin.majors.form.logo') }}</label>
                <div id="logoPreviewEdit" style="width:100%;max-width:280px;border:1px dashed #cbd5e1;border-radius:14px;display:block;background:#f8fafc;color:#64748b;margin-bottom:10px;padding:8px">
                    @if($jurusan->foto)
                        <img src="{{ asset('storage/'.$jurusan->foto) }}" alt="Foto Jurusan" style="display:block;width:100%;height:auto;object-fit:contain;background:#fff;border-radius:10px">
                    @else
                        <span style="font-size:12px">Preview</span>
                    @endif
                </div>
                <input id="logoInputEdit" class="ip" type="file" name="foto" accept="image/*" style="padding:10px">
            </div>

            {{-- Tombol --}}
            <div class="actions">
                <a href="{{ route('admin.jurusan.index') }}" class="btn ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn primary">{{ __('admin.common.save') }}</button>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function(){
              const input = document.getElementById('logoInputEdit');
              const box = document.getElementById('logoPreviewEdit');
              if(!input || !box) return;
              input.addEventListener('change', ()=>{
                const f = input.files && input.files[0];
                if(!f){ return; }
                const url = URL.createObjectURL(f);
                box.innerHTML = '<img src="'+url+'" alt="preview" style="display:block;width:100%;height:auto;object-fit:contain;background:#fff;border-radius:10px">';
              });
            });
            </script>

        </form>
    </div>
    <style>
    .fi{display:grid;gap:8px}
    .lb{font-weight:800;color:#0a1f4f}
    .ip{width:100%;max-width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:12px;outline:none;background:#fff;font-size:14px}
    .ip:focus{border-color:#93c5fd;box-shadow:0 0 0 4px rgba(147,197,253,.25)}
    .actions{display:flex;gap:10px;margin-top:4px;width:100%;box-sizing:border-box}
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border-radius:12px;font-weight:800;border:1px solid transparent;cursor:pointer;max-width:100%;box-sizing:border-box;-webkit-appearance:none;appearance:none}
    .btn.primary{background:#0a1f4f;color:#fff;border:2px solid #d4af37}
    .btn.ghost{background:#fff;border:1px solid #e5e7eb;color:#0f172a;text-decoration:none}
    .btn.primary{background:#0a1f4f!important;color:#fff;border:2px solid #d4af37;padding:10px 16px;border-radius:12px;font-weight:800;-webkit-appearance:none;appearance:none}
    .btn.primary:hover{filter:brightness(1.05)}
    @media (max-width: 768px){
      .actions{width:100%; flex-direction:column; align-items:stretch}
      .btn{width:100%; max-width:none; margin:0}
      .actions .btn.primary{ order:-1; }
    }
    </style>
@endsection
