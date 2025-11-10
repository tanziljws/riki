@extends('layouts.app')

@section('title', 'Register User')

@section('content')
<section class="reg-shell">
  <div class="reg-card">
    <div class="avatar-wrap">
      <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo">
    </div>

    <div class="reg-head">
      <div class="reg-title">Daftar User</div>
      <div class="reg-sub">Buat akun untuk bisa like dan komentar.</div>
    </div>

    @if ($errors->any())
      <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('user.register.post') }}" class="reg-form" autocomplete="off">
      @csrf

      <div class="fg">
        <label>Nama</label>
        <div class="ig">
          <span class="ic">👤</span>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap" />
        </div>
      </div>

      <div class="fg">
        <label>Email</label>
        <div class="ig">
          <span class="ic">✉️</span>
          <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@contoh.com" />
        </div>
      </div>

      <div class="fg">
        <label>Password</label>
        <div class="ig pw">
          <span class="ic">🔒</span>
          <input id="password" type="password" name="password" required placeholder="Minimal 6 karakter" />
          <button type="button" class="eye" data-target="password" aria-label="Tampilkan atau sembunyikan sandi">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <div class="fg">
        <label>Konfirmasi Password</label>
        <div class="ig pw">
          <span class="ic">✅</span>
          <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi password" />
          <button type="button" class="eye" data-target="password_confirmation" aria-label="Tampilkan atau sembunyikan sandi">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit" id="btnSubmit">Daftar</button>
    </form>

    <p class="hint">
      Sudah punya akun? <a href="{{ route('user.login') }}" class="to-login">Login</a>
    </p>
  </div>
</section>

<style>
body {
  background: linear-gradient(rgba(9,12,20,.4), rgba(9,12,20,.4)),
              url('{{ asset('images/loginuser.jpeg') }}') center/cover no-repeat !important;
  color: #fff;
}

.reg-shell {
  min-height: calc(100vh - 120px);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 24px 16px;
}

.reg-card {
  width: 100%;
  max-width: 520px; /* 💥 diperbesar dari 460px */
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 20px;
  box-shadow: 0 16px 40px rgba(0,0,0,0.3);
  overflow: hidden;
}

.avatar-wrap {
  display: flex;
  justify-content: center;
  margin-top: 16px;
}

.avatar-wrap img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  border: 3px solid #FFD700;
  box-shadow: 0 0 12px #FFD700;
  object-fit: cover;
}

.reg-head {
  text-align: center;
  padding: 16px;
}

.reg-title {
  font-weight: 900;
  font-size: 1.7rem;
  color: #fff;
  text-shadow: 0 0 10px rgba(255,215,0,.6);
}

.reg-sub {
  font-size: 0.95rem;
  color: #e5e7eb;
}

.alert.error {
  background: rgba(220,20,60,0.8);
  padding: 10px 14px;
  margin: 12px 22px 0;
  border-radius: 12px;
  text-align: center;
}

.reg-form {
  padding: 28px; /* 💥 dari 20px jadi 28px */
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.fg label {
  font-weight: 700;
  font-size: 0.95rem;
}

.ig {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 14px;
  padding: 12px 14px;
  transition: 0.2s;
}

.ig:focus-within {
  border-color: #3B82F6;
  box-shadow: 0 0 8px #3B82F6;
}

.ig input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  color: #fff;
  font-size: 15px;
}

.ig input::placeholder {
  color: rgba(255,255,255,0.7);
}

.eye {
  position: absolute;
  right: 14px;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.btn-submit {
  background: linear-gradient(135deg, #0b4aad, #1e3a8a);
  border: none;
  border-radius: 14px;
  color: #fff;
  padding: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: 0.2s;
}

.btn-submit:hover {
  filter: brightness(1.1);
}

.hint {
  text-align: center;
  padding-bottom: 20px;
  font-size: 0.95rem;
}

.hint a {
  color: #93c5fd;
  font-weight: 700;
}

/* RESPONSIVE */
@media (max-width: 480px) {
  .reg-card {
    max-width: 95%; /* 💥 biar pas di HP */
  }
  .avatar-wrap img {
    width: 70px;
    height: 70px;
  }
  .reg-title {
    font-size: 1.4rem;
  }
  .ig {
    padding: 10px 12px;
  }
  .ig input {
    font-size: 14px;
  }
  .btn-submit {
    font-size: 15px;
  }
  .reg-form {
    padding: 22px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const eyes = document.querySelectorAll('.eye');
  eyes.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
      }
    });
  });
});
</script>
@endsection
