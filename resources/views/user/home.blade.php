@extends('layouts.app')

@section('title', 'SMKN 4 BOGOR')

@section('content')
<!-- Navbar disediakan oleh layouts.app -->
 
<!-- Hero Section -->
<section class="hero">
    <div class="slides">
        @if(!empty($heroSlides) && count($heroSlides))
            @foreach($heroSlides as $i => $s)
                @php $bgUrl = (!empty($s->image) && file_exists(public_path('storage/'.$s->image))) ? asset('storage/'.$s->image) : asset('images/smkn4.jpg'); @endphp
                <div class="slide {{ $i===0 ? 'active' : '' }}" aria-label="{{ $s->title ?? 'Slide' }}">
                    <div class="slide-bg" style="background-image:url('{{ $bgUrl }}')"></div>
                    <img class="slide-img" src="{{ $bgUrl }}" alt="{{ $s->title ?? 'Slide' }}" />
                </div>
            @endforeach
        @else
            <div class="slide active"><div class="slide-bg" style="background-image:url('{{ asset('images/smkn4.jpg') }}')"></div><img class="slide-img" src="{{ asset('images/smkn4.jpg') }}" alt="Slide 1" /></div>
            <div class="slide"><div class="slide-bg" style="background-image:url('{{ asset('images/pabima.jpg') }}')"></div><img class="slide-img" src="{{ asset('images/pabima.jpg') }}" alt="Slide 2" /></div>
            <div class="slide"><div class="slide-bg" style="background-image:url('{{ asset('images/foto3.jpeg') }}')"></div><img class="slide-img" src="{{ asset('images/foto3.jpeg') }}" alt="Slide 3" /></div>
        @endif
    </div>
    <div class="overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">
            <span id="typing-text" aria-label="Selamat Datang di SMKN 4 BOGOR"></span>
        </h1>
        <p data-aos="fade-up" data-aos-delay="200">Sekolah Unggul • Berkarakter • Berprestasi</p>
        <a href="{{ route('galeri') }}" class="btn-cta" data-aos="zoom-in" data-aos-delay="400">
            Jelajahi Galeri
        </a>
    </div>
    <div class="slider-controls">
        <span class="prev">&#10094;</span>
        <span class="next">&#10095;</span>
    </div>
    <div class="slider-dots"></div>
</section>

<!-- Highlight Section -->
<section class="highlight">
    <h2 data-aos="fade-up">Kenapa Memilih <span class="gold">SMKN 4 Bogor?</span></h2>
    <div class="cards">
        <div class="card" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-school"></i>
            <h3>Fasilitas Lengkap</h3>
            <p>Sarana modern dengan teknologi terbaru untuk mendukung pembelajaran.</p>
        </div>
        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Guru Profesional</h3>
            <p>Tenaga pendidik berpengalaman, inovatif, dan kompeten di bidangnya.</p>
        </div>
        <div class="card" data-aos="fade-up" data-aos-delay="300">
            <i class="fas fa-medal"></i>
            <h3>Prestasi Gemilang</h3>
            <p>Raih penghargaan dan prestasi tingkat daerah, nasional, hingga internasional.</p>
        </div>
    </div>
</section>

<section class="about">
    <div class="about-wrap">
        <div class="about-media" data-aos="zoom-in">
            <img src="{{ asset('images/kepalasekolah.jpeg') }}" alt="Kepala Sekolah">
        </div>
        <div class="about-text" data-aos="fade-left">
            <h2>Tentang <span class="gold">SMKN 4 Bogor</span></h2>
            <p>SMKN 4 Bogor berkomitmen menghasilkan lulusan berkarakter, kompeten, dan siap kerja melalui pembelajaran berbasis industri, fasilitas modern, dan kolaborasi dengan mitra dunia usaha dan dunia industri.</p>
            <div class="about-points">
                <div><i class="fa-solid fa-circle-check"></i> Kurikulum terkini berbasis proyek</div>
                <div><i class="fa-solid fa-circle-check"></i> Teaching factory & praktik intensif</div>
                <div><i class="fa-solid fa-circle-check"></i> Bimbingan karier dan magang</div>
            </div>
            <a href="{{ route('tentang') }}" class="btn-cta small">Pelajari Selengkapnya</a>
        </div>
    </div>
</section>

<section class="cta-band" data-aos="zoom-in">
    <div class="cta-inner">
        <div class="cta-text">
            <h3>Bergabung bersama SMKN 4 Bogor</h3>
            <p>Daftar sekarang dan wujudkan masa depan digitalmu.</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('galeri') }}" class="btn-cta alt">Lihat Galeri</a>
            <a href="{{ route('user.register') }}" class="btn-cta">Daftar</a>
        </div>
    </div>
    
</section>

<footer class="site-footer">
    <div class="footer-grid">
        <div class="f-brand">
            <div class="brand-head">
                <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo SMKN 4 Bogor">
                <div class="f-title">SMKN 4 BOGOR</div>
            </div>
            <p>Mencetak lulusan berkarakter, kompeten, dan siap kerja.</p>
        </div>
        <div class="f-links">
            <div class="f-sub">Navigasi</div>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('tentang') }}">Tentang Kami</a>
            <a href="{{ route('galeri') }}">Galeri</a>
            <a href="{{ route('user.register') }}">Daftar</a>
        </div>
        <div class="f-contact">
            <div class="f-sub">Kontak</div>
            <a href="mailto:info@smkn4bogor.sch.id">info@smkn4bogor.sch.id</a>
            <a href="tel:+62251xxxxxxx">(0251) 7547 381</a>
            <div class="f-social">
                <a href="https://www.instagram.com/smkn4kotabogor/" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com/p/SMK-NEGERI-4-KOTA-BOGOR-100054636630766/?locale=id_ID" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://www.youtube.com/@smknegeri4bogor905" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <div class="f-copy">© {{ date('Y') }} SMKN 4 Bogor. All rights reserved.</div>
</footer>

<!-- CSS -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
* { font-family: 'Poppins', sans-serif; }

/* Navbar diatur oleh layout */

/* Fallback jika AOS tidak termuat: pastikan konten tetap terlihat */
[data-aos], .aos-init { opacity: 1 !important; transform: none !important; }

/* Intro */
.intro-top{ padding: 92px 20px 6px; background: transparent; }
.intro-inner{ max-width:1100px; margin:0 auto; display:grid; grid-template-columns:1.5fr 1fr; gap:28px; align-items:center; }
.intro-left h1{ margin:0 0 10px; color:#004aad; font-size:2.2rem; line-height:1.25; }
.intro-left p{ color:#445; margin:0 0 16px; }
.intro-actions{ display:flex; gap:12px; }
.intro-right img{ width:100%; max-width:190px; border-radius:12px; border:2px solid #d4af37; box-shadow:0 6px 16px rgba(2,6,23,0.08); }
@media (max-width: 768px){ .intro-inner{ grid-template-columns:1fr; text-align:center; } .intro-actions{ justify-content:center; } }

/* Hero */
.hero {
    position:relative; height:80vh; overflow:hidden; margin-bottom: 40px;
    /* full-bleed to viewport without negative margins */
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    display:flex; justify-content:center; align-items:center;
    text-align:center; color:#fff;
}
.slides, .slide {
    position:absolute; top:0; left:0; width:100%; height:100%;
    transition:opacity 1s ease;
}
.slide-bg{ position:absolute; inset:0; background-size:cover; background-position:center; opacity:1; }
.slide-img{ width:100%; height:100%; object-fit:cover; object-position:center; display:block; background:transparent; }
.slide { opacity:0; }
.slide.active { opacity:1; }
.overlay { position:absolute; top:0; left:0; right:0; bottom:0; background:linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.45)); }
.hero-content {
    position:relative; z-index:2; max-width:850px; padding:0 20px;
}
.hero-content.glass {
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(10px);
    padding:40px; border-radius:20px;
}
.hero-content h1 { font-size:3.5rem; font-weight:800; line-height:1.2; margin-bottom:15px; color:#004aad; }
.hero-title { color:#004aad; }
.hero-title .gold { color:#d4af37; }

.hero-content p { margin:20px 0 30px; font-size:1.2rem; font-weight:500; }
.btn-cta {
    display:inline-block; padding:14px 34px;
    background:#004aad;
    color:#fff; font-weight:600; border-radius:12px; transition:0.25s; border:2px solid #004aad;
}
.btn-cta:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,74,173,0.25); background:#003a8e; border-color:#003a8e; }

/* Slider Controls + Dots */
.slider-controls {
    position:absolute; top:50%; width:100%;
    display:flex; justify-content:space-between; padding:0 30px;
    z-index:3; transform:translateY(-50%);
}
.slider-controls span {
    cursor:pointer; font-size:2.5rem; color:#fff; transition:0.3s;
}
.slider-controls span:hover { color:#d4af37; }
.slider-dots {
    position:absolute; bottom:25px; width:100%;
    display:flex; justify-content:center; gap:10px; z-index:3;
}
.slider-dots span {
    width:12px; height:12px; border-radius:50%;
    background:#fff; opacity:0.5; cursor:pointer; transition:0.3s;
}
.slider-dots span.active { opacity:1; background:#d4af37; }

/* Highlight */
.highlight {
    padding: 50px 20px 60px; background:linear-gradient(180deg,#f8f9fb,#fff); text-align:center;
}
.highlight .cards{ max-width:1100px; margin:0 auto; }
.highlight h2{ margin-bottom:40px; }
.highlight h2 { font-size:2.5rem; margin-bottom:60px; font-weight:700; color:#004aad; }
.highlight h2 .gold { color:#d4af37; }

.cards {
    display:flex; justify-content:center; gap:35px; flex-wrap:wrap;
}
.card {
    background:#fff; border:1px solid #eee; padding:26px 22px; box-sizing:border-box;
    border-radius:16px; width:100%; height:100%; display:flex; flex-direction:column; justify-content:flex-start; align-items:center; text-align:center;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.card:hover { transform:translateY(-6px); box-shadow:0 12px 26px rgba(0,0,0,0.12); }
.card i {
    font-size:2.5rem;
    background: linear-gradient(90deg,#004aad,#d4af37);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    margin-bottom:22px;
}
.card h3 { margin-bottom:15px; font-size:1.3rem; font-weight:700; color:#333; }
.card p { font-size:1rem; color:#555; }

/* Footer: blue-gold theme */
.site-footer { background:#004aad; color:#eaf1ff; padding:50px 20px 20px; }
.footer-grid { max-width:1200px; margin:0 auto; display:grid; gap:28px; grid-template-columns: 2fr 1fr 1fr; align-items:stretch; }
.brand-head { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
.brand-head img { width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid #ffd666; background:#fff; }
.f-title { font-size:1.2rem; font-weight:800; color:#fff; }
.f-sub { color:#ffd666; font-weight:800; margin-bottom:8px; }
.f-links a, .f-contact a { display:block; color:#eaf1ff; text-decoration:none; margin:6px 0; }
.f-links a:hover, .f-contact a:hover { color:#d4af37; }
.f-social a { margin-right:10px; color:#eaf1ff; }
.f-social a:hover { color:#d4af37; }
.f-copy { text-align:center; margin-top:28px; color:#dbe7ff; font-size:.95rem; border-top:1px solid rgba(255,255,255,.15); padding-top:14px; }

/* Responsive */
@media (max-width: 768px) {
    .hero { height: 70vh; width:100vw; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw); }
    .slides, .slide { background-position: center top; }
    .navbar-right { display:none; flex-direction:column; gap:15px; background:rgba(255,255,255,0.95); position:absolute; top:75px; right:0; padding:20px; border-radius:0 0 12px 12px; box-shadow:0 8px 25px rgba(0,0,0,0.1); }
    .navbar-right.active { display:flex; }
    .nav-toggle { display:block; }
    .hero-content h1 { font-size:2.2rem; }
    .footer-grid { grid-template-columns: 1fr; }
}

/* Extra small devices */
@media (max-width: 480px) {
  .hero { height: 54vh; }
  .hero-content { padding: 0 12px; }
  .hero-content h1 { font-size: 1.9rem; }
  .hero-content p { font-size: 1rem; }
  .btn-cta { padding: 12px 20px; font-size: .95rem; }
  .slider-controls { padding: 0 14px; }
  .slider-controls span { font-size: 2rem; }
  .slider-dots { bottom: 18px; }
}

/* About */
.about { padding: 60px 20px 10px; max-width: 1100px; margin: 0 auto; }
.about-wrap { display:grid; grid-template-columns: 1.1fr 1.3fr; gap: 30px; align-items:center; }
.about-media img { width:100%; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.12); object-fit:cover; }
.about-text h2 { color:#004aad; font-size:2.1rem; margin-bottom:12px; }
.about-text p { color:#444; margin-bottom:14px; }
.about-points { display:grid; gap:8px; margin-bottom:16px; }
.about-points i { color:#16a34a; margin-right:8px; }
.btn-cta.small { padding:10px 18px; }

/* Stats */
.stats { display:grid; grid-template-columns: repeat(4,1fr); gap:16px; margin:34px auto 0; max-width:1100px; }
.stat { background:#fff; border:1px solid #eef2f7; border-radius:14px; padding:18px; text-align:center; box-shadow:0 6px 16px rgba(2,6,23,0.06); }
.stat .num { font-size:1.8rem; font-weight:800; color:#004aad; }
.stat .lbl { color:#555; font-weight:600; }

/* Programs */
.programs { padding: 60px 20px; background: linear-gradient(180deg,#fff,#f8f9fb); text-align:center; }
.programs h2 { color:#004aad; margin-bottom:20px; font-size:2rem; }
.prog-grid { display:grid; grid-template-columns: repeat(3, 1fr); gap:26px; max-width:1100px; margin:0 auto; align-items:stretch; }
.prog { background:#fff; border:1px solid #eef2f7; border-radius:14px; padding:18px; display:flex; flex-direction:column; align-items:center; gap:10px; box-shadow:0 6px 16px rgba(2,6,23,0.06); transition:.25s; }
.prog img { width:64px; height:64px; object-fit:cover; border-radius:12px; }
.prog div { font-weight:700; color:#0b3c8f; }
.prog:hover { transform: translateY(-4px); box-shadow:0 10px 24px rgba(2,6,23,0.08); }

/* CTA band */
.cta-band { padding: 50px 20px 70px; }
.cta-inner { max-width: 1100px; margin: 0 auto; background: linear-gradient(135deg, #0b3c8f, #1e3a8a); color:#fff; border-radius:16px; padding:22px; display:flex; align-items:center; justify-content:space-between; gap:20px; box-shadow:0 12px 28px rgba(2,6,23,0.15); }
.cta-text h3 { margin:0 0 6px; font-size:1.6rem; }
.cta-text p { margin:0; opacity:.95; }
.btn-cta.alt { background:#fff; color:#0b3c8f !important; border-color:#fff; }
.btn-cta.alt:hover { background:#f1f5ff; }

@media (max-width: 992px){
  .about-wrap { grid-template-columns:1fr; }
  .prog-grid { grid-template-columns: repeat(2,1fr); }
  .stats { grid-template-columns: repeat(2,1fr); }
  .cards { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 520px){
  .prog-grid { grid-template-columns: 1fr; }
  .stats { grid-template-columns: 1fr; }
  .cards { grid-template-columns: 1fr; }
  .cta-inner { flex-direction:column; text-align:center; }
}

</style>

<!-- JS -->
<script>
// Nav toggle diatur oleh layout

// Typing animation (smooth, no blinking cursor)
document.addEventListener('DOMContentLoaded', function(){
  const el = document.getElementById('typing-text');
  if(!el) return;
  const first = 'Galeri ';
  const second = 'Foto';
  const speed = 55; // ms per char

  function typeSegment(target, text, index, done){
    if(index < text.length){
      target.textContent += text.charAt(index);
      setTimeout(()=>typeSegment(target, text, index+1, done), speed);
    } else if (typeof done === 'function') {
      done();
    }
  }

  typeSegment(el, first, 0, () => {
    const goldSpan = document.createElement('span');
    goldSpan.className = 'gold';
    el.appendChild(goldSpan);
    typeSegment(goldSpan, second, 0);
  });
});

// Slider
let slides=document.querySelectorAll('.slide'), dotsContainer=document.querySelector('.slider-dots'), current=0;
slides.forEach((_,i)=>{ let dot=document.createElement("span"); if(i===0) dot.classList.add("active"); dot.addEventListener("click",()=>{current=i; showSlide(current);}); dotsContainer.appendChild(dot); });
let dots=document.querySelectorAll('.slider-dots span');

function showSlide(index){ slides.forEach((s,i)=>s.classList.toggle("active", i===index)); dots.forEach((d,i)=>d.classList.toggle("active", i===index)); }
function nextSlide(){ current=(current+1)%slides.length; showSlide(current); }
function prevSlide(){ current=(current-1+slides.length)%slides.length; showSlide(current); }
document.querySelector('.next').addEventListener('click', nextSlide);
document.querySelector('.prev').addEventListener('click', prevSlide);
setInterval(nextSlide,6000);

</script>

<script>
// Simple count-up for stats
document.addEventListener('DOMContentLoaded', function(){
  const els = document.querySelectorAll('.stat .num');
  els.forEach(el=>{
    const target = parseInt(el.getAttribute('data-target')||'0',10);
    let cur = 0; const step = Math.max(1, Math.round(target/60));
    const t = setInterval(()=>{
      cur += step; if (cur >= target){ cur = target; clearInterval(t); }
      el.textContent = cur.toLocaleString('id-ID');
    }, 30);
  });
});
</script>

<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration:1000, once:true });</script>
@endsection
