@extends('layouts.app')
@section('title', __('admin.majors.title'))

@section('content')
<div style="padding:24px">
  <div class="jrs-top" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;align-items:center;margin-bottom:18px">
    <h3 style="font-size:22px;font-weight:800;color:#0a1f4f;margin:0">{{ __('admin.majors.list_title') }}</h3>
    <div style="display:flex;gap:10px;align-items:center">
      <div class="search-wrap" style="position:relative">
        <input id="jurusanSearch" type="text" placeholder="{{ __('admin.majors.search_placeholder') }}" style="padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:12px;outline:none;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
      </div>
      <a class="add-btn" href="{{ route('admin.jurusan.create') }}" style="padding:10px 16px;background:#0a1f4f;color:#fff;border-radius:12px;border:2px solid #d4af37;text-decoration:none;font-weight:800;display:inline-flex;align-items:center;gap:8px"><i class="fa-solid fa-plus"></i> {{ __('admin.majors.add') }}</a>
    </div>
  </div>

  <div class="jrs-grid">
    @forelse($jurusan as $item)
      <div class="jrs-card">
        <div class="jrs-logo">
          @if($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
          @else
            <span>{{ __('admin.majors.form.no_logo') }}</span>
          @endif
        </div>
        <h4 class="jrs-title">{{ $item->nama }}</h4>
        <p class="jrs-desc">{{ $item->deskripsi ?? '-' }}</p>
        <div class="jrs-actions">
          <a href="{{ route('admin.jurusan.edit', $item->id) }}" class="btn-edit"><i class="fa-solid fa-pen"></i> {{ __('admin.common.edit') }}</a>
          <form action="{{ route('admin.jurusan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.majors.confirm_delete') }}');">
            @csrf @method('DELETE')
            <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i> {{ __('admin.common.delete') }}</button>
          </form>
        </div>
      </div>
    @empty
      <p style="grid-column:1/-1;color:#6b7280">{{ __('admin.majors.no_data') }}</p>
    @endforelse
  </div>

  <style>
    .jrs-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
    .jrs-card{background:#fff;border:1px solid #eef0f4;border-radius:18px;padding:18px;box-shadow:0 8px 22px rgba(2,6,23,.06);display:flex;flex-direction:column;align-items:center;gap:10px;transition:transform .25s ease, box-shadow .25s ease}
    .jrs-card:hover{transform:translateY(-6px);box-shadow:0 16px 40px rgba(37,99,235,.18)}
    .jrs-logo{width:120px;height:120px;border-radius:999px;display:grid;place-items:center;overflow:hidden;background:#f1f5f9;position:relative;box-shadow:inset 0 0 0 6px #fff, 0 8px 24px rgba(2,6,23,.08);border:3px solid #e2e8f0}
    .jrs-logo::after{content:"";position:absolute;inset:-4px;border-radius:999px;box-shadow:0 0 0 4px rgba(212,175,55,.4);opacity:.6}
    .jrs-logo img{width:100%;height:100%;object-fit:contain;object-position:center;background:#fff}
    .jrs-logo span{font-size:12px;color:#94a3b8}
    .jrs-title{margin:4px 0 0;font-size:16px;font-weight:900;color:#0a1f4f;text-align:center}
    .jrs-desc{margin:2px 0 10px;color:#475569;text-align:center;line-height:1.5;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:66px}
    .jrs-actions{display:flex;gap:10px;justify-content:center;margin-top:auto}
    .jrs-actions .btn-edit,.jrs-actions .btn-del{display:inline-flex;align-items:center;gap:6px;padding:9px 12px;border-radius:12px;font-weight:800;font-size:12px;text-decoration:none;border:1px solid transparent}
    .jrs-actions .btn-edit{background:#0a1f4f;color:#fff;border-color:#d4af37}
    .jrs-actions .btn-edit:hover{filter:brightness(1.05)}
    .jrs-actions .btn-del{background:#ef4444;color:#fff}
    .jrs-actions .btn-del:hover{filter:brightness(.95)}
    /* Header behavior like Galeri/Guru */
    .jrs-top input#jurusanSearch{ width:260px; max-width:100%; box-sizing:border-box; }
    @media (max-width: 992px){
      .jrs-top{ gap:10px !important; flex-direction:column; align-items:stretch !important; }
      .jrs-top > div{ width:100%; display:flex; flex-direction:column; gap:10px; }
      .jrs-top input{ width:100% !important; min-width:0 !important; }
      .jrs-top a.add-btn{ width:100%; justify-content:center; display:block; box-sizing:border-box; }
    }
    @media (max-width: 480px){
      .jrs-top{ margin-bottom:14px !important; }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('jurusanSearch');
      const cards = Array.from(document.querySelectorAll('.jrs-card'));
      if(!input) return;
      input.addEventListener('input', function(){
        const q = this.value.toLowerCase();
        cards.forEach(c=>{
          const text = c.innerText.toLowerCase();
          c.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
  </script>
</div>
@endsection
