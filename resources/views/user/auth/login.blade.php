@extends('layouts.app')

@section('title', 'Login User')

@section('content')
<section class="login-shell">
  <div class="card">
    <div class="avatar-wrap">
      <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo">
    </div>
    <h1 class="title">Login User</h1>
    <p class="subtitle">Gunakan akun Anda untuk like dan komentar.</p>

    @if (session('status'))
      <div class="alert success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('user.login.post') }}" class="form" autocomplete="off">
      @csrf
      <div class="ig">
        <span class="ic">✉️</span>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email" />
      </div>
      <div class="ig pw">
        <span class="ic">🔒</span>
        <input id="password" type="password" name="password" required placeholder="Password" />
        <button class="eye" type="button" aria-label="Tampilkan/ sembunyikan sandi">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
      <label class="remember"><input type="checkbox" name="remember" /> Ingat saya</label>
      <button type="submit" class="btn primary">Login</button>
    </form>

    

    <p class="alt">Belum punya akun? <a href="{{ route('user.register') }}">Daftar</a></p>
  </div>
</section>

<style>
  body{background:linear-gradient(rgba(9,12,20,.35),rgba(9,12,20,.35)), url('{{ asset('images/loginuser.jpeg') }}') center center / cover no-repeat !important}
  .ornament-bg::before,.ornament-bg::after{display:none !important}

  .login-shell{min-height:calc(100vh - 120px);display:grid;place-items:center;padding:24px 16px;background:transparent;position:relative}
  .login-shell::before{content:"";display:none}
  .card{width:100%;max-width:420px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);padding:26px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,.35);color:#fff}
  .avatar-wrap{display:grid;place-items:center;margin-top:2px;margin-bottom:12px}
  .avatar-wrap img{width:86px;height:86px;border-radius:999px;border:3px solid #FFD700;box-shadow:0 0 15px #FFD700;object-fit:cover;background:#fff}
  .title{text-align:center;margin:0;color:#fff;font-weight:900;text-shadow:0 0 8px rgba(255,215,0,.8)}
  .subtitle{text-align:center;margin:6px 0 14px;color:#e5e7eb;opacity:.9}
  .alert{padding:10px 12px;border-radius:10px;margin-bottom:12px}
  .alert.success{background:rgba(0,128,0,.7);color:#fff;border:0}
  .alert.error{background:rgba(220,20,60,.7);color:#fff;border:0}
  .form{display:grid;gap:12px}
  .ig{display:flex;align-items:center;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:12px;padding:12px 14px;transition:border-color .2s, box-shadow .2s}
  .ig:focus-within{border-color:rgba(147,197,253,.9);box-shadow:0 0 10px #3B82F6}
  .ig input{flex:1;background:transparent;border:none;outline:none;color:#fff}
  .ig input::placeholder{color:rgba(255,255,255,.75)}
  .ic{margin-right:10px;color:#fff;opacity:.85}
  .pw{position:relative;padding-right:44px}
  .eye{position:absolute;right:8px;top:50%;transform:translateY(-50%);border:0;background:transparent;border-radius:10px;padding:6px;cursor:pointer;color:#fff;opacity:.9}
  .eye:hover{background:rgba(255,255,255,.12)}
  .remember{display:flex;align-items:center;gap:8px;color:#e5e7eb;font-size:.95rem}
  .btn{display:flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:12px;padding:12px 16px;font-weight:800;cursor:pointer;box-shadow:0 8px 18px rgba(2,6,23,.18)}
  .btn.primary{background:linear-gradient(135deg,#3B82F6,#1E3A8A);color:#fff}
  .btn.primary:hover{background:linear-gradient(135deg,#60A5FA,#2563EB);box-shadow:0 0 18px rgba(59,130,246,.6);transform:scale(1.02)}
  .btn.google{background:#fff;color:#0b1220;margin-top:10px;border:1px solid #e5e7eb}
  .btn.google:hover{filter:brightness(.98)}
  .or{display:flex;align-items:center;gap:8px;margin:10px 0}
  .or span{flex:1;height:1px;background:rgba(229,231,235,.5)}
  .or em{color:#e2e8f0;font-style:normal;font-weight:700}
  .alt{text-align:center;color:#e2e8f0;margin-top:6px}
  .alt a{color:#93c5fd;text-decoration:underline}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const eye = document.querySelector('.eye');
  const pwd = document.getElementById('password');
  if(eye && pwd){
    eye.addEventListener('click', ()=>{
      const show = pwd.type === 'password' ? 'text' : 'password';
      pwd.type = show;
      eye.innerHTML = show==='password'
        ? '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>'
        : '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.76 20.76 0 0 1 5.06-6.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a20.66 20.66 0 0 1-3.22 4.49"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    });
  }
});
</script>
@endsection
