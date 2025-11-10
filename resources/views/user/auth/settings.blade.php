@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<section class="auth-page" style="max-width:620px;margin:40px auto;padding:24px;background:#fff;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.08)">
    <h1 style="margin:0 0 12px;font-weight:800;color:#004aad">Pengaturan Akun</h1>
    <p style="margin:0 0 16px;color:#555">Ubah nama, email, dan kata sandi akun Anda.</p>

    @if (session('status'))
        <div style="background:#e8f5e9;color:#1b5e20;padding:10px 12px;border-radius:8px;margin-bottom:12px;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fdecea;color:#b71c1c;padding:10px 12px;border-radius:8px;margin-bottom:12px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('user.settings.update') }}" style="display:grid;gap:12px">
        @csrf
        <div>
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px" />
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px" />
        </div>
        <hr style="border:none;border-top:1px solid #e5e7eb;margin:10px 0" />
        <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:12px">
            <div style="font-weight:700;color:#0b4aad;margin-bottom:8px">Ganti Kata Sandi (opsional)</div>
            <div style="color:#64748b;margin-bottom:10px;font-size:.95rem">Isi bagian ini hanya jika Anda ingin mengganti kata sandi.</div>
            <div style="display:grid;gap:10px">
                <div>
                    <label>Kata sandi saat ini</label>
                    <div class="pw-group">
                        <input id="current_password" class="pw-input" type="password" name="current_password" placeholder="Masukkan kata sandi sekarang" />
                        <button type="button" class="pw-toggle" data-toggle="current_password" aria-label="Tampilkan/ sembunyikan sandi">
                          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b4aad" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <div style="color:#b91c1c;font-size:.9rem;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>Kata sandi baru</label>
                    <div class="pw-group">
                        <input id="password" class="pw-input" type="password" name="password" placeholder="Minimal 6 karakter" />
                        <button type="button" class="pw-toggle" data-toggle="password" aria-label="Tampilkan/ sembunyikan sandi">
                          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b4aad" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <div style="color:#b91c1c;font-size:.9rem;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>Konfirmasi kata sandi baru</label>
                    <div class="pw-group">
                        <input id="password_confirmation" class="pw-input" type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" />
                        <button type="button" class="pw-toggle" data-toggle="password_confirmation" aria-label="Tampilkan/ sembunyikan sandi">
                          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b4aad" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <a href="{{ url()->previous() }}" style="padding:10px 14px;border-radius:10px;border:1px solid #cbd5e1;color:#0b4aad;font-weight:700;background:#f8fafc">Batal</a>
            <button type="submit" style="background:#004aad;color:#fff;border:none;padding:12px 16px;border-radius:10px;font-weight:700">Simpan Perubahan</button>
        </div>
    </form>
</section>
<style>
  .pw-group { display:flex; align-items:center; width:100%; background:#fff; border:1px solid #cbd5e1; border-radius:10px; overflow:hidden; }
  .pw-input { flex:1; min-width:0; padding:10px 12px; border:none; outline:none; }
  .pw-toggle { flex:0 0 auto; height:100%; border:none; background:#eef2ff; padding:8px 10px; cursor:pointer; display:flex; align-items:center; justify-content:center; border-left:1px solid #dbeafe; }
  .pw-toggle:hover { background:#dde7ff; }
  @media (max-width: 480px){ .pw-toggle{ padding:6px 8px; } }
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('button[data-toggle]').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const id = btn.getAttribute('data-toggle');
      const input = document.getElementById(id);
      if(!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.innerHTML = input.type === 'password'
        ? '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b4aad" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>'
        : '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b4aad" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.76 20.76 0 0 1 5.06-6.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a20.66 20.66 0 0 1-3.22 4.49"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    });
  });
});
</script>
@endsection