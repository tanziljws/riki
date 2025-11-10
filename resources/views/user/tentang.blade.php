@extends('layouts.app')

@section('title', 'Tentang Kami - SMKN 4 BOGOR')

@section('content')
<!-- Navbar disediakan oleh layouts.app -->

 

<!-- Tentang Kami (Profil Sekolah) -->
<section class="highlight about-school">
    <div class="container" data-aos="fade-up">
        <h2>Tentang <span class="gold">Kami</span></h2>
        <!-- Logo sekolah -->
        <div class="school-img" data-aos="zoom-in">
            <img src="{{ asset('images/logo-smkn.jpg') }}" alt="Logo SMKN 4 Bogor">
        </div>

        <!-- Grid konten: teks di kiri, card kepala sekolah di kanan -->
        <div class="school-content">
            <div class="school-text">
                <p>SMKN 4 Bogor adalah sekolah kejuruan negeri yang berlokasi di Kecamatan Bogor Selatan, Kota Bogor, Jawa Barat.</p>
                <p>Berdiri pada 15 Juni 2009 (SK 421-45-177/2009) di bawah Kementerian Pendidikan dan Kebudayaan.</p>
                <p>Saat ini memiliki 1.066 siswa yang dibimbing oleh 45 guru profesional di bidangnya.</p>
                <p>Dengan kehadirannya, SMKN 4 Bogor berkomitmen berkontribusi dalam mencerdaskan anak bangsa di wilayah Kota Bogor.</p>
            </div>

            <!-- Vertical separator -->
            <div class="v-sep" aria-hidden="true"></div>

            <!-- Card Kepala Sekolah di sisi -->
            <aside class="profile-card" data-aos="fade-left" aria-label="Kepala Sekolah">
                <img class="avatar" src="{{ asset('images/kepalasekolah.jpeg') }}" alt="Foto Kepala Sekolah" onerror="this.onerror=null;this.src='https://via.placeholder.com/200x240.png?text=Kepala+Sekolah';">
                <div class="profile-info">
                    <h3 class="profile-name">Mulya Murprihartono</h3>
                    <p class="profile-role">Kepala Sekolah SMKN 4 Bogor</p>
                    <p class="profile-quote">"Bersama membangun generasi unggul, berkarakter, dan berdaya saing."</p>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Lokasi Sekolah (Maps) diletakkan tepat di bawah deskripsi) -->
<section class="maps-address" data-aos="fade-up">
    <div class="container">
        <h2 class="map-title">Lokasi <span class="gold">Sekolah</span></h2>
        <div class="map-grid">
            <div class="map-embed">
                <iframe title="Lokasi SMKN 4 Bogor" src="https://www.google.com/maps?q=SMKN%204%20Bogor%2C%20Jl.%20Raya%20Tajur%20Kp.%20Buntar%2C%20Muarasari%2C%20Bogor%20Selatan%2C%20Kota%20Bogor%2016137&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="address-card">
                <h3>Alamat</h3>
                <p>SMKN 4 Bogor<br>Jl. Raya Tajur Kp. Buntar, RT02/RW08, Kel. Muarasari, Kec. Bogor Selatan, Kota Bogor 16137, Jawa Barat</p>
                <p><strong>Telepon:</strong> (0251) 7547 381<br><strong>Email:</strong> info@smkn4bogor.sch.id</p>
                <a href="https://maps.google.com/?q=SMKN+4+Bogor,+Jl.+Raya+Tajur+Kp.+Buntar,+Muarasari,+Bogor+Selatan,+Kota+Bogor+16137" target="_blank" rel="noopener" class="btn-cta small">Buka di Google Maps</a>
            </div>
        </div>
    </div>
</section>

<!-- Program Keahlian -->
<section class="majors" data-aos="fade-up">
    <div class="container">
        <h2>Program <span class="gold">Keahlian</span></h2>
        <div class="majors-grid">
            <div class="major-card">
                <i class="fas fa-code"></i>
                <h3>PPLG</h3>
                <p>Pengembangan Perangkat Lunak dan Gim. Fokus pada pemrograman, UI/UX, dan pembuatan aplikasi serta gim.</p>
            </div>
            <div class="major-card">
                <i class="fas fa-network-wired"></i>
                <h3>TJKT</h3>
                <p>Teknik Jaringan Komputer dan Telekomunikasi. Mempelajari jaringan, server, dan sistem komunikasi data.</p>
            </div>
            <div class="major-card">
                <i class="fas fa-car"></i>
                <h3>TO</h3>
                <p>Teknik Otomotif. Fokus pada sistem kendaraan, perawatan, perbaikan, dan teknologi otomotif.</p>
            </div>
            <div class="major-card">
                <i class="fas fa-cogs"></i>
                <h3>TPFL</h3>
                <p>Teknik Pengelasan Fabrikasi Logam. Keahlian manufaktur dan proses pengelasan modern.</p>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="vision-mission">
    <h2 data-aos="fade-up">Visi & <span class="gold">Misi</span></h2>
    <div class="cards">
        <div class="card vm-card" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-eye"></i>
            <h3>Visi</h3>
            <p>Menjadi sekolah kejuruan unggulan yang berkarakter dan berdaya saing tinggi di tingkat nasional dan internasional.</p>
        </div>
        <div class="card vm-card" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-bullseye"></i>
            <h3>Misi</h3>
            <ul>
                <li>Menyediakan pendidikan berkualitas tinggi</li>
                <li>Mengembangkan kompetensi siswa sesuai jurusan</li>
                <li>Mendorong inovasi dan kreativitas siswa</li>
                <li>Membangun karakter yang berintegritas</li>
            </ul>
        </div>
    </div>
</section>

@endsection

<!-- CSS -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
* { font-family: 'Poppins', sans-serif; }

/* Hero */
.hero {
    position:relative; height:100vh; overflow:hidden;
    display:flex; justify-content:center; align-items:center;
    text-align:center; color:#fff;
}
.slides, .slide {
    position:absolute; top:0; left:0; width:100%; height:100%;
    background-size:cover; background-position:center;
    transition:opacity 1s ease;
}
.slide { opacity:0; }
.slide.active { opacity:1; }
.overlay { position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.45); }
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

.school-logo {
    width: 80px; height: 80px;
    border-radius: 50%; border: 3px solid #d4af37;
    object-fit: cover; margin-bottom: 20px;
}

.hero-content p { margin:20px 0 30px; font-size:1.2rem; font-weight:500; }
.btn-cta {
    display:inline-block; padding:14px 34px;
    background:#004aad; border:2px solid #004aad;
    color:#fff; font-weight:600; border-radius:12px; transition:0.25s;
}
.btn-cta.small { padding:10px 16px; font-size:0.95rem; border-radius:10px; }
.btn-cta:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,74,173,0.25); background:#003a8e; border-color:#003a8e; }

/* Responsive */
@media (max-width: 768px) {
  .hero { height: 62vh; }
  .slides, .slide { background-position: center top; }
}
@media (max-width: 480px) {
  .hero { height: 54vh; }
}

/* Highlight */
.highlight {
    padding:100px 20px;
    background:linear-gradient(180deg,#f8f9fb,#fff);
    text-align:center;
}
.highlight h2 { font-size:2.5rem; margin-bottom:60px; font-weight:700; color:#004aad; }
.highlight h2 .gold { color:#d4af37; }

.cards {
    display:flex; justify-content:center; gap:35px; flex-wrap:wrap;
}
.card {
    background:#fff; border:1px solid #eee; padding:40px 25px;
    border-radius:16px; width:300px;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
    transition: transform 0.4s, box-shadow 0.4s;
    transform-style:preserve-3d;
}
.card:hover { transform:translateY(-12px) rotate3d(1,1,0,7deg); box-shadow:0 12px 30px rgba(0,0,0,0.15); }
.card i {
    font-size:2.5rem;
    background: linear-gradient(90deg,#004aad,#d4af37);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    margin-bottom:22px;
}
.card h3 { margin-bottom:15px; font-size:1.3rem; font-weight:700; color:#333; }
.card p { font-size:1rem; color:#555; }

/* Fallback: pastikan elemen dengan AOS tetap terlihat meskipun JS AOS gagal */
[data-aos] { opacity: 1 !important; transform: none !important; }

/* About School */
.about-school { padding-top: 72px; }
.about-school .container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 16px;
}
.about-school h2 { margin-bottom: 18px; text-wrap: balance; letter-spacing: .2px; }
.school-content {
    display: grid;
    grid-template-columns: 1.6fr 1px 1fr; /* text | separator | card */
    column-gap: 40px;
    align-items: start;
    margin-top: 24px;
}
.school-img {
    flex: 1;
    min-width: 300px;
    display: flex;
    justify-content: center;
    margin-bottom: 8px;
}
.school-img img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #d4af37;
    object-fit: cover;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.school-text {
    flex: 1;
    min-width: 300px;
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 690px;
}
.school-text p {
    font-size: 1.08rem;
    line-height: 1.9;
    color: #444;
    margin: 0;
    text-align: justify;
    hyphens: auto;
}

/* Headmaster Profile Card */
.profile-card {
    margin-top: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 16px;
    padding: 20px 20px 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    align-self: start;
}
.v-sep {
    width: 1px; background: linear-gradient(180deg, rgba(0,74,173,0), rgba(0,74,173,.16), rgba(0,74,173,0));
    border-radius: 1px;
    align-self: stretch; /* full height of grid row */
    height: 100%;
}
.profile-card .avatar {
    width: 200px; height: 220px; border-radius: 12px; object-fit: cover;
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}
.profile-card .profile-info { text-align: center; }
.profile-card .profile-name { margin: 0 0 6px; color:#004aad; font-size: 1.2rem; font-weight: 700; }
.profile-card .profile-role { margin: 0 0 8px; color:#333; font-weight: 600; }
.profile-card .profile-quote { margin: 0; color:#666; font-style: italic; line-height: 1.6; }

/* Majors */
.majors { padding:80px 20px; background:#fff; text-align:center; }
.majors .container { max-width:1200px; margin:0 auto; }
.majors h2 { font-size:2.3rem; color:#004aad; margin-bottom:40px; }
.majors h2 .gold { color:#d4af37; }
.majors-grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:24px; }
.major-card { background:#fff; border:1px solid #eee; border-radius:16px; padding:26px 20px; box-shadow:0 6px 20px rgba(0,0,0,0.08); transition:.3s; }
.major-card:hover { transform:translateY(-8px); box-shadow:0 12px 28px rgba(0,0,0,0.12); }
.major-card i { font-size:2rem; color:#004aad; margin-bottom:12px; }
.major-card h3 { margin:6px 0 10px; color:#333; }
.major-card p { color:#555; font-size:.95rem; line-height:1.6; }

/* Maps & Address */
.maps-address { padding: 80px 20px; background:#fff; }
.maps-address .container { max-width: 1200px; margin:0 auto; }
.map-title { text-align:center; font-size:2.2rem; color:#004aad; margin-bottom:30px; }
.map-grid { display:grid; grid-template-columns: 2fr 1fr; gap:24px; align-items:start; }
.map-embed iframe { width:100%; height:380px; border:0; border-radius:16px; box-shadow:0 8px 25px rgba(0,0,0,0.08); }
.address-card { background:#f9fafc; border:1px solid #eee; border-radius:16px; padding:24px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
.address-card h3 { margin-top:0; color:#004aad; }
.address-card p { color:#555; line-height:1.7; }

/* Vision Mission */
.vision-mission {
    padding:100px 20px;
    background:linear-gradient(180deg,#fff,#f8f9fb);
    text-align:center;
}
.vision-mission h2 { font-size:2.5rem; margin-bottom:60px; font-weight:700; color:#004aad; }
.vm-card ul {
    text-align: left;
    padding-left: 20px;
}
.vm-card ul li {
    margin-bottom: 8px;
    color: #555;
}

/* Responsive */
@media (max-width: 992px) {
    .hero-content h1 { font-size:2.2rem; }
    .school-content { grid-template-columns: 1fr; row-gap: 18px; }
    .school-text { text-align: center; }
    .school-text p { text-align: left; }
    .v-sep { display: none; }
    .profile-card { max-width: 520px; margin: 8px auto 0; }
    .majors-grid { grid-template-columns: 1fr 1fr; gap: 18px; }
    .map-grid { grid-template-columns: 1fr; gap: 18px; }
}

@media (max-width: 768px) {
    .highlight { padding: 70px 16px; }
    .about-school .container, .majors .container, .maps-address .container { padding: 0 12px; }
    .about-school h2, .majors h2, .map-title, .vision-mission h2 { font-size: 1.8rem; }
    .school-text p { font-size: 1rem; line-height: 1.8; }
    .map-embed iframe { height: 300px; }
}

@media (max-width: 480px) {
    .school-img img { width: 120px; height: 120px; }
    .profile-card .avatar { width: 170px; height: 190px; }
    .majors-grid { grid-template-columns: 1fr; }
    .major-card { padding: 20px 16px; }
    .map-embed iframe { height: 240px; }
}

/* Sticky card on large screens for a polished feel */
@media (min-width: 1200px) {
    .profile-card { position: sticky; top: 110px; }
}
</style>

<!-- JS -->
<script>
// Navbar diatur oleh layout
</script>

<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
// Init AOS with fallback so konten tetap terlihat meski AOS gagal dimuat
document.addEventListener('DOMContentLoaded', function(){
  try {
    if (window.AOS && typeof AOS.init === 'function') {
      AOS.init({ duration: 1000, once: true });
    } else {
      document.querySelectorAll('[data-aos]')
        .forEach(el => { el.style.opacity = '1'; el.style.transform = 'none'; });
    }
  } catch (e) {
    document.querySelectorAll('[data-aos]')
      .forEach(el => { el.style.opacity = '1'; el.style.transform = 'none'; });
  }
});
</script>
