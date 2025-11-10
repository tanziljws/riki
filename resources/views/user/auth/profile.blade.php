@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
@php
    $name = strtolower($user->name ?? '');
    $isFemale = false;
    foreach (['sri','siti','ayu','putri','putry','dewi','citra','rani','wati','nisa','anis','anisa','nisaa','nurul','amel','amelia','lia','linda','dinda','rina','rina','intan','vika','vika','fitri','fitria','fitriyah'] as $kw) {
        if (str_contains($name, $kw)) { $isFemale = true; break; }
    }
    $style = 'adventurer';
    $maleUrl = 'https://api.dicebear.com/7.x/'.$style.'/svg?seed='.urlencode($user->email).'&accessoriesProbability=60&facialHairProbability=80';
    $femaleUrl = 'https://api.dicebear.com/7.x/'.$style.'/svg?seed='.urlencode($user->email).'&hair=long01,long02,long03,long11&facialHairProbability=0&accessoriesProbability=60';
    $fallback = $isFemale ? $femaleUrl : $maleUrl;
@endphp
<section class="u-profile">
    <div class="u-header">
        <div class="u-avatar">
            <img id="avatarImg" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : $fallback }}" alt="Avatar">
        </div>
        <div class="u-meta">
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
        </div>
        <div class="u-actions">
            <form id="avatarForm" action="{{ route('user.avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input id="avatarInput" type="file" name="avatar" accept="image/*" style="display:none" />
                <button type="button" id="changeAvatarBtn" class="btn primary equal">Ubah Profil</button>
            </form>
            <form action="{{ route('user.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn danger equal">Keluar</button>
            </form>
        </div>
    </div>

    <div class="u-grid">
        <div class="u-card">
            <div class="u-card-title">Informasi Akun</div>
            <div class="u-row"><span>Nama</span><strong>{{ $user->name }}</strong></div>
            <div class="u-row"><span>Email</span><strong>{{ $user->email }}</strong></div>
            <div class="u-row"><span>Bergabung</span><strong>{{ optional($user->created_at)->format('d M Y') }}</strong></div>
        </div>
        <div class="u-card">
            <div class="u-card-title">Keamanan</div>
            <div class="u-text">Ubah password tersedia di halaman <a href="{{ route('user.settings') }}">Pengaturan</a>.</div>
            <div class="u-text subtle">Untuk foto profil kustom, upload avatar bisa diaktifkan di Pengaturan.</div>
        </div>
    </div>
</section>

<style>
.u-profile{max-width:980px;margin:28px auto;padding:0 16px}
.u-header{display:grid;grid-template-columns:auto 1fr auto;gap:16px;align-items:center;background:linear-gradient(135deg,#fff 0%,#f5f7fb 50%,#eef2ff 100%);padding:16px;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 12px 30px rgba(2,6,23,.06)}
.u-avatar{position:relative;width:78px;height:78px;border-radius:999px;display:grid;place-items:center;background:#fff;border:3px solid #d4af37;overflow:hidden}
.u-avatar img{width:100%;height:100%;object-fit:cover;border-radius:inherit}
.u-meta h1{margin:0;font-size:1.4rem;color:#0b4aad;font-weight:800}
.u-meta p{margin:4px 0 0;color:#64748b}
.u-actions{display:flex;gap:10px;align-items:center}
.btn{display:inline-flex;align-items:center;gap:8px;border:none;cursor:pointer;padding:10px 14px;border-radius:10px;font-weight:700;text-decoration:none}
.btn.equal{min-width:140px;justify-content:center}
.btn.primary{background:#0b4aad;color:#fff}
.btn.primary:hover{background:#0a3f8e}
.btn.danger{background:#e11d48;color:#fff}
.btn.danger:hover{background:#be123c}
.u-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
.u-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;box-shadow:0 8px 24px rgba(2,6,23,.06)}
.u-card-title{color:#0b4aad;font-weight:800;margin-bottom:10px}
.u-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px dashed #e5e7eb}
.u-row:last-child{border-bottom:none}
.u-row span{color:#64748b}
.u-text{color:#334155;line-height:1.5}
.u-text.subtle{color:#64748b;margin-top:8px}
@media (max-width: 900px){.u-header{grid-template-columns:auto 1fr;gap:12px}.u-actions{grid-column:1/-1;justify-content:flex-start}}
@media (max-width: 640px){.u-avatar{width:64px;height:64px}.u-meta h1{font-size:1.2rem}.u-grid{grid-template-columns:1fr}.u-actions{flex-wrap:wrap}.btn{width:100%;justify-content:center}}
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.getElementById('changeAvatarBtn');
  const input = document.getElementById('avatarInput');
  const form = document.getElementById('avatarForm');
  const img = document.getElementById('avatarImg');
  if(btn && input){
    btn.addEventListener('click', ()=> input.click());
    input.addEventListener('change', ()=>{
      if(input.files && input.files[0]){
        const file = input.files[0];
        const url = URL.createObjectURL(file);
        if(img) img.src = url; // preview
        form.submit(); // auto submit
      }
    });
  }
});
</script>
@endsection
