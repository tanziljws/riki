<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SMKN 4</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo-smkn.jpg') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/logo-smkn.jpg') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body, html { height: 100%; overflow: hidden; background:#0b1225; }

    /* New background: left yellow, right navy with layered blue cards */
    .bg {
      position: fixed; inset: 0; z-index: 0; pointer-events:none;
      background: url('{{ asset('images/loginadmin.png') }}') center center / cover no-repeat;
      opacity: 1;
    }
    .bg::before, .bg::after { content: none; }
    .bg::before { }
    .bg::after  { }
    .bg .s3 { display: none; }

    /* Login Box */
    .login-box {
      position: relative;
      z-index: 1;
      width: 90%;
      max-width: 360px;
      margin: auto;
      top: 50%;
      transform: translate3d(0,-50%,0);
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.15);
      padding: 40px 35px;
      border-radius: 20px;
      text-align: center;
      backdrop-filter: blur(12px);
      box-shadow: 0 0 30px rgba(0,0,0,0.5);
    }

    /* Logo */
    .login-box img {
      width: 100px; height: 100px;
      border-radius: 50%;
      border: 3px solid #FFD700;
      box-shadow: 0 0 15px #FFD700;
      margin-bottom: 15px;
      transform: translate3d(0,0,0);
      transform-origin: 50% 50%;
      object-fit: cover;    /* biar bulat sempurna */
      object-position: center;
    }

    .login-box h2 {
      color: #fff;
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 0 0 8px rgba(255,215,0,0.8);
    }

    .login-box p.subtext { color:#e5e7eb; font-size:12px; margin-top:-10px; margin-bottom:12px; opacity:.9; }

    /* Input */
    .field { position:relative; margin:12px 0; min-height: 46px; }
    .field input {
      width: 100%;
      height: 46px;
      padding: 12px 56px 12px 44px;
      border: none;
      border-radius: 10px;
      background: rgba(255,255,255,0.15);
      color: #fff;
      font-size: 14px;
      line-height: 22px;
      outline: none;
      transition: 0.3s;
      display: block;
    }
    .field input:focus {
      background: rgba(255,255,255,0.25);
      box-shadow: 0 0 10px #3B82F6;
    }
    .field input::placeholder { color: rgba(255,255,255,0.7); }

    .field .icon { position:absolute; left:12px; top:0; bottom:0; margin:auto 0; height:16px; color:#fff; opacity:.8; display:inline-flex; align-items:center; }
    .field .toggle-pass { position:absolute; right:12px; top:0; bottom:0; margin:auto 0; background:transparent; border:none; color:#fff; opacity:.9; cursor:pointer; width:32px; height:32px; padding:0; line-height:0; display:grid; place-items:center; border-radius:8px; z-index:2; outline:none; }
    .field .toggle-pass:hover { background:rgba(255,255,255,.12); }

    /* Button */
    .login-box button {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 14px;
      margin-top: 14px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #3B82F6, #1E3A8A);
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    .login-box button:hover:not(:disabled) {
      background: linear-gradient(135deg, #60A5FA, #2563EB);
      box-shadow: 0 0 18px rgba(59,130,246,0.6);
      transform: scale(1.03);
    }
    .login-box button:disabled {
      background: linear-gradient(135deg, #1E40AF, #1E3A8A);
      cursor: not-allowed;
      opacity: 0.85;
    }

    /* Spinner */
    .spinner {
      border: 3px solid #fff;
      border-top: 3px solid transparent;
      border-radius: 50%;
      width: 16px; height: 16px;
      animation: spin 1s linear infinite;
    }

    /* Back Button */
    .back-btn {
      display: inline-block;
      margin-top: 18px;
      font-size: 14px;
      color: #ffffff;
      text-decoration: none;
      transition: 0.3s;
    }
    .back-btn:hover {
      color: #fff;
      text-shadow: 0 0 8px #FFD700;
    }

    /* Alerts */
    .alert {
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 15px;
      font-size: 14px;
      animation: fadeDown 0.5s ease;
    }
    .alert-error { background: rgba(220,20,60,0.7); color: #fff; }
    .alert-success { background: rgba(0,128,0,0.7); color: #fff; }

    /* Animations */
    @keyframes flyIn {
      from { opacity: 0; transform: translate3d(0,-70%,0) scale(0.96); }
      to   { opacity: 1; transform: translate3d(0,-50%,0) scale(1); }
    }
    /* Logo flies in on a smooth curved path and stops exactly centered */
    @keyframes logoFlyIn {
      0% { opacity: 0; transform: translate3d(160px,-140px,0) scale(0.85) rotate(-8deg); filter: drop-shadow(0 6px 14px rgba(0,0,0,.25)); }
      55% { opacity: 1; transform: translate3d(-12px,6px,0) scale(1.03) rotate(2deg); }
      70% { transform: translate3d(6px,-4px,0) scale(0.995); }
      100% { opacity: 1; transform: translate3d(0,0,0) scale(1) rotate(0deg); }
    }
    @media (prefers-reduced-motion: reduce) {
      .login-box, .login-box img { animation-duration: 1ms; animation-iteration-count: 1; }
    }
    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes float {
      0%,100% { transform: translate3d(0,0,0) scale(1) rotate(0deg); }
      50% { transform: translate3d(0,-10px,0) scale(1.01) rotate(0.2deg); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes bgFade { from { opacity: 0; transform: translateZ(0); } to { opacity: 1; transform: translateZ(0); } }
    /* Improve back link contrast on mobile */
    @media (max-width: 768px){
      .back-btn{ color:#ffffff !important; text-shadow:0 0 6px rgba(0,0,0,.45); }
    }
  </style>
</head>
<body>
  <!-- New graphic background -->
  <div class="bg"><div class="s3"></div></div>

  <!-- Login Box -->
  <div class="login-box">
    <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo RG">
    <h2>Login Admin</h2>
    <p class="subtext">Gunakan akun admin untuk mengelola konten.</p>

    <!-- Error Alert -->
    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <!-- Success Alert -->
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="loginForm" action="{{ route('admin.login.post') }}" method="POST" novalidate autocomplete="off">
      @csrf
      <div class="field">
        <i class="fa-solid fa-envelope icon" aria-hidden="true"></i>
        <input type="email" name="email" placeholder="Email" autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false" inputmode="email" aria-autocomplete="none" required aria-label="Email" autofocus>
      </div>
      <div class="field">
        <i class="fa-solid fa-lock icon" aria-hidden="true"></i>
        <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password" required aria-label="Password">
        <button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan/Sembunyikan password">
          <i class="fa-regular fa-eye" id="eyeIcon"></i>
        </button>
      </div>
      <button type="submit" id="loginBtn">
        <span id="btnText">Login</span>
      </button>
    </form>

    <a href="{{ url('/') }}" class="back-btn">← Kembali ke Home</a>
  </div>

  <script>
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');
    const pass = document.getElementById('password');
    const toggle = document.getElementById('togglePass');
    const eye = document.getElementById('eyeIcon');

    form.addEventListener('submit', () => {
      btn.disabled = true;
      btn.innerHTML = '<div class="spinner"></div> <span>Memproses...</span>';
    });

    if (toggle && pass) {
      toggle.addEventListener('click', () => {
        const isText = pass.type === 'text';
        pass.type = isText ? 'password' : 'text';
        eye.classList.toggle('fa-eye');
        eye.classList.toggle('fa-eye-slash');
      });
    }
  </script>
</body>
</html>
