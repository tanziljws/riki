@extends('layouts.app')
@section('title', __('admin.gallery.add_photo'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.05);overflow:hidden">
  <h2 style="margin:0 0 12px;font-size:18px;font-weight:800;color:#0a1f4f">{{ __('admin.gallery.add_photo') }}</h2>

  @if ($errors->any())
    <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:10px;margin-bottom:10px">{{ $errors->first() }}</div>
  @endif

  <form id="galeriCreateForm" action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="g-form">
    @csrf
    <div class="fi home-hide" id="titleWrap">
      <label class="lb">{{ __('admin.gallery.form.title') }}</label>
      <input class="ip" type="text" name="title" value="{{ old('title') }}" placeholder="{{ __('admin.gallery.form.title_placeholder') }}">
    </div>
    <div class="fi home-hide" id="descWrap">
      <label class="lb">{{ __('admin.gallery.form.description') }}</label>
      <textarea class="ip" name="description" rows="3" placeholder="{{ __('admin.gallery.form.description_placeholder') }}">{{ old('description') }}</textarea>
    </div>
    <div class="fi">
      <label class="lb">{{ __('admin.gallery.form.category') }}</label>
      <select class="ip" name="category_id" required>
        <option value="">{{ __('admin.gallery.form.choose_category') }}</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ old('category_id')==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="fi" id="singleImageWrap">
      <label class="lb">{{ __('admin.gallery.form.image') }}</label>
      <div id="dropZone" class="dz">
        <div class="thumb" id="previewBox"><span>{{ __('admin.gallery.form.preview_appears_here') }}</span></div>
        <div class="dz-ctrl">
          <input id="imageInput" class="file" type="file" name="image" accept="image/*">
          <button type="button" id="pickBtn" class="btn pick">{{ __('admin.gallery.form.pick_image') }}</button>
        </div>
        <div class="hint">{{ __('admin.gallery.form.size_hint') }}</div>
      </div>
    </div>

    <div class="fi" id="homeImagesWrap" style="display:none">
      <label class="lb">Gambar (1–5 foto untuk Home)</label>
      <input id="homeImages" class="ip" type="file" name="images[]" accept="image/*" multiple>
      <div class="hint">Pilih 1 sampai 5 foto. Bisa unggah satu per satu (ulang form). Maks 4MB/foto.</div>
    </div>
    <div class="actions">
      <a href="{{ route('admin.galeri.index') }}" class="btn ghost">{{ __('admin.common.cancel') }}</a>
      <button type="submit" class="btn primary">{{ __('admin.common.save') }}</button>
    </div>
  </form>

  <style>
    .g-form{display:grid;gap:14px;max-width:720px}
    .fi{display:grid;gap:8px}
    .lb{font-weight:800;color:#0a1f4f}
    .ip{width:100%;max-width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:12px;outline:none;background:#fff}
    .ip:focus{border-color:#93c5fd;box-shadow:0 0 0 4px rgba(147,197,253,.25)}
    .dz{border:1px dashed #cbd5e1;border-radius:14px;padding:12px;background:#f8fafc;max-width:100%;box-sizing:border-box}
    .dz-ctrl{display:flex;gap:10px;align-items:center;margin-top:10px}
    .file{display:none}
    .thumb{width:100%;max-width:420px;height:220px;border-radius:12px;background:#e2e8f0;display:grid;place-items:center;overflow:hidden;border:1px solid #e5e7eb;box-shadow:inset 0 0 0 6px #fff, 0 8px 22px rgba(2,6,23,.06)}
    .thumb img{width:100%;height:100%;object-fit:cover}
    .hint{color:#64748b;font-size:12px;margin-top:6px}
    .actions{display:flex;gap:10px;margin-top:4px;width:100%;box-sizing:border-box}
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border-radius:12px;font-weight:800;border:1px solid transparent;cursor:pointer;max-width:100%;box-sizing:border-box;-webkit-appearance:none;appearance:none}
    .btn.primary{background:#0a1f4f;color:#fff;border:2px solid #d4af37}
    .btn.ghost{background:#fff;border:1px solid #e5e7eb;color:#0f172a;text-decoration:none}
    .btn.primary{background:#0a1f4f!important;color:#fff;border:2px solid #d4af37;padding:10px 16px;border-radius:12px;font-weight:800;-webkit-appearance:none;appearance:none}
    .btn.primary:hover{filter:brightness(1.05)}
    .home-hide.hidden{display:none}
    @media (max-width: 768px){
      .g-form{max-width:100%}
      .actions{width:100%; flex-direction:column; align-items:stretch}
      .btn{width:100%; max-width:none; margin:0}
      .actions .btn.primary{ order:-1; }
    }
  </style>

  <script>
    (function(){
      const form = document.getElementById('galeriCreateForm');
      const cat = form.querySelector('select[name="category_id"]');
      const file = document.getElementById('imageInput');
      const homeFiles = document.getElementById('homeImages');
      const titleWrap = document.getElementById('titleWrap');
      const descWrap = document.getElementById('descWrap');
      const singleWrap = document.getElementById('singleImageWrap');
      const homeWrap = document.getElementById('homeImagesWrap');
      const pick = document.getElementById('pickBtn');
      const box = document.getElementById('previewBox');
      const dz = document.getElementById('dropZone');
      const maxSize = 4 * 1024 * 1024; // 4MB

      function showPreview(f){
        if(!f) return;
        if(f.size > maxSize){
          alert('{{ __('admin.common.file_too_big') }}');
          const inp = document.getElementById('imageInput');
          if (inp) inp.value = '';
          return;
        }
        const url = URL.createObjectURL(f);
        box.innerHTML = '<img src="'+url+'" alt="preview">';
      }

      function toggleForHome(){
        const label = (cat.options[cat.selectedIndex] && (cat.options[cat.selectedIndex].text || '')).toLowerCase().trim();
        const isHome = label === 'home';
        if (isHome){
          // hide title/desc + single image, show multiple
          titleWrap.classList.add('hidden');
          descWrap.classList.add('hidden');
          singleWrap.style.display = 'none';
          homeWrap.style.display = '';
          if (file) { file.value = ''; box.innerHTML = '<span>{{ __('admin.gallery.form.preview_appears_here') }}</span>'; }
          if (homeFiles) homeFiles.required = true;
          if (file) file.required = false;
        } else {
          titleWrap.classList.remove('hidden');
          descWrap.classList.remove('hidden');
          singleWrap.style.display = '';
          homeWrap.style.display = 'none';
          if (homeFiles) homeFiles.required = false;
          if (file) file.required = true;
        }
      }

      if (pick && file) pick.addEventListener('click', ()=> file.click());
      if (file) file.addEventListener('change', ()=> showPreview(file.files[0]));
      if (cat) cat.addEventListener('change', toggleForHome);
      // run once on load to reflect current selection
      toggleForHome();

      if (dz && file) {
        dz.addEventListener('dragover', e=>{e.preventDefault(); dz.style.background = '#eef2ff';});
        dz.addEventListener('dragleave', ()=>{dz.style.background = '#f8fafc';});
        dz.addEventListener('drop', e=>{
          e.preventDefault(); dz.style.background = '#f8fafc';
          const f0 = e.dataTransfer.files && e.dataTransfer.files[0];
          if(f0){
            file.files = e.dataTransfer.files;
            showPreview(f0);
          }
        });
      }
    })();
  </script>
</div>
@endsection
