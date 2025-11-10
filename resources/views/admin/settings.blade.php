@extends('layouts.app')
@section('title', app()->getLocale()==='en' ? 'Settings' : 'Pengaturan')

@section('content')
<div class="dashboard">
    <div class="card">
        <div class="card-head"><i class="fa-solid fa-gear"></i> <span data-i18n="settings_title">Pengaturan</span></div>
        @if(session('status'))
            @php $en = app()->getLocale()==='en'; @endphp
            <div style="background:#ecfdf5;border:1px solid #bbf7d0;color:#065f46;padding:10px;border-radius:10px;margin-bottom:10px;">{{ $en ? 'Settings updated successfully.' : session('status') }}</div>
        @endif
        <div class="grid-2">
            <div>
                <div class="qa-title" data-i18n="account_settings">Akun</div>
                <form method="POST" action="{{ route('admin.settings.update') }}" class="form">
                    @csrf
                    <div class="form-row">
                        <label for="email"><span data-i18n="email">Email</span></label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" />
                    </div>
                    <div class="form-row">
                        <label for="password"><span data-i18n="new_password">Kata Sandi Baru</span></label>
                        <input type="password" id="password" name="password" autocomplete="new-password" />
                    </div>
                    <div class="form-row">
                        <label for="password_confirmation"><span data-i18n="confirm_password">Konfirmasi Sandi</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" />
                    </div>
                    <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> <span data-i18n="save_account">Simpan Akun</span></button>
                </form>
            </div>
            <div>
                <div class="qa-title" data-i18n="preferences">Preferensi</div>
                @php
                    $brandName = 'SUPER ADMIN';
                    $brandLogo = asset('images/perusahaan.png');
                    $brandingJson = storage_path('app/branding.json');
                    if (file_exists($brandingJson)) {
                        $b = json_decode(@file_get_contents($brandingJson), true) ?: [];
                        if (!empty($b['brand_name'])) { $brandName = $b['brand_name']; }
                    }
                    if (file_exists(public_path('storage/branding/logo.png'))) {
                        $brandLogo = asset('storage/branding/logo.png');
                    }
                @endphp
                <form method="POST" action="{{ route('admin.settings.update') }}" id="prefForm" class="form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <label for="language"><span data-i18n="language">Bahasa</span></label>
                        @php $loc = session('locale','id'); @endphp
                        <select name="language" id="language">
                            <option value="id" {{ $loc==='id' ? 'selected' : '' }}>Indonesia</option>
                            <option value="en" {{ $loc==='en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="brand_name"><span data-i18n="brand_name">Nama Brand</span></label>
                        <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name', $brandName) }}" placeholder="Contoh: SUPER ADMIN" />
                    </div>
                    <div class="form-row">
                        <label for="brand_logo"><span data-i18n="brand_logo">Logo Brand</span></label>
                        <input type="file" id="brand_logo" name="brand_logo" accept="image/png,image/jpeg,image/webp" />
                        <div style="display:flex;align-items:center;gap:10px;margin-top:6px">
                            <img src="{{ $brandLogo }}" alt="Preview Logo" style="width:48px;height:48px;border-radius:50%;border:2px solid #d4af37;object-fit:cover;background:#fff">
                            <small data-i18n="current_logo">Logo saat ini</small>
                        </div>
                    </div>
                    <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> <span data-i18n="save_pref">Simpan Preferensi</span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard{ padding:24px; max-width:1100px; margin:0 auto; font-family:'Poppins',sans-serif; }
.card{ background:#fff; padding:16px; border:1px solid #eef0f4; border-radius:16px; box-shadow:0 10px 30px rgba(2,6,23,.06); overflow:hidden; }
.card-head{ font-weight:800; color:#111827; display:flex; align-items:center; gap:8px; margin-bottom:12px; }
.grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start; }
.grid-2 > div{ display:flex; flex-direction:column; }
.qa-title{ font-weight:800; margin-bottom:12px; color:#111827; }
.form{ display:flex; flex-direction:column; gap:14px; flex:1; width:100%; }
.form-row{ display:flex; flex-direction:column; gap:8px; }
.form-row label{ font-size:13px; color:#6b7280; }
.form-row input, .form-row select{
    width:100%; max-width:100%; box-sizing:border-box; padding:12px 14px; border:1px solid #e5e7eb; border-radius:12px; background:#fff; font-family:inherit; outline:none;
    transition:border-color .2s, box-shadow .2s;}
.form-row input:focus, .form-row select:focus{ border-color:#93c5fd; box-shadow:0 0 0 4px rgba(147,197,253,.35); }
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border-radius:12px;font-weight:800;border:1px solid transparent;cursor:pointer;max-width:100%;box-sizing:border-box;-webkit-appearance:none;appearance:none}
.btn.primary{background:#0a1f4f;color:#fff;border:2px solid #d4af37}
.btn.primary{background:#0a1f4f!important;color:#fff;border:2px solid #d4af37;padding:10px 16px;border-radius:12px;font-weight:800;-webkit-appearance:none;appearance:none}
.btn.primary:hover{filter:brightness(1.05)}
@media (max-width: 1024px){ .grid-2{ grid-template-columns:1fr; } }
@media (max-width: 768px){
  .dashboard{ padding:16px; }
  .card{ padding:14px; }
  .grid-2{ gap:14px; }
  .form{ gap:12px; }
  .btn{ width:100%; justify-content:center; }
}
</style>

<script>
window.addEventListener('DOMContentLoaded', ()=>{
  const prefForm = document.getElementById('prefForm');
  const langSel = document.getElementById('language');
  // Language will change only after clicking "Simpan Preferensi".
  // i18n
  const LOCALE = @json(session('locale','id'));
  const I18N = {
    id: { settings_title:'Pengaturan', account_settings:'Akun', email:'Email', new_password:'Kata Sandi Baru', confirm_password:'Konfirmasi Sandi', save_account:'Simpan Akun', preferences:'Preferensi', language:'Bahasa', theme:'Tema', light:'Terang', dark:'Gelap', save_pref:'Simpan Preferensi', brand_name:'Nama Brand', brand_logo:'Logo Brand', current_logo:'Logo saat ini' },
    en: { settings_title:'Settings', account_settings:'Account', email:'Email', new_password:'New Password', confirm_password:'Confirm Password', save_account:'Save Account', preferences:'Preferences', language:'Language', theme:'Theme', light:'Light', dark:'Dark', save_pref:'Save Preferences', brand_name:'Brand Name', brand_logo:'Brand Logo', current_logo:'Current Logo' }
  };
  document.querySelectorAll('[data-i18n]').forEach(el=>{
    const key = el.getAttribute('data-i18n');
    const str = (I18N[LOCALE] && I18N[LOCALE][key]) || el.textContent;
    if (str) el.textContent = str;
  });
});
</script>
@endsection
