@extends('layouts.app')
@section('title', __('admin.majors.add'))

@section('content')
<div style="max-width: 550px; margin: auto; padding: 30px;">
    <div style="background: #fff; border-radius: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); padding: 35px;">
        <h2 style="color: #1e3a8a; text-align: center; margin-bottom: 30px; font-weight: 700; font-size: 24px;">
            {{ __('admin.majors.add') }}
        </h2>

        <form action="{{ route('admin.jurusan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Jurusan --}}
            <div style="margin-bottom: 22px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">{{ __('admin.majors.form.name') }}</label>
                <input type="text" name="nama" placeholder="{{ __('admin.majors.form.name_placeholder') }}" required
                       style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d1d5db; font-size: 14px; transition: all .2s;"
                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.2)';"
                       onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
            </div>

            {{-- Deskripsi --}}
            <div style="margin-bottom: 22px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">{{ __('admin.majors.form.description') }}</label>
                <textarea name="deskripsi" placeholder="{{ __('admin.majors.form.description_placeholder') }}" rows="4"
                          style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d1d5db; font-size: 14px; transition: all .2s;"
                          onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.2)';"
                          onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"></textarea>
            </div>

            {{-- Foto / Logo Jurusan --}}
            <div style="margin-bottom: 28px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; color:#1e293b;">{{ __('admin.majors.form.logo') }}</label>
                <div id="logoPreview" style="width:160px;height:120px;border:1px dashed #cbd5e1;border-radius:14px;display:grid;place-items:center;background:#f8fafc;color:#64748b;margin-bottom:10px;overflow:hidden">
                    <span style="font-size:12px">Preview</span>
                </div>
                <input id="logoInput" type="file" name="foto" accept="image/*" 
                       style="width: 100%; padding: 10px; font-size: 14px; border-radius: 12px; border: 1px solid #d1d5db; background:#f9fafb;">
            </div>

            {{-- Tombol Simpan & Batal --}}
            <div class="actions">
                <a href="{{ route('admin.jurusan.index') }}" class="btn ghost">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn primary">{{ __('admin.common.save') }}</button>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function(){
              const input = document.getElementById('logoInput');
              const box = document.getElementById('logoPreview');
              if(!input || !box) return;
              input.addEventListener('change', ()=>{
                const f = input.files && input.files[0];
                if(!f) { box.innerHTML='<span style="font-size:12px">Preview</span>'; return; }
                const url = URL.createObjectURL(f);
                box.innerHTML = '<img src="'+url+'" alt="preview" style="width:100%;height:100%;object-fit:cover">';
              });
            });
            </script>
        </form>
    </div>
</div>
<style>
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
