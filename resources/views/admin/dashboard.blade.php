@extends('layouts.app')
@section('title', app()->getLocale()==='en' ? 'Super Admin Dashboard' : 'Dashboard Super Admin')

@section('content')
<div id="dashboard" class="dashboard">

    {{-- Hero --}}
    <div class="hero">
        @php
            $brandLogo = asset('images/perusahaan.png');
            if (file_exists(public_path('storage/branding/logo.png'))) {
                $brandLogo = asset('storage/branding/logo.png');
            }
        @endphp
        <div class="brand-logo">
            <img src="{{ $brandLogo }}" alt="Logo">
        </div>
        <div>
            @php($en = app()->getLocale()==='en')
            <h1>{{ $en ? 'Super Admin Dashboard' : 'Dashboard Super Admin' }}</h1>
            <p>{{ $en ? 'Gallery & activity management panel. Fast insights, modern UI.' : 'Panel manajemen galeri & aktivitas. Insight cepat, tampilan modern.' }}</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="stats">
        @foreach([
            ['label' => $en ? 'Active Photos' : 'Foto Aktif', 'icon' => 'fa-solid fa-images', 'value' => $totalGalleries ?? 0, 'href' => route('admin.galeri.index')],
            ['label' => $en ? 'Albums' : 'Album', 'icon' => 'fa-solid fa-folder-open', 'value' => $totalAlbums ?? 0, 'href' => route('admin.galeri.index')],
            ['label' => $en ? 'Uploads Today' : 'Upload Hari Ini', 'icon' => 'fa-solid fa-cloud-arrow-up', 'value' => $uploadsToday ?? 0, 'href' => route('admin.galeri.index')],
            ['label' => $en ? 'Pending Review' : 'Menunggu Review', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingReview ?? 0, 'href' => url('admin/aktivitas')],
        ] as $s)
            <a class="stat" href="{{ $s['href'] }}">
                <div class="stat-icon"><i class="{{ $s['icon'] }}"></i></div>
                <div class="stat-body">
                    <div class="stat-value count-up" data-value="{{ $s['value'] }}">0</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="card quick">
        <div class="qa-title">{{ $en ? 'Quick Actions' : 'Aksi Cepat' }}</div>
        <div class="qa-list">
            <a href="{{ route('admin.galeri.index') }}" class="qa-btn"><i class="fa-solid fa-plus"></i> {{ $en ? 'Upload Photo' : 'Upload Foto' }}</a>
            <a href="{{ route('admin.galeri.index') }}" class="qa-btn"><i class="fa-solid fa-folder-plus"></i> {{ $en ? 'New Album' : 'Album Baru' }}</a>
            <a href="{{ route('admin.galeri.index') }}" class="qa-btn"><i class="fa-solid fa-star"></i> {{ $en ? 'Manage Featured' : 'Atur Featured' }}</a>
            <a href="{{ route('admin.galeri.index') }}" class="qa-btn"><i class="fa-solid fa-tags"></i> {{ $en ? 'Manage Tags' : 'Kelola Tag' }}</a>
        </div>
    </div>

    <div class="grid-2">
        {{-- Uploads 7 Hari --}}
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-chart-column"></i> {{ $en ? 'Uploads (7 Days)' : 'Upload 7 Hari' }}</div>
            <canvas id="uploadsChart" height="220"></canvas>
        </div>
        {{-- Storage Usage --}}
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-database"></i> {{ $en ? 'Storage Usage' : 'Penggunaan Storage' }}</div>
            <div class="storage-wrap">
                <canvas id="storageChart" height="220"></canvas>
                <div class="storage-legend">
                    <div><span class="dot used"></span> Terpakai</div>
                    <div><span class="dot free"></span> Sisa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        {{-- Moderation Queue --}}
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-shield"></i> {{ $en ? 'Pending Moderation' : 'Moderasi Pending' }}</div>
            <ul class="list">
                @forelse(($recentActivities ?? collect())->where('type','comment')->take(5) as $act)
                    <li>
                        <div class="thumb">
                            @php($gimg = optional($act->gallery)->image ? asset('storage/'.optional($act->gallery)->image) : null)
                            @if($gimg)
                                <img src="{{ $gimg }}" alt="{{ optional($act->gallery)->title ?? 'Galeri' }}" />
                            @endif
                        </div>
                        <div class="meta">
                            <b>{{ optional($act->gallery)->title ?? 'Galeri' }}</b>
                            <span>by {{ optional($act->actor)->name ?? 'User' }} • {{ $act->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="actions">
                            <a class="btn-approve" href="{{ url('admin/aktivitas?type=comment') }}" title="Detail"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="meta"><b>{{ $en ? 'No recent comments.' : 'Tidak ada komentar terbaru.' }}</b></div>
                    </li>
                @endforelse
            </ul>
            <div class="card-foot">
                <a href="{{ url('admin/aktivitas') }}" class="btn-more">{{ $en ? 'See all' : 'Lihat Semua' }}</a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-clock-rotate-left"></i> {{ $en ? 'Recent Activity' : 'Aktivitas Terbaru' }}</div>
            <ul class="activity">
                @forelse($recentActivities ?? [] as $a)
                    <li>
                        <i class="fa-solid {{ $a->type === 'like' ? 'fa-heart' : 'fa-comment' }}"></i>
                        <div class="act-body">
                            <div class="act-line">
                                <span class="actor">{{ optional($a->actor)->name ?? 'User' }}</span>
                                <span class="verb">{{ $a->type === 'like' ? ($en ? 'liked' : 'menyukai') : ($en ? 'commented on' : 'mengomentari') }}</span>
                                <b class="title">{{ optional($a->gallery)->title ?? 'Galeri' }}</b>
                            </div>
                            <div class="time">{{ $a->created_at->diffForHumans() }}</div>
                        </div>
                    </li>
                @empty
                    <li><div class="act-body"><div class="act-line">{{ $en ? 'No recent activity.' : 'Tidak ada aktivitas terbaru.' }}</div></div></li>
                @endforelse
            </ul>
            <div class="card-foot">
                <a href="{{ url('admin/aktivitas') }}" class="btn-more">{{ $en ? 'See all' : 'Lihat Semua' }}</a>
            </div>
        </div>
    </div>

    <div class="grid-2">
        {{-- System Status --}}
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-heart-pulse"></i> {{ $en ? 'System Health' : 'Kesehatan Sistem' }}</div>
            <div class="system">
                <div class="sys-item"><span class="dot ok"></span> Database <b>OK</b></div>
                <div class="sys-item"><span class="dot ok"></span> Queue <b>Jalan</b></div>
                <div class="sys-item"><span class="dot ok"></span> Storage <b>Stabil</b></div>
            </div>
        </div>
        {{-- Placeholder empty for future widgets --}}
        <div class="card muted">
            <div class="card-head"><i class="fa-solid fa-layer-group"></i> {{ $en ? 'Additional Widgets' : 'Widget Tambahan' }}</div>
            <p>{{ $en ? 'Ready for album/tag insights or analytics integration.' : 'Siap untuk insight album/tag atau integrasi analytics.' }}</p>
        </div>
    </div>


    <div class="footer">© {{ date('Y') }} SMKN 4 Bogor • Super Admin</div>
</div>

{{-- CSS --}}
<style>
:root{
    --blue:#0a1f4f; --blue-2:#0b3c8f; --gold:#d4af37; --muted:#6b7280; --card:#ffffff; --bg:#f6f7fb;
}
.dashboard{ padding:24px; max-width:1200px; margin:0 auto; font-family:'Poppins',sans-serif; }
.hero{ display:flex; align-items:center; gap:16px; margin-bottom:18px; }
.brand-logo{ width:64px; height:64px; border-radius:50%; background:#fff; border:2px solid var(--gold); display:flex; align-items:center; justify-content:center; box-shadow:0 6px 24px rgba(2,6,23,.08); overflow:hidden; }
.brand-logo img{ width:100%; height:100%; object-fit:contain; display:block; }
.hero h1{ margin:0; font-size:22px; font-weight:800; color:#111827; }
.hero p{ margin:2px 0 0; color:var(--muted); font-size:13px; }

.stats{ display:grid; grid-template-columns:repeat(4,minmax(160px,1fr)); gap:14px; margin-bottom:16px; }
.stat{ display:flex; gap:12px; align-items:center; padding:14px; border-radius:14px; background:var(--card); border:1px solid #eef0f4; box-shadow:0 4px 14px rgba(0,0,0,.04); text-decoration:none; }
.stat:hover{ transform:translateY(-2px); box-shadow:0 10px 24px rgba(0,0,0,.08); transition:.2s; }
.stat-icon{ width:42px; height:42px; border-radius:12px; background:var(--blue); color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; border:2px solid var(--gold); }
.stat-value{ font-size:22px; font-weight:800; color:#111827; line-height:1; }
.stat-label{ font-size:12px; color:#6b7280; }

.card{ background:var(--card); border:1px solid #eef0f4; border-radius:14px; padding:16px; box-shadow:0 6px 18px rgba(0,0,0,.05); }
.card-head{ font-weight:800; color:#111827; display:flex; align-items:center; gap:8px; margin-bottom:12px; }
.grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin:16px 0; }
.quick .qa-title{ font-weight:700; margin-bottom:12px; }
.qa-list{ display:grid; grid-template-columns: repeat(4, minmax(140px, 1fr)); gap:12px; width:100%; align-items:stretch; }
.qa-btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:42px; padding:10px 12px; background:var(--blue); color:#fff; border-radius:12px; border:2px solid var(--gold); text-decoration:none; font-weight:700; font-size:13px; box-shadow:0 4px 12px rgba(2,6,23,.08); width:100%; box-sizing:border-box; }
.qa-btn:hover{ filter:brightness(1.05); transform:translateY(-1px); }

.storage-wrap{ display:flex; align-items:center; gap:12px; }
.storage-legend .dot{ display:inline-block; width:10px; height:10px; border-radius:999px; margin-right:6px; }
.storage-legend .used{ background:var(--blue-2); }
.storage-legend .free{ background:#e5e7eb; }

.list{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; }
.list li{ display:flex; align-items:center; gap:10px; background:#fff; border:1px solid #eef0f4; border-radius:12px; padding:10px; }
.thumb{ width:64px; height:64px; background:#e5e7eb; border-radius:8px; overflow:hidden; display:flex; align-items:center; justify-content:center; flex:0 0 auto; }
.thumb img{ width:100%; height:100%; object-fit:cover; display:block; }
.meta span{ display:block; color:#6b7280; font-size:12px; }
.actions{ margin-left:auto; display:flex; gap:8px; }
.btn-approve,.btn-reject{ width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:10px; border:none; cursor:pointer; }
.btn-approve{ background:#16a34a; color:#fff; }
.btn-reject{ background:#ef4444; color:#fff; }

.activity{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; }
.activity li{ display:flex; align-items:flex-start; gap:10px; font-size:14px; color:#374151; background:#fff; border:1px solid #eef0f4; border-radius:12px; padding:10px 12px; }
.activity li i{ width:28px; height:28px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; background:var(--blue); color:#fff; flex:0 0 auto; }
.activity li b{ max-width:55%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

.system{ display:flex; flex-direction:column; gap:8px; }
.sys-item{ display:flex; align-items:center; gap:8px; font-size:14px; color:#374151; }
.dot{ width:10px; height:10px; border-radius:999px; display:inline-block; }
.dot.ok{ background:#22c55e; }

.footer{ text-align:center; color:#6b7280; font-size:12px; margin-top:14px; }

/* Responsive breakpoints */
@media (max-width: 1024px){
  .stats{ grid-template-columns:repeat(2,1fr);} 
  .grid-2{ grid-template-columns:1fr;}
  .qa-list{ grid-template-columns: repeat(2, minmax(160px, 1fr)); }
}
@media (max-width: 768px){
  .dashboard{ padding:16px; }
  .hero{ gap:12px; }
  .stats{ grid-template-columns:1fr; }
  .qa-list{ grid-template-columns: 1fr; }
  .qa-btn{ width:100%; }
  .storage-wrap{ flex-direction:column; align-items:stretch; }
  .activity li{ flex-wrap:wrap; }
  .activity li b{ max-width:100%; white-space:normal; }
  .thumb{ width:56px; height:56px; }
}
/* Card foot and more button */
.card-foot{ margin-top:10px; display:flex; justify-content:flex-end; }
.btn-more{ display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:10px; border:1px solid #dbeafe; background:#f1f5ff; color:#0b3c8f; text-decoration:none; font-weight:700; font-size:12px; }
.btn-more:hover{ background:#e6efff; }
@media (max-width: 480px){
  .dashboard{ padding:12px; }
  .hero{ flex-wrap:wrap; }
  .brand-logo{ width:56px; height:56px; }
  .hero h1{ font-size:20px; }
  .stat{ padding:12px; }
  .card{ padding:14px; }
  .qa-list{ gap:10px; }
}

/* Make charts responsive */
.card canvas{ width:100% !important; height:auto !important; display:block; }

/* Dark theme overrides for dashboard */
[data-theme='dark'] .dashboard{ color:#e5e7eb; }
[data-theme='dark'] .card{ background:#0f172a; border-color:#1f2937; box-shadow:none; }
[data-theme='dark'] .stat{ background:#0f172a; border-color:#1f2937; }
[data-theme='dark'] .stat-label, [data-theme='dark'] .hero p, [data-theme='dark'] .footer{ color:#9ca3af; }
[data-theme='dark'] .stat-value, [data-theme='dark'] .card-head, [data-theme='dark'] .hero h1{ color:#f3f4f6; }
[data-theme='dark'] .list li{ background:#0f172a; border-color:#1f2937; }
</style>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>
<script>
window.addEventListener('DOMContentLoaded', ()=>{
  // CountUp
  document.querySelectorAll('.count-up').forEach(el=>{
    const v = parseInt(el.getAttribute('data-value')||'0',10);
    const c = new countUp.CountUp(el, v, { duration: 1.6, separator: '.' });
    if(!c.error) c.start();
  });

  // Uploads last 7 days
  const uc = document.getElementById('uploadsChart');
  if (uc){
    new Chart(uc, {
      type:'bar',
      data:{
        labels:['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
        datasets:[{ label:'Uploads', data:{!! json_encode($uploads7dCounts ?? [0,0,0,0,0,0,0]) !!}, backgroundColor:'#0b3c8f', borderRadius:6 }]
      },
      options:{ plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ color:'#6b7280' } }, y:{ ticks:{ color:'#6b7280' } } } }
    });
  }

  // Storage usage
  const sc = document.getElementById('storageChart');
  if (sc){
    const storageData = {!! json_encode([
      ($storageUsed ?? 68),
      max(($storageTotal ?? 100) - ($storageUsed ?? 68), 0)
    ]) !!};
    new Chart(sc, {
      type:'doughnut',
      data:{ labels:['Terpakai','Sisa'], datasets:[{ data: storageData, backgroundColor:['#0b3c8f','#e5e7eb'], borderWidth:2 }] },
      options:{ cutout:'60%', plugins:{ legend:{ display:false } } }
    });
  }

});
</script>
@endsection
