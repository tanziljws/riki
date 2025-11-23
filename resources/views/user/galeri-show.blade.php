@extends('layouts.app')

@section('title', $item['judul'].' - Galeri')

@section('content')
@php($isUser = auth()->check() && auth()->user()->role === 'user')
<section class="gd" aria-labelledby="detail-title">
  <div class="gd-container">
    <a href="{{ route('galeri') }}" class="gd-close" aria-label="Tutup dan kembali ke Galeri">✕</a>

    <article class="gd-card {{ $isUser ? '' : 'guest' }}">
      <header class="gd-header">
        <h1 id="detail-title">{{ $item['judul'] }}</h1>
        <p class="gd-sub">{{ $item['desk'] }}</p>
      </header>

      <div class="gd-media">
        <img src="{{ $item['img'] }}" alt="{{ $item['judul'] }}" />
      </div>

      <div class="gd-actions" role="toolbar" aria-label="Aksi postingan">
        <button class="btn like" aria-pressed="false" title="Suka"><i class="far fa-heart"></i> <span class="badge like-count">0</span></button>
        <button class="btn comment" title="Komentar"><i class="far fa-comment"></i> <span class="c-text">Komentar</span> <span class="badge c-count">0</span></button>
        <button class="btn download"><i class="fas fa-download"></i> Unduh</button>
        @unless($isUser)
          <a href="{{ route('user.login') }}" class="btn primary" style="margin-left:auto">Masuk/Daftar</a>
        @endunless
      </div>

      <div class="gd-comments" aria-live="polite"></div>

      <div class="gd-form">
        <textarea id="c-input" placeholder="Tulis komentar kamu..."></textarea>
        <div class="gd-form-actions">
          <button class="btn ghost clear">Hapus</button>
          <button class="btn primary send">Kirim</button>
        </div>
        <div class="gd-thanks">Komentar terkirim, terima kasih!</div>
      </div>
    </article>
  </div>
</section>

<style>
  .gd { background:#f8fafc; padding:24px 0 40px; }
  .gd-container { position:relative; max-width:720px; margin:0 auto; padding:0 16px; }
  .gd-close { position:absolute; right:8px; top:-6px; width:34px; height:34px; display:grid; place-items:center; border-radius:10px; background:#fff; color:#0b4aad; border:1px solid #dbeafe; font-weight:900; box-shadow:0 6px 14px rgba(2,6,23,.08); }
  .gd-close:hover { background:#f3f6fb; }
  .gd-card { background:#fff; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08); overflow:hidden; }
  .gd-header { padding:16px 18px 8px; }
  .gd-header h1 { font-size:1.2rem; margin:0 0 6px; color:#0f172a; }
  .gd-sub { margin:0; color:#475569; font-size:.95rem; }
  .gd-media { background:#0b0b0b; display:flex; justify-content:center; align-items:center; }
  .gd-media img { width:100%; max-height:70vh; object-fit:contain; display:block; }
  .gd-actions { display:flex; gap:10px; flex-wrap:wrap; padding:12px 16px; border-top:1px solid #eef2f7; align-items:center; }
  .btn { background:#fff; border:1px solid #dbe3ef; color:#334155; border-radius:999px; padding:8px 14px; font-weight:600; cursor:pointer; transition:.2s; line-height:1; display:inline-flex; align-items:center; gap:8px; }
  .btn:hover { background:#f1f5ff; color:#004aad; border-color:#c9d7f0; transform:translateY(-1px); }
  .btn.primary { background:#004aad; color:#fff; border-color:#004aad; }
  .btn.primary:hover { background:#003680; border-color:#003680; }
  .btn.ghost { background:#f1f5f9; }
  .btn.like.active { color:#e63946; border-color:#f2c1c4; background:#fff5f5; }
  .badge { background:#eef2ff; color:#1d4ed8; border-radius:999px; padding:2px 8px; font-size:.85rem; }
  
  .btn.disabled { opacity:.6; pointer-events:none; }
  .gd-form textarea:disabled { background:#f1f5f9; cursor:not-allowed; }
  .gd-comments { padding:0 16px 8px; max-height:240px; overflow:auto; }
  .c-item { display:flex; gap:10px; margin-top:10px; }
  .c-ava { width:32px; height:32px; border-radius:50%; background:#e5e7eb; flex:0 0 32px; display:grid; place-items:center; color:#0b4aad; font-weight:700; overflow:hidden; }
  .c-ava img { width:100%; height:100%; object-fit:cover; display:block; }
  .c-body { background:#f8fafc; border:1px solid #e5e7eb; padding:8px 10px; border-radius:10px; flex:1; }
  .c-head { display:flex; align-items:center; justify-content:space-between; gap:8px; }
  .c-name { font-weight:700; color:#0b4aad; font-size:.9rem; }
  .c-time { color:#94a3b8; font-size:.8rem; white-space:nowrap; }
  .c-text { color:#334155; font-size:.95rem; }
  .c-foot { display:flex; align-items:center; gap:12px; margin-top:6px; font-size:.85rem; color:#64748b; }
  .c-like { display:inline-flex; align-items:center; gap:6px; cursor:pointer; color:#475569; border:1px solid #dbe3ef; padding:4px 10px; border-radius:999px; background:#fff; }
  .c-like.active { color:#e11d48; border-color:#fecdd3; background:#fff1f2; }
  .c-del { background:none; border:none; color:#ef4444; cursor:pointer; padding:4px 6px; border-radius:8px; }
  .c-del:hover { background:#fee2e2; }
  .gd-form { padding:8px 16px 16px; }
  #c-input { width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px; min-height:90px; resize:none; font-family:inherit; }
  .gd-form-actions { margin-top:10px; display:flex; gap:10px; justify-content:flex-end; }
  .gd-thanks { display:none; margin-top:8px; color:green; font-weight:700; }
  @media (max-width: 640px){
    .gd-container { padding:0 12px; }
    .gd-header h1 { font-size:1.05rem; }
    .btn { padding:8px 12px; font-weight:600; }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const isUser = {{ (auth()->check() && auth()->user()->role === 'user') ? 'true' : 'false' }};
  const loginUrl = "{{ route('user.login') }}";
  const userName = "{{ auth()->check() ? auth()->user()->name : 'User' }}";
  const userAvatar = "{{ auth()->check() ? (auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : ('https://www.gravatar.com/avatar/'.md5(strtolower(trim(auth()->user()->email))).'?d=identicon')) : '' }}";
  const imgUrl = "{{ $item['img'] }}";
  const postId = {{ $item['id'] }};
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const stateUrl = "{{ route('galeri.state', ['gallery' => $item['id']]) }}";
  const likeUrl = "{{ route('galeri.like', ['gallery' => $item['id']]) }}";
  const commentUrl = "{{ route('galeri.comment', ['gallery' => $item['id']]) }}";

  const likeBtn = document.querySelector('.btn.like');
  const likeCount = document.querySelector('.like-count');
  const cBtn = document.querySelector('.btn.comment');
  const cCount = document.querySelector('.c-count');
  const dBtn = document.querySelector('.btn.download');
  const list = document.querySelector('.gd-comments');
  const input = document.getElementById('c-input');
  const send = document.querySelector('.btn.primary.send');
  const clearBtn = document.querySelector('.btn.ghost.clear');
  const thanks = document.querySelector('.gd-thanks');

  let comments = [];

  // If guest, visually disable like/comment input (tetap bisa lihat gambar & share/unduh)
  if(!isUser){
    likeBtn.classList.add('disabled');
    likeBtn.setAttribute('aria-disabled','true');
    input.setAttribute('disabled','true');
    send.setAttribute('disabled','true');
  }

  likeBtn.addEventListener('click', async ()=>{
    if(!isUser){ window.location.href = loginUrl; return; }
    try{
      const res = await fetch(likeUrl, { method:'POST', headers:{ 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } });
      if(!res.ok) throw new Error('Like gagal');
      const data = await res.json();
      likeBtn.classList.toggle('active', data.liked);
      likeCount.textContent = String(data.likes_count || 0);
    }catch(e){ console.error(e); }
  });

  function fmtDate(ts){
    const d = new Date(ts);
    const pad=n=>String(n).padStart(2,'0');
    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
  }

  function initials(name){
    if(!name) return '?';
    return name.trim().charAt(0).toUpperCase();
  }

  async function fetchState(){
    try{
      const res = await fetch(stateUrl, { headers:{ 'Accept':'application/json' } });
      const data = await res.json();
      likeBtn.classList.toggle('active', !!data.liked_by_me);
      likeCount.textContent = String(data.likes_count || 0);
      comments = Array.isArray(data.comments) ? data.comments.map(c=>({
        id: c.id,
        name: c.user_name,
        avatar: c.user_avatar ? ('{{ asset('storage') }}/'+c.user_avatar) : '',
        text: c.text,
        time: new Date(c.created_at).getTime(),
        mine: !!c.mine
      })) : [];
    }catch(e){ comments = []; }
  }

  function render(){
    list.innerHTML = comments.map(c=>{
      const isMine = !!c.mine || c.name === userName;
      return `
      <div class="c-item" data-id="${c.id}">
        <div class="c-ava">${c.avatar ? `<img src="${c.avatar}" alt="${c.name}">` : initials(c.name)}</div>
        <div class="c-body">
          <div class="c-head"><div class="c-name">${c.name}</div><span class="c-time">${fmtDate(c.time)}</span></div>
          <div class="c-text">${c.text}</div>
          <div class="c-foot">
            ${isMine ? '<button class="c-del" type="button"><i class="fas fa-trash-alt"></i> Hapus</button>' : ''}
          </div>
        </div>
      </div>`;
    }).join('');
    cCount.textContent = String(comments.length);
  }

  send.addEventListener('click', async ()=>{
    if(!isUser){ window.location.href = loginUrl; return; }
    const t = input.value.trim();
    if(!t) return;
    try{
      const res = await fetch(commentUrl, { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify({ text: t }) });
      if(!res.ok) throw new Error('Komentar gagal');
      const c = await res.json();
      comments.unshift({ id: c.id, name: c.user_name, text: c.text, time: new Date(c.created_at).getTime(), avatar: c.user_avatar ? ('{{ asset('storage') }}/'+c.user_avatar) : userAvatar, mine: true });
      input.value='';
      render();
      thanks.style.display='block';
      setTimeout(()=>thanks.style.display='none', 1600);
    }catch(e){ console.error(e); }
  });

  clearBtn.addEventListener('click', ()=> input.value='');

  input.addEventListener('keydown', (e)=>{
    if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); send.click(); }
  });

  dBtn.addEventListener('click', ()=>{
    const a = document.createElement('a');
    a.href = imgUrl; a.download = '{{ Str::slug($item['judul']) }}.jpg'; a.click();
  });

  // Delegated handlers for like/delete
  list.addEventListener('click', async (e)=>{
    const itemEl = e.target.closest('.c-item');
    if(!itemEl) return;
    const id = parseInt(itemEl.getAttribute('data-id'));
    const idx = comments.findIndex(x=>x.id===id);
    if(idx<0) return;
    if(e.target.closest('.c-del')){
      if(!(comments[idx].mine || comments[idx].name===userName)) return;
      try{
        const delUrl = "{{ url('/comments') }}/" + id;
        const res = await fetch(delUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } });
        if(!res.ok) throw new Error('Hapus gagal');
        comments.splice(idx,1);
        render();
      }catch(err){ console.error(err); }
      return;
    }
  });

  // initial load from server
  (async()=>{ await fetchState(); render(); })();
});
</script>
@endsection
