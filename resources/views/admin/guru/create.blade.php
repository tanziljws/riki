@extends('layouts.app')
@section('title', __('admin.teachers.add'))

@section('content')
<div style="max-width: 640px; margin: 0 auto; padding: 16px;">
    <div style="background: #fff; border-radius: 18px; border:1px solid #eef0f4; box-shadow: 0 8px 25px rgba(0,0,0,0.06); padding: 28px;">
        <h2 style="color: #0a1f4f; text-align: center; margin-bottom: 20px; font-weight: 800; font-size: 22px;">
            {{ __('admin.teachers.add') }}
        </h2>

        <form action="{{ route('admin.guru.store') }}" method="POST" style="display:grid; gap:14px;">
            @csrf

            {{-- Nama Guru --}}
            <div class="fi">
                <label class="lb">{{ __('admin.teachers.form.name') }}</label>
                <input class="ip" type="text" name="nama" placeholder="{{ __('admin.teachers.form.name_placeholder') }}" required>
            </div>

            {{-- Jabatan --}}
            <div class="fi">
                <label class="lb">{{ __('admin.teachers.form.position') }}</label>
                <input class="ip" type="text" name="jabatan" placeholder="{{ __('admin.teachers.form.position_placeholder') }}">
            </div>

            

            {{-- Tombol --}}
            <div class="actions">
                <button type="submit" class="btn primary">{{ __('admin.common.save') }}</button>
                <a href="{{ route('admin.guru.index') }}" class="btn ghost">{{ __('admin.common.cancel') }}</a>
            </div>
        </form>
    </div>
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
