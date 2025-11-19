@extends('layouts.app')

@section('title', 'Galeri - SMKN 4 Bogor')

@section('content')
<section class="gallery-page" aria-labelledby="gallery-title">
    <div class="container">
        <h1 id="gallery-title" class="title">Galeri <span class="highlight">SMKN 4 BOGOR</span></h1>
        <p class="subtitle">Jelajahi momen terbaik dan dokumentasi kegiatan sekolah kami.</p>

        <!-- Tabs Kategori -->
        <div class="tabs">
            <button class="tab active" data-filter="all"><i class="fas fa-table-cells"></i> Semua</button>
            <button class="tab" data-filter="kepala-sekolah"><i class="fas fa-user-tie"></i> Kepala Sekolah</button>
            <button class="tab" data-filter="guru"><i class="fas fa-chalkboard-teacher"></i> Guru</button>
            <button class="tab" data-filter="jurusan"><i class="fas fa-school"></i> Jurusan</button>
            <button class="tab" data-filter="kegiatan"><i class="fas fa-calendar-check"></i> Kegiatan</button>
            <button class="tab" data-filter="eskul"><i class="fas fa-users"></i> Ekstrakurikuler</button>
        </div>

        <!-- Grid Galeri -->
        <div class="grid">
            @foreach ($items as $it)
            <article class="card" data-category="{{ $it['kategori'] }}" data-id="{{ $it['id'] }}" data-likes="{{ $it['likes_count'] ?? 0 }}" data-comments="{{ $it['comments_count'] ?? 0 }}">
                <div class="image" style="background-image:url('{{ asset($it['img']) }}');"></div>
                <div class="content">
                    <h3>{{ $it['judul'] }}</h3>
                    <p>{{ $it['desk'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

<!-- ========== STYLE ========== -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
* { font-family:'Poppins', sans-serif; box-sizing:border-box; }

.gallery-page { background:#f8fafc; padding:40px 0; }
.container { max-width:1200px; margin:0 auto; padding:0 20px; }
.title { text-align:center; font-size:2rem; font-weight:800; color:#004aad; margin-bottom:8px; }
.highlight { color:#f4b400; }
.subtitle { text-align:center; color:#555; margin-bottom:30px; }

.tabs { display:flex; justify-content:center; flex-wrap:wrap; gap:10px; margin-bottom:30px; }
.tab {
    border:1px solid #004aad; border-radius:999px;
    padding:10px 18px; background:#fff; color:#004aad; font-weight:600;
    cursor:pointer; transition:all .3s;
}
.tab:hover { background:#004aad; color:#fff; transform:translateY(-2px); box-shadow:0 4px 14px rgba(0,74,173,.25); }
.tab.active { background:#004aad; color:#fff; }

.grid { display:grid; gap:24px; grid-template-columns: repeat(3, 1fr); }

.card {
  background:#fff; border-radius:18px; overflow:hidden;
  box-shadow:0 8px 25px rgba(0,0,0,.06);
  transition:transform .3s ease, box-shadow .3s ease;
}
.card:hover { transform:translateY(-6px); box-shadow:0 12px 30px rgba(0,0,0,.12); }
.card .image {
  height: clamp(260px, 26vw, 320px);
  background-size: contain;
  background-position: top center;
  background-repeat: no-repeat;
  background-color: #f3f4f6;
}
.card .content { padding:16px; }
.card h3 { font-size:1.1rem; color:#222; margin-bottom:6px; }
.card p { color:#666; font-size:.95rem; }

/* Comment list under each card */
.comments { padding:0 16px 14px; max-height: 140px; overflow:auto; }
.comment-item { display:flex; gap:8px; margin-top:8px; }
.comment-avatar { width:26px; height:26px; border-radius:50%; background:#e5e7eb; flex:0 0 26px; }
.comment-body { background:#f8fafc; border:1px solid #e5e7eb; padding:8px 10px; border-radius:10px; flex:1; }
.comment-name { font-weight:700; color:#0b4aad; font-size:.9rem; }
.comment-text { color:#334155; font-size:.92rem; }

/* Viewer image */
.viewer-img { width:100%; height:auto; border-radius:12px; margin-bottom:10px; }

.like-bar {
  display:flex; justify-content:space-between; align-items:center;
  padding:12px 16px; border-top:1px solid #eee;
}
.like-bar button {
  background:none; border:none; color:#555;
  cursor:pointer; display:flex; align-items:center; gap:6px;
  font-weight:500; transition:.25s;
}
.like-bar button:hover { color:#004aad; transform:translateY(-2px); }
.like.active { color:#e63946; transform:scale(1.2); }

/* Modal Komentar */
.comment-modal {
  position: static; background: transparent;
  display:none; align-items:stretch; justify-content:stretch;
  opacity:1; pointer-events:auto; transition:none;
  z-index:auto; margin-top: 16px;
}
.comment-modal.active { display:block; }

.comment-box {
  position:relative;
  background:#fff; border-radius:16px; width:90%; max-width:450px;
  padding:22px; box-shadow:0 10px 40px rgba(0,0,0,.25);
  animation:popUp .3s ease;
}
@keyframes popUp { from{transform:scale(.9);opacity:0;} to{transform:scale(1);opacity:1;} }

.comment-box h4 { color:#004aad; margin-bottom:10px; }
.comment-box textarea {
  width:100%; border:1px solid #ccc; border-radius:10px;
  padding:10px; resize:none; min-height:80px;
  font-family:inherit; margin-bottom:12px;
}
.comment-actions { display:flex; justify-content:flex-end; gap:10px; }
.comment-actions button {
  border:none; border-radius:8px; padding:8px 14px;
  font-weight:600; cursor:pointer; transition:.25s;
}
.comment-actions .send { background:#004aad; color:#fff; }
.comment-actions .delete { background:#ccc; color:#333; }
.comment-actions .send:hover { background:#003680; }
.comment-actions .delete:hover { background:#999; }

.comment-close {
  position:absolute; top:10px; right:14px;
  background:none; border:none; font-size:1.4rem;
  color:#444; cursor:pointer; transition:.25s;
}
.comment-close:hover { color:#e63946; transform:rotate(90deg); }

.thank-you {
  display:none; text-align:center; margin-top:8px;
  color:green; font-weight:600; animation:fadeIn .4s ease;
}
@keyframes fadeIn { from{opacity:0;} to{opacity:1;} }

/* Responsive columns so photos stay medium, not too small/big */
@media (max-width: 1024px) {
  .grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .grid { grid-template-columns: 1fr; }
}

/* Mobile specific tweaks */
@media (max-width: 768px) {
  .container { padding: 0 12px; }
  .title { font-size: 1.6rem; }
  .subtitle { font-size: .95rem; }
  .tabs { justify-content: flex-start; flex-wrap: nowrap; overflow-x: auto; gap: 8px; padding-bottom: 6px; scrollbar-width: thin; }
  .tabs::-webkit-scrollbar { height: 6px; }
  .tabs::-webkit-scrollbar-thumb { background: #d0d7e2; border-radius: 999px; }
  .tab { white-space: nowrap; padding: 8px 14px; font-size: .95rem; }
}

@media (max-width: 480px) {
  .card .image { height: clamp(220px, 60vw, 280px); }
  .card .content { padding: 12px; }
  .card h3 { font-size: 1rem; }
  .card p { font-size: .9rem; }
}
</style>

<!-- ========== SCRIPT ========== -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const isUser = {{ (auth()->check() && auth()->user()->role === 'user') ? 'true' : 'false' }};
  const userName = "{{ auth()->check() ? auth()->user()->name : '' }}";
  const loginUrl = "{{ route('user.login') }}";
  const detailUrlTemplate = "{{ route('galeri.show', ['id' => '__ID__']) }}";
  const stateUrlTemplate = "{{ route('galeri.state', ['gallery' => '__ID__']) }}";
  const tabs = document.querySelectorAll('.tab');
  const cards = document.querySelectorAll('.card');
  const comments = Array.from({length: cards.length}, ()=>[]);

  // === Filter Function ===
  function applyFilter(filterValue) {
    let visibleCount = 0;
    cards.forEach(c=>{
      const category = c.dataset.category;
      const shouldShow = filterValue === 'all' || category === filterValue;
      c.style.display = shouldShow ? '' : 'none';
      if (shouldShow) visibleCount++;
    });
    console.log('Filter applied:', filterValue, 'Visible:', visibleCount, 'Total:', cards.length);
  }

  // === Filter Tabs ===
  tabs.forEach(tab=>{
    tab.addEventListener('click', ()=>{
      tabs.forEach(t=>t.classList.remove('active'));
      tab.classList.add('active');
      const f = tab.dataset.filter;
      applyFilter(f);
    });
  });
  
  // Apply default filter "all" saat pertama load
  applyFilter('all');

  // === Like, Comment, Share bar ===
  cards.forEach((card, i)=>{
    const bar = document.createElement('div');
    bar.className='like-bar';

    const like=document.createElement('button');
    const initialLikes = parseInt(card.getAttribute('data-likes')||'0',10);
    like.innerHTML=`<i class="far fa-heart"></i> <span>${initialLikes}</span>`;
    like.addEventListener('click',()=>{
      if(!isUser){ window.location.href = loginUrl; return; }
      const count=like.querySelector('span');
      let val=parseInt(count.textContent);
      if(like.classList.contains('active')){like.classList.remove('active');count.textContent=val-1;}
      else{like.classList.add('active');count.textContent=val+1;}
    });

    const initialComments = parseInt(card.getAttribute('data-comments')||'0',10);
    const comment=document.createElement('button');
    comment.innerHTML=`<i class="far fa-comment"></i> Komentar (<span class="c-count">${initialComments}</span>)`;
    comment.addEventListener('click',()=>{
      if(!isUser){ window.location.href = loginUrl; return; }
      const id = card.getAttribute('data-id');
      const url = detailUrlTemplate.replace('__ID__', id);
      window.location.href = url;
    });

    const share=document.createElement('button');
    share.innerHTML='<i class="fas fa-download"></i> Unduh';
    share.addEventListener('click',()=>{
      const url=card.querySelector('.image').style.backgroundImage.slice(5,-2);
      const a=document.createElement('a');
      a.href=url;a.download='foto-smkn4.jpg';a.click();
    });

    bar.append(like,comment,share);
    card.append(bar);

    // Container list komentar di bawah kartu
    const list = document.createElement('div');
    list.className = 'comments';
    list.dataset.index = i;
    card.append(list);

    // Buat gambar dapat di-klik seperti postingan -> buka modal komentar
    const imgDiv = card.querySelector('.image');
    imgDiv.style.cursor = 'pointer';
    imgDiv.addEventListener('click', ()=>{
      const id = card.getAttribute('data-id');
      const url = detailUrlTemplate.replace('__ID__', id);
      window.location.href = url;
    });

    // Fetch real counts for logged-in users to mirror detail page
    if(isUser){
      const id = card.getAttribute('data-id');
      const stateUrl = stateUrlTemplate.replace('__ID__', id);
      fetch(stateUrl, { headers:{ 'Accept':'application/json' } })
        .then(res=>res.ok?res.json():null)
        .then(data=>{
          if(!data) return;
          // like state and count
          like.classList.toggle('active', !!data.liked_by_me);
          const likeCountSpan = like.querySelector('span');
          if(likeCountSpan) likeCountSpan.textContent = String(data.likes_count || 0);
          // comment count badge
          const cCountSpan = card.querySelector('.c-count');
          if(cCountSpan) cCountSpan.textContent = String((data.comments || []).length);
        })
        .catch(()=>{});
    }
  });

  // === Viewer Modal (image + social bar + comments) ===
  const modal=document.createElement('div');
  modal.className='comment-modal';
  modal.innerHTML=`
    <div class="comment-box">
      <button class="comment-close">&times;</button>
      <img id="viewerImg" class="viewer-img" alt="view" />
      <div class="like-bar" style="padding:8px 0 12px">
        <button class="v-like"><i class="far fa-heart"></i> <span class="v-like-count">0</span></button>
        <button class="v-comment"><i class="far fa-comment"></i> Komentar (<span class="v-c-count">0</span>)</button>
        <button class="v-download"><i class="fas fa-download"></i> Unduh</button>
      </div>
      <div class="modal-list" style="max-height:200px;overflow:auto;margin-bottom:10px"></div>
      <textarea placeholder="Tulis komentar kamu..."></textarea>
      <div class="comment-actions">
        <button class="delete">Hapus</button>
        <button class="send">Kirim</button>
      </div>
      <div class="thank-you">Terima kasih atas saran Anda!</div>
    </div>
  `;
  // tempatkan viewer tepat di bawah grid
  const gridEl = document.querySelector('.grid');
  gridEl.insertAdjacentElement('afterend', modal);

  const textarea=modal.querySelector('textarea');
  const sendBtn=modal.querySelector('.send');
  const deleteBtn=modal.querySelector('.delete');
  const closeBtn=modal.querySelector('.comment-close');
  const thankText=modal.querySelector('.thank-you');
  const modalList=modal.querySelector('.modal-list');
  let currentIndex = -1;
  let currentCard = null;

  // Enter to send (Shift+Enter for newline)
  textarea.addEventListener('keydown', (e)=>{
    if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); sendBtn.click(); }
  });

  function openViewer(i){
    currentIndex = i;
    currentCard = cards[i];
    // set image src
    const url=currentCard.querySelector('.image').style.backgroundImage.slice(5,-2);
    document.getElementById('viewerImg').src = url;
    // sync like state/count
    const cardLike=currentCard.querySelector('.like-bar button');
    const vLike=modal.querySelector('.v-like');
    const vLikeCount=modal.querySelector('.v-like-count');
    const cardLikeCount = cardLike.querySelector('span');
    vLike.classList.toggle('active', cardLike.classList.contains('active'));
    vLikeCount.textContent = cardLikeCount ? cardLikeCount.textContent : '0';
    // sync comment count
    const vCCount=modal.querySelector('.v-c-count');
    vCCount.textContent = String(comments[i].length);
    // set download
    modal.querySelector('.v-download').onclick=()=>{ const a=document.createElement('a'); a.href=url; a.download='foto-smkn4.jpg'; a.click(); };
    // v-like toggles card like
    modal.querySelector('.v-like').onclick=()=>{
      if(!isUser){ window.location.href = loginUrl; return; }
      cardLike.click();
      vLike.classList.toggle('active', cardLike.classList.contains('active'));
      vLikeCount.textContent = currentCard.querySelector('.like-bar button span').textContent;
    };
    // v-comment focuses textarea
    modal.querySelector('.v-comment').onclick=()=> textarea.focus();
    // open
    modal.classList.add('active');
    modal.scrollIntoView({ behavior:'smooth', block:'start' });
    textarea.value='';
    thankText.style.display='none';
    renderModalComments();
  }

  function renderCardComments(i){
    const list = document.querySelector(`.comments[data-index="${i}"]`);
    if(!list) return;
    const items = comments[i];
    const last = items.slice(-3); // tampilkan 3 terbaru
    let html = last.map(c=>`
      <div class="comment-item">
        <div class="comment-avatar"></div>
        <div class="comment-body">
          <div class="comment-name">${c.name}</div>
          <div class="comment-text">${c.text}</div>
        </div>
      </div>
    `).join('');
    if(items.length > 3){
      html += `<div style="margin-top:6px"><button class="view-all" style="background:none;border:none;color:#0b4aad;font-weight:700;cursor:pointer">Lihat semua (${items.length}) komentar</button></div>`;
    }
    list.innerHTML = html;
    const btn = list.querySelector('.view-all');
    if(btn){ btn.addEventListener('click', ()=> openViewer(i)); }

    // update comment count in bar
    const card = cards[i];
    const countSpan = card.querySelector('.like-bar .c-count');
    if(countSpan){ countSpan.textContent = String(items.length); }
  }

  function renderModalComments(){
    if(currentIndex<0) return;
    modalList.innerHTML = comments[currentIndex].map(c=>`
      <div class="comment-item">
        <div class="comment-avatar"></div>
        <div class="comment-body">
          <div class="comment-name">${c.name}</div>
          <div class="comment-text">${c.text}</div>
        </div>
      </div>
    `).join('');
  }

  sendBtn.addEventListener('click',()=>{
    if(!isUser){ window.location.href = loginUrl; return; }
    const text = textarea.value.trim();
    if(!text) return;
    if(currentIndex<0) return;
    comments[currentIndex].push({ name: userName || 'User', text });
    textarea.value='';
    renderModalComments();
    renderCardComments(currentIndex);
    const vCCount=modal.querySelector('.v-c-count');
    vCCount.textContent = String(comments[currentIndex].length);
    thankText.style.display='block';
    setTimeout(()=>thankText.style.display='none',1800);
  });

  deleteBtn.addEventListener('click',()=>{ textarea.value=''; });
  closeBtn.addEventListener('click',()=>{ modal.classList.remove('active'); });

  // inline viewer: tidak menutup saat klik area luar
});
</script>
@endsection
