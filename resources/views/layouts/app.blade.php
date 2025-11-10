<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        // Use dynamic branding only for admin; keep user/guest logo unchanged
        if (request()->is('admin/*')) {
            $brandFavicon = asset('images/perusahaan.png');
            if (file_exists(public_path('storage/branding/logo.png'))) {
                $brandFavicon = asset('storage/branding/logo.png');
            }
        } else {
            $brandFavicon = asset('images/logo-smkn.jpg');
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $brandFavicon }}">
    <link rel="apple-touch-icon" href="{{ $brandFavicon }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        :root{ --gold:#d4af37; --deep-blue:#0a1f4f; --royal-blue:#0b3c8f; --light-blue:#eaf2ff; }
        body { font-family: 'Poppins', sans-serif; margin:0; background:linear-gradient(180deg,#f7f9fc 0%, #ffffff 100%); color:#333; overflow-x:hidden; }
        html { overflow-x:hidden; }
        a { text-decoration: none; }
        main { padding:80px 30px 30px; position: relative; z-index: 1; }

        /* Subtle professional ornaments (user pages only) */
        .ornament-bg::before,
        .ornament-bg::after {
            content:""; position: fixed; inset:0; pointer-events:none; z-index:0;
            background: none;
        }
        /* Top-left blue glow + faint grid */
        .ornament-bg::before {
            background:
              /* soft grid */
              linear-gradient(transparent 23px, rgba(0,0,0,0.03) 24px) 0 0/100% 24px,
              linear-gradient(90deg, transparent 23px, rgba(0,0,0,0.03) 24px) 0 0/24px 100%,
              /* blue glow */
              radial-gradient(420px 320px at 6% 10%, rgba(0,74,173,0.12), rgba(0,74,173,0) 60%);
            mask: linear-gradient(#000, rgba(0,0,0,0.85));
        }
        /* Bottom-right gold glow */
        .ornament-bg::after {
            background:
              radial-gradient(520px 380px at 96% 92%, rgba(212,175,55,0.12), rgba(212,175,55,0) 65%);
            mask: linear-gradient(rgba(0,0,0,0.85), #000);
        }
        @media (max-width: 992px) {
          .ornament-bg::before { background:
              linear-gradient(transparent 23px, rgba(0,0,0,0.025) 24px) 0 0/100% 24px,
              linear-gradient(90deg, transparent 23px, rgba(0,0,0,0.025) 24px) 0 0/24px 100%,
              radial-gradient(320px 240px at 8% 10%, rgba(0,74,173,0.10), rgba(0,74,173,0) 60%);
          }
          .ornament-bg::after { background:
              radial-gradient(420px 300px at 92% 92%, rgba(212,175,55,0.10), rgba(212,175,55,0) 65%);
          }
        }
        @media (max-width: 768px) {
          .ornament-bg::before, .ornament-bg::after { display:none; }
        }

        /* Public corner logo watermark (disabled) */
        .ornament-logo{ display:none !important; }

        /* Admin theme (blue/gold) */
        html, body { height:100%; }
        .admin-theme { background: linear-gradient(180deg,#f7f9fc,#ffffff); }
        .admin-theme .admin-topbar strong { color:#0a1f4f; }

        /* Admin sidebar layout */
        .admin-shell { display:flex; min-height:100vh; overflow-x:hidden; }
        .admin-sidebar {
            position:fixed; left:0; top:0; height:100vh; width:260px;
            background: var(--deep-blue); /* solid deep blue */
            color:#eaf2ff; padding:18px 14px; box-shadow: 6px 0 24px rgba(2,6,23,0.25);
            border-right:1px solid rgba(255,255,255,0.08);
            overflow:hidden; overflow-y:auto; -webkit-overflow-scrolling:touch;
            box-shadow: 6px 0 24px rgba(2,6,23,0.25), inset 0 0 0 1px rgba(212,175,55,0.12); /* subtle gold line */
            position: fixed !important; transform: translateZ(0);
        }
        .admin-sidebar::before { content:none; }
        .admin-sidebar::after{ display:none; content:none; }
        .admin-sidebar .brand { display:flex; align-items:center; gap:12px; padding:10px 12px; margin-bottom:12px; }
        .admin-sidebar .brand img { width:44px; height:44px; border-radius:50%; border:2px solid #d4af37; box-shadow:0 0 0 4px rgba(212,175,55,0.18); object-fit:cover; background:#fff; }
        .admin-sidebar .brand .title { font-weight:800; letter-spacing:.3px; color:#d4af37; }
        .admin-sidebar nav { margin-top:10px; display:flex; flex-direction:column; gap:6px; }
        .admin-link { display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:12px; color:#eaf2ff; font-weight:600; transition:.2s; position:relative; }
        .admin-link i { width:32px; height:32px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:14px; color:#ffffff; background: var(--royal-blue); border:2px solid var(--gold); box-shadow: 0 3px 10px rgba(0,0,0,0.15); }
        .admin-link:hover { background:rgba(255,255,255,0.10); transform: translateX(2px); box-shadow: inset 0 0 0 1px rgba(212,175,55,0.25); }
        .admin-link.active { background:rgba(212,175,55,0.18); color:#fff; box-shadow: inset 0 0 0 1px rgba(212,175,55,0.55); }
        .admin-link.active i { background:var(--gold); color:var(--deep-blue); border-color:#f1cf6b; box-shadow: 0 6px 16px rgba(212,175,55,0.35); }
        .admin-link.active::before { content:""; position:absolute; left:8px; top:50%; transform:translateY(-50%); width:6px; height:6px; border-radius:50%; background:#d4af37; box-shadow:0 0 0 4px rgba(212,175,55,0.22); }
        .admin-sidebar .section { margin:12px 6px 6px; font-size:12px; text-transform:uppercase; letter-spacing:.8px; color:#dbeafe; opacity:.85; }
        .admin-footer { margin-top:auto; padding:12px; font-size:12px; color:#94a3b8; opacity:.9; }

        .admin-content { flex:1; display:flex; flex-direction:column; min-width:0; margin-left:260px; padding-left:12px; }
        .admin-topbar {
            height:64px; display:flex; align-items:center; justify-content:space-between;
            padding:0 18px 0 16px; background:#ffffff; backdrop-filter:saturate(100%);
            border:1px solid rgba(0,0,0,0.06); border-radius:12px; box-shadow:0 6px 16px rgba(2,6,23,0.06);
            position:sticky; top:12px; z-index:10; margin:12px 24px 16px 28px; /* beri jarak ekstra dari sidebar */
        }
        .admin-topbar .left { display:flex; align-items:center; gap:10px; }
        .admin-topbar .menu-btn { display:inline-flex; width:36px; height:36px; border-radius:10px; border:1px solid rgba(0,0,0,0.08); background:#fff; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 1px 4px rgba(0,0,0,0.06); }
        .admin-topbar .right { display:flex; align-items:center; gap:12px; }
        .admin-topbar .right span { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:160px; }
        .btn-logout, .btn-login { border:2px solid var(--gold); padding:10px 16px; color:#fff; border-radius:10px; font-weight:700; cursor:pointer; transition:all 0.25s ease; font-size:13px; display:flex; align-items:center; gap:6px; background: linear-gradient(135deg, var(--royal-blue), #1E3A8A); box-shadow:0 4px 12px rgba(2,6,23,0.12); }
        .btn-login:hover, .btn-logout:hover { filter:brightness(1.05); transform:translateY(-1px); }

        /* Generic admin components colorization */
        .admin-theme .badge, .admin-theme .tag { background: rgba(212,175,55,0.18); color:#0a1f4f; border:1px solid rgba(212,175,55,0.45); }
        .admin-theme .btn-primary, .admin-theme button.primary { background: linear-gradient(135deg, #3B82F6, #1E3A8A); color:#fff; border:none; }
        .admin-theme .btn-primary:hover, .admin-theme button.primary:hover { filter:brightness(1.03); }
        .admin-theme .card, .admin-theme .panel { background:#fff; border:1px solid #eef2f7; border-radius:12px; box-shadow:0 6px 16px rgba(2,6,23,0.06); }

        .admin-main { padding:12px 24px 24px; overflow-x:hidden; }

        /* Collapse (mobile) */
        @media (max-width: 992px) {
            .admin-content { margin-left:0; }
            .admin-topbar { margin:10px; top:10px; border-radius:12px; }
            .admin-sidebar { position:fixed; left:-280px; top:0; z-index:1200; transition:left .25s ease; width:260px; }
            .admin-sidebar.open { left:0; }
            .admin-overlay { position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1190; display:none; }
            .admin-overlay.show { display:block; }
        }
        /* no scroll lock required; content stays scrollable while sidebar open */
        @media (max-width: 480px) {
            .admin-topbar .right span { display:none; }
            .btn-logout { padding:8px 10px; font-size:12px; }
        }

        /* User navbar */
        .navbar { position: fixed; top: 0; left: 0; right: 0; height: 75px; display: flex !important; align-items: center; gap: 20px; padding: 0 50px; background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); box-shadow: 0 6px 25px rgba(0,0,0,0.05); z-index: 5000; transition: all 0.3s; }
        .navbar.shrink { height: 60px; padding: 0 30px; }
        .logo { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 1.3rem; color: #004aad; }
        .logo img { height: 50px; width: 50px; border-radius: 50%; border: 2px solid #004aad; object-fit: cover; }
        .right-group { margin-left:auto; display:flex; align-items:center; gap:16px; }
        .nav-links { display:flex; align-items:center; gap:18px; }
        .nav-links a { color: #004aad; font-weight: 600; position: relative; transition: 0.3s; }
        .nav-links a.active, .nav-links a:hover { color: #d4af37; }
        .nav-links a::after { content:""; position:absolute; bottom:-5px; left:0; height:2px; width:0; background:#d4af37; transition:0.3s; }
        .nav-links a:hover::after, .nav-links a.active::after { width:100%; }
        .btn-login-user { margin-left:auto; background:#004aad; color:#fff !important; padding:10px 18px; border-radius:9999px; font-weight:600; transition:0.25s; display:inline-flex; align-items:center; gap:8px; border:2px solid #d4af37; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .btn-login-user:hover { transform:translateY(-1px); box-shadow:0 8px 18px rgba(0,0,0,0.15); background:#003a8e; }
        .btn-login-user img { width:28px; height:28px; border-radius:9999px; border:2px solid #d4af37; object-fit:cover; }
        .profile-wrap { position:relative; }
        .profile-btn { display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:9999px; background:transparent; color:#004aad; border:1px solid #dbeafe; cursor:pointer; font-weight:700; }
        .profile-btn img { width:26px; height:26px; border-radius:9999px; object-fit:cover; }
        .profile-btn span { max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:inline-block; }
        .profile-btn:hover { background:#f3f6fb; }
        .profile-menu { position:absolute; right:0; top:calc(100% + 8px); min-width:190px; background:#fff; border:1px solid rgba(0,0,0,.08); border-radius:12px; box-shadow:0 12px 30px rgba(0,0,0,.12); padding:8px; display:none; z-index:1200; }
        .profile-menu.show { display:block; }
        .profile-menu a, .profile-menu button { width:100%; display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; color:#0b4aad; background:transparent; border:none; cursor:pointer; font-weight:600; text-align:left; }
        .profile-menu a:hover, .profile-menu button:hover { background:#f3f6fb; }
        .nav-toggle { display:none; font-size:1.6rem; cursor:pointer; }
        @media (max-width: 768px) {
            .navbar { padding: 0 16px; }
            .logo img { height:40px; width:40px; }
            .right-group { gap:10px; }
            .btn-login-user { padding:8px 12px; font-size:.9rem; }
            .nav-links { display:none; flex-direction:column; gap:15px; background:rgba(255,255,255,0.95); position:absolute; top:60px; left:0; right:0; padding:16px; border-radius:0 0 12px 12px; box-shadow:0 8px 25px rgba(0,0,0,0.1); }
            .nav-links.active { display:flex; }
            .nav-toggle { display:block; }
            .profile-btn span { display:none; }
        }
    </style>
</head>
<body class="{{ request()->is('admin/*') ? 'admin-theme' : 'ornament-bg' }}">
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
    @if(request()->is('admin/*'))
        <div class="admin-shell">
            <aside class="admin-sidebar" id="adminSidebar">
                <div class="brand">
                    <img src="{{ $brandLogo }}" alt="Logo">
                    <div class="title">{{ $brandName }}</div>
                </div>
                <div class="section">{{ __('admin.sidebar.menu') }}</div>
                <nav>
                    <a class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-gauge-high"></i> <span>{{ __('admin.sidebar.dashboard') }}</span>
                    </a>
                    <a class="admin-link {{ request()->is('admin/galeri*') ? 'active' : '' }}" href="{{ route('admin.galeri.index') }}">
                        <i class="fa-solid fa-images"></i> <span>{{ __('admin.sidebar.gallery') }}</span>
                    </a>
                    <a class="admin-link {{ request()->is('admin/guru*') ? 'active' : '' }}" href="{{ route('admin.guru.index') }}">
                        <i class="fa-solid fa-chalkboard-user"></i> <span>{{ __('admin.sidebar.teachers') }}</span>
                    </a>
                    <a class="admin-link {{ request()->is('admin/jurusan*') ? 'active' : '' }}" href="{{ route('admin.jurusan.index') }}">
                        <i class="fa-solid fa-layer-group"></i> <span>{{ __('admin.sidebar.majors') }}</span>
                    </a>
                    <a class="admin-link {{ request()->is('admin/aktivitas*') ? 'active' : '' }}" href="{{ url('admin/aktivitas') }}">
                        <i class="fa-solid fa-bolt"></i> <span>{{ __('admin.sidebar.activity') }}</span>
                    </a>
                    <a class="admin-link {{ request()->is('admin/settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                        <i class="fa-solid fa-gear"></i> <span>{{ __('admin.sidebar.settings') }}</span>
                    </a>
                    <div class="section" style="margin-top:14px">{{ __('admin.sidebar.settings_section') }}</div>
                    <a class="admin-link {{ request()->is('admin/management*') ? 'active' : '' }}" href="{{ url('admin/management') }}">
                        <i class="fa-solid fa-user-shield"></i> <span>{{ __('admin.sidebar.account_mgmt') }}</span>
                    </a>
                </nav>
                <div class="admin-footer">© {{ date('Y') }} SMKN 4 Bogor</div>
            </aside>
            <div class="admin-content">
                <div class="admin-topbar">
                    <div class="left">
                        <button class="menu-btn" id="menuBtn" aria-label="Toggle sidebar"><i class="fa-solid fa-bars"></i></button>
                        <strong>@yield('title', 'Dashboard Super Admin')</strong>
                    </div>
                    <div class="right">
                        <span>{{ __('admin.topbar.hi') }}</span>
                        <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i> {{ __('admin.topbar.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="admin-main">
                    @yield('content')
                </div>
            </div>
            <div class="admin-overlay" id="adminOverlay"></div>
        </div>
    @else
        <nav class="navbar">
            <div class="logo">
                <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo SMKN 4 BOGOR">
                <span>SMKN 4 BOGOR</span>
            </div>
            <div class="right-group">
                <div class="nav-links" id="nav-menu">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang Kami</a>
                    <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri</a>
                    @guest
                        <a href="{{ route('user.register') }}" class="{{ request()->routeIs('user.register') ? 'active' : '' }}">Daftar</a>
                    @endguest
                </div>
                @if(auth()->check())
                    @php
                        $u = auth()->user();
                        $name = strtolower($u->name ?? '');
                        $isFemale = false;
                        foreach (['sri','siti','ayu','putri','putry','dewi','citra','rani','wati','nisa','anis','anisa','nisaa','nurul','amel','amelia','lia','linda','dinda','rina','rina','intan','vika','vika','fitri','fitria','fitriyah'] as $kw) {
                            if (str_contains($name, $kw)) { $isFemale = true; break; }
                        }
                        $style = 'adventurer';
                        $maleUrl = 'https://api.dicebear.com/7.x/'.$style.'/svg?seed='.urlencode($u->email).'&accessoriesProbability=60&facialHairProbability=80';
                        $femaleUrl = 'https://api.dicebear.com/7.x/'.$style.'/svg?seed='.urlencode($u->email).'&hair=long01,long02,long03,long11&facialHairProbability=0&accessoriesProbability=60';
                        $fallback = $isFemale ? $femaleUrl : $maleUrl;
                        $avatar = isset($u->avatar) && $u->avatar ? asset('storage/'.$u->avatar) : $fallback;
                    @endphp
                    <div class="profile-wrap">
                        <button class="profile-btn" id="profileBtn" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ $avatar }}" alt="Avatar" />
                            <span>{{ $u->name }}</span>
                            <i class="fa-solid fa-caret-down"></i>
                        </button>
                        <div class="profile-menu" id="profileMenu" role="menu">
                            <a href="{{ route('user.profile') }}" role="menuitem"><i class="fa-regular fa-user"></i> Profil</a>
                            <a href="{{ route('user.settings') }}" role="menuitem"><i class="fa-solid fa-gear"></i> Pengaturan</a>
                            <form action="{{ route('user.logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" role="menuitem"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-login-user">
                        <i class="fas fa-user-shield"></i> Login Admin
                    </a>
                @endif
                <div class="nav-toggle" id="nav-toggle"><i class="fas fa-bars"></i></div>
            </div>
        </nav>
        
    @endif

    @if(!request()->is('admin/*'))
        <main>
            @yield('content')
        </main>
    @endif

    <script>
    // Apply only on user navbar (not on admin routes)
    (function(){
        // Only run on public pages where body has ornament-bg class
        if (!document.body.classList.contains('ornament-bg')) return;
        const userNavbar = document.querySelector('nav.navbar');
        if(!userNavbar) return;
        userNavbar.style.display = 'flex';
        userNavbar.style.zIndex = 5000;
        window.addEventListener('scroll', ()=>{
            userNavbar.classList.toggle('shrink', window.scrollY > 50);
        });
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('nav-menu');
        if (toggle && menu) { toggle.addEventListener('click', ()=> menu.classList.toggle('active')); }

        // EPIC pre-login animation on "Login Admin" click
        const loginBtn = document.querySelector('.btn-login-user');
        const logoImg = document.querySelector('.navbar .logo img');
        if (loginBtn && logoImg) {
            loginBtn.addEventListener('click', () => {
                const href = loginBtn.getAttribute('href');
                if (!href) return; // safety
                // Direct navigation without animation
                // Let the browser follow the link normally
            });
        }

        // Profile dropdown
        const pBtn = document.getElementById('profileBtn');
        const pMenu = document.getElementById('profileMenu');
        if (pBtn && pMenu) {
            pBtn.addEventListener('click', (e)=>{
                e.stopPropagation();
                const show = !pMenu.classList.contains('show');
                pMenu.classList.toggle('show', show);
                pBtn.setAttribute('aria-expanded', show ? 'true' : 'false');
            });
            document.addEventListener('click', ()=>{
                pMenu.classList.remove('show');
                pBtn.setAttribute('aria-expanded', 'false');
            });
        }

        // ---- Epic animation implementation ----
        function runEpicLoginAnimation(href){
            const startRect = logoImg.getBoundingClientRect();

            // Overlay for cinematic feel
            const overlay = document.createElement('div');
            Object.assign(overlay.style, {
                position:'fixed', inset:'0', background:'radial-gradient(ellipse at center, rgba(255,255,255,0) 0%, rgba(0,0,0,.45) 70%)',
                opacity:'0', zIndex:'1500', pointerEvents:'none', transition:'opacity .2s ease'
            });
            document.body.appendChild(overlay);
            requestAnimationFrame(()=> overlay.style.opacity = '1');

            // Clone logo (hero) – prepare higher intrinsic size to keep it crisp when visually larger
            const hero = logoImg.cloneNode(true);
            const natW = logoImg.naturalWidth || startRect.width;
            const natH = logoImg.naturalHeight || startRect.height;
            const maxVisualScale = 1.4; // matches previous visual up-scale (1 -> 2.4)
            const targetVisualWidth = startRect.width * (1 + maxVisualScale);
            const hiW = Math.min(natW, targetVisualWidth);
            const scaleStart = startRect.width / hiW; // start visually at original size
            const hiH = hiW * (startRect.height / startRect.width);
            Object.assign(hero.style, {
                position:'fixed', left:startRect.left+'px', top:startRect.top+'px', width:hiW+'px', height:hiH+'px',
                zIndex:'2001', margin:'0', borderRadius:'50%', boxShadow:'0 0 0 rgba(0,0,0,0)', filter:'none',
                willChange: 'transform, filter'
            });
            hero.style.imageRendering = '-webkit-optimize-contrast';
            hero.style.backfaceVisibility = 'hidden';
            document.body.appendChild(hero);

            // Canvas for particles/confetti
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            function resizeCanvas(){
                const dpr = Math.min(1.5, window.devicePixelRatio || 1); // lower DPR for smoother perf
                canvas.width = Math.floor(window.innerWidth * dpr);
                canvas.height = Math.floor(window.innerHeight * dpr);
                canvas.style.width = window.innerWidth + 'px';
                canvas.style.height = window.innerHeight + 'px';
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }
            Object.assign(canvas.style, { position:'fixed', inset:'0', zIndex:'2000', pointerEvents:'none' });
            document.body.appendChild(canvas); resizeCanvas();
            window.addEventListener('resize', resizeCanvas, { once:true });

            // Path: quadratic bezier to center with slight wobble
            const target = { x: window.innerWidth/2 - startRect.width*1.2/2, y: window.innerHeight/2 - startRect.height*1.2/2 };
            const ctrl = { x: startRect.left + (target.x - startRect.left)*0.4, y: Math.min(startRect.top, target.y) - Math.min(220, window.innerHeight*0.2) };

            const ease = t=> (1 - Math.cos(Math.PI * t)) / 2; // smoother sine ease in-out
            const rnd = (a,b)=> a + Math.random()*(b-a);

            // Particles store
            const sparkles = [];
            const confetti = [];

            // Timers
            let start = performance.now();
            let last = start;
            const duration = 3000; // longer, smoother

            // Emit a sparkle at x,y
            function addSparkle(x,y){
                sparkles.push({ x, y, r: rnd(2,4), life: 1, hue: rnd(45,60) });
            }

            // Confetti burst at x,y
            function burstConfetti(x,y){
                const colors = ['#f59e0b','#fbbf24','#fde68a','#ffffff','#34d399','#60a5fa'];
                for(let i=0;i<70;i++){ // modest confetti for smoother perf
                    confetti.push({
                        x, y,
                        vx: Math.cos(i/60*2*Math.PI)*rnd(2,6),
                        vy: Math.sin(i/60*2*Math.PI)*rnd(2,6) - rnd(2,6),
                        g: 0.12,
                        size: rnd(3,6),
                        rot: rnd(0,360),
                        vr: rnd(-6,6),
                        col: colors[(i+Math.floor(rnd(0,colors.length)))%colors.length],
                        life: 1
                    });
                }
            }

            function drawParticles(dt){
                ctx.clearRect(0,0,canvas.width,canvas.height);
                // sparkles
                for(let i=sparkles.length-1;i>=0;i--){
                    const p=sparkles[i]; p.life -= dt * 1.1; if(p.life<=0){ sparkles.splice(i,1); continue; }
                    ctx.beginPath(); ctx.fillStyle = `hsla(${p.hue},95%,65%,${Math.max(0,p.life)})`;
                    ctx.arc(p.x,p.y,p.r,0,Math.PI*2); ctx.fill();
                }
                // confetti
                for(let i=confetti.length-1;i>=0;i--){
                    const c=confetti[i]; c.vy += c.g * dt * 60; c.x += c.vx * dt * 60; c.y += c.vy * dt * 60; c.rot += c.vr * dt * 60; c.life -= dt * 0.6;
                    if(c.life<=0 || c.y>window.innerHeight+20){ confetti.splice(i,1); continue; }
                    ctx.save(); ctx.translate(c.x,c.y); ctx.rotate(c.rot*Math.PI/180);
                    ctx.globalAlpha = Math.max(0,c.life);
                    ctx.fillStyle = c.col; ctx.fillRect(-c.size/2,-c.size/2,c.size,c.size);
                    ctx.restore();
                }
            }

            function step(now){
                const t = Math.min(1, (now-start)/duration);
                const dt = Math.max(0.001, (now - last) / 1000); // seconds
                last = now;
                const te = ease(t);
                // Quadratic Bezier + wobble
                const bx = (1-te)*(1-te)*startRect.left + 2*(1-te)*te*ctrl.x + te*te*target.x;
                const by = (1-te)*(1-te)*startRect.top  + 2*(1-te)*te*ctrl.y + te*te*target.y;
                // gentler wobble with smoother decay (less amplitude, lower freq)
                const wobble = -Math.sin(te*Math.PI*3.0) * Math.max(5, window.innerWidth*0.005) * Math.pow(1-te, 1.25);
                const x = bx + wobble;
                const y = by + wobble*0.4;

                // Scale, rotate, glow – scale from a smaller factor (scaleStart) to 1.0 to keep bitmap crisp
                const scale = scaleStart + (1 - scaleStart) * te;
                const rotTurns = -2.0; // end at 0deg (no upside-down)
                const rot = (1 - Math.cos(Math.PI*te)) * 180 * rotTurns; // eased CCW
                hero.style.transform = `translate3d(${x-startRect.left}px, ${y-startRect.top}px, 0) rotate(${rot}deg) scale(${scale})`;
                hero.style.filter = `drop-shadow(0 6px 20px rgba(255,255,255,${0.35+te*0.25}))`;

                // Sparkles trail
                const currW = hiW * scale; const currH = hiH * scale;
                addSparkle(x + currW/2, y + currH/2);
                drawParticles(dt);

                if (t < 1) {
                    requestAnimationFrame(step);
                } else {
                    // Crash flash + confetti
                    burstConfetti(x + currW/2, y + currH/2);
                    // ensure final pose is upright (0deg) and crisp at intrinsic size
                    hero.style.transform = `translate3d(${x-startRect.left}px, ${y-startRect.top}px, 0) rotate(0deg) scale(1.08)`;
                    hero.style.filter = 'drop-shadow(0 0 40px rgba(255,255,255,.9)) blur(0px)';

                    // brief flash overlay
                    const flash = document.createElement('div');
                    Object.assign(flash.style, { position:'fixed', inset:'0', background:'#fff', opacity:'0', zIndex:'2100', pointerEvents:'none' });
                    document.body.appendChild(flash);
                    flash.animate([{opacity:0},{opacity:.8, offset:.2},{opacity:0}],{ duration:450, easing:'ease-out' });

                    // let confetti play a bit, then navigate
                    setTimeout(()=>{
                        cleanup();
                        window.location.href = href;
                    }, 650);
                }
            }

            function cleanup(){
                overlay.remove(); hero.remove(); canvas.remove();
            }

            requestAnimationFrame(step);
            // safety fallback navigate if anything blocks
            setTimeout(()=>{ try{ cleanup(); }catch{} window.location.href = href; }, 4000);
        }
    })();

    // Admin sidebar behavior
    (function(){
        if (!document.body.classList.contains('admin-theme')) return;
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const btn = document.getElementById('menuBtn');
        if(!sidebar || !overlay || !btn) return;
        function open(){ sidebar.classList.add('open'); }
        function close(){ sidebar.classList.remove('open'); }
        btn.addEventListener('click', (e)=>{
            e.stopPropagation();
            sidebar.classList.contains('open') ? close() : open();
        });
        // click on content area closes sidebar
        document.addEventListener('click', (e)=>{
            if (!sidebar.classList.contains('open')) return;
            const isInsideSidebar = sidebar.contains(e.target);
            const isMenuBtn = e.target === btn || (btn && btn.contains(e.target));
            if (!isInsideSidebar && !isMenuBtn) close();
        });
        // keep overlay hidden so content can scroll while sidebar is open
    })();
    </script>
</body>
</html>
