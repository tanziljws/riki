@extends('layouts.app')
@section('title', __('admin.gallery.title'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.05)">
  <div class="galeri-top" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;align-items:center;margin-bottom:12px">
    <h2 style="margin:0;font-size:18px;font-weight:800;color:#0a1f4f">{{ __('admin.gallery.title') }}</h2>
    <div style="display:flex;gap:10px;align-items:center">
      <div class="search-wrap" style="position:relative">
        <input id="galeriSearch" type="text" placeholder="{{ __('admin.common.search_placeholder') }}" style="padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
      </div>
      <a class="add-btn" href="{{ route('admin.galeri.create') }}" style="display:inline-flex;align-items:center;gap:8px;background:#0a1f4f;color:#fff;padding:10px 12px;border-radius:10px;border:2px solid #d4af37;text-decoration:none;font-weight:700">
        <i class="fa-solid fa-plus"></i> {{ __('admin.gallery.add_photo') }}
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:10px 12px;border-radius:10px;margin-bottom:10px">{{ session('success') }}</div>
  @endif

  <div class="tbl-wrap" style="overflow:auto;border:1px solid #eef0f4;border-radius:12px">
    <table id="galeriTable" style="width:100%;border-collapse:separate;border-spacing:0">
      <thead>
        <tr style="background:#0a1f4f;color:#fff">
          <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.gallery.table.image') }}</th>
          <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.gallery.table.title') }}</th>
          <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.gallery.table.category') }}</th>
          <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.gallery.table.description') }}</th>
          <th style="padding:12px 14px;text-align:left;position:sticky;top:0;width:140px">{{ __('admin.gallery.table.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $it)
          <tr class="gl-row">
            <td style="padding:12px 14px;border-top:1px solid #eef0f4">
              <div style="width:76px;height:56px;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;box-shadow:0 2px 8px rgba(2,6,23,.06)" data-gallery-id="{{ $it->id }}" data-image-path="{{ $it->image ?? 'NULL' }}">
                @if(!empty($it->image) && trim($it->image) !== '' && $it->image !== '0')
                  @php
                    $imageUrl = str_replace('/storage/', '/files/', asset('storage/'.$it->image));
                  @endphp
                  <img src="{{ $imageUrl }}" 
                       alt="img" 
                       style="width:100%;height:100%;object-fit:cover" 
                       data-debug-path="{{ $it->image }}"
                       data-debug-url="{{ $imageUrl }}"
                       onerror="console.error('Image failed to load for gallery #{{ $it->id }}:', {path: '{{ $it->image }}', url: this.src}); this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'76\' height=\'56\'%3E%3Crect fill=\'%23e5e7eb\' width=\'76\' height=\'56\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2394a3b8\' font-size=\'12\'%3ENo Image%3C/text%3E%3C/svg%3E'; this.onerror=null;"
                       onload="console.log('Image loaded for gallery #{{ $it->id }}:', {path: '{{ $it->image }}', url: this.src});">
                @else
                  <div style="width:100%;height:100%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:10px" data-debug-no-image="true" data-gallery-id="{{ $it->id }}" data-image-path="{{ $it->image ?? 'NULL' }}">No Image</div>
                @endif
              </div>
            </td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#0f172a;font-weight:700">{{ $it->title }}</td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4">
              @php($cat = optional($it->category)->name)
              <span style="display:inline-block;background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px">{{ $cat ?? '-' }}</span>
            </td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#475569">{{ Str::limit($it->description, 100) }}</td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4">
              <div style="display:flex;gap:8px">
                <a href="{{ route('admin.galeri.edit', $it->id) }}" title="{{ __('admin.common.edit') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:#0a1f4f;color:#fff;border:2px solid #d4af37"><i class="fa-solid fa-pen"></i></a>
                <form action="{{ route('admin.galeri.destroy', $it->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.gallery.confirm_delete') }}')">
                  @csrf @method('DELETE')
                  <button type="submit" title="{{ __('admin.common.delete') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:#ef4444;color:#fff;border:none"><i class="fa-solid fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" style="padding:14px;color:#6b7280">{{ __('admin.common.no_data') }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <style>
    #galeriTable tbody tr:hover{background:#f8fafc}
    #galeriTable thead th:first-child{border-top-left-radius:12px}
    #galeriTable thead th:last-child{border-top-right-radius:12px}
    #galeriTable thead th{ position:sticky; top:0; z-index:3; background:#0a1f4f; }
    .galeri-top{ position:relative; z-index:2; margin-bottom:12px; }
    .tbl-wrap{ margin-top:8px; position:relative; z-index:1; }
    /* Layout tweaks */
    .galeri-top > div{ display:flex; gap:10px; align-items:center; }
    .galeri-top input#galeriSearch{ width:280px; max-width:100%; box-sizing:border-box; }
    /* Mobile: gunakan horizontal scroll agar struktur tabel tetap utuh */
    @media (max-width: 992px){
      #galeriTable{ min-width: 760px; }
      #galeriTable td{ padding:10px 12px !important; }
      .galeri-top{ gap:10px !important; flex-direction:column; align-items:stretch !important; }
      .galeri-top > div{ flex:1 1 100%; width:100%; display:flex; flex-direction:column; align-items:stretch; gap:10px; }
      .galeri-top input{ min-width:0 !important; width:100% !important; }
      .galeri-top a.add-btn{ width:100%; justify-content:center; display:block; box-sizing:border-box; margin-top:2px; }
      .tbl-wrap{ margin-top:10px; }
    }
    /* Stack earlier to avoid overlap */
    @media (max-width: 768px){
      .galeri-top{ flex-direction:column; align-items:stretch !important; gap:10px !important; }
      .galeri-top > div{ width:100%; display:flex; flex-direction:column; align-items:stretch; gap:10px; }
      .galeri-top .search-wrap{ width:100%; }
      .galeri-top input{ width:100% !important; max-width:520px; min-width:0 !important; margin:0 auto; }
      .galeri-top a.add-btn{ width:100%; justify-content:center; margin-top:2px; }
    }
    @media (max-width: 480px){
      #galeriTable{ min-width: 700px; }
      .galeri-top{ margin-bottom:14px !important; }
      .tbl-wrap{ margin-top:12px; }
    }
    /* Smooth horizontal scroll on mobile */
    .card > div[style*="overflow:auto"]{ -webkit-overflow-scrolling: touch; }
    .galeri-pager-wrap{ display:flex; justify-content:center; margin-top:8px; }
    .galeri-pager{
      display:inline-flex; align-items:center; gap:8px;
      padding:6px 14px; border-radius:999px; background:#f9fafb;
      box-shadow:0 3px 10px rgba(15,23,42,0.08);
      border:1px solid #e5e7eb; color:#0f172a; font-size:13px;
    }
    .galeri-pager-btn{
      display:inline-flex; align-items:center; justify-content:center;
      width:30px; height:30px; border-radius:999px; border:1px solid #e5e7eb;
      background:#fff; color:#0f172a; text-decoration:none;
      box-shadow:0 2px 5px rgba(15,23,42,0.06); font-size:14px;
      cursor:pointer; touch-action:manipulation;
    }
    .galeri-pager-btn.disabled{ opacity:.4; cursor:not-allowed; box-shadow:none; background:#f3f4f6; }
    .galeri-pager-btn:not(.disabled):hover{ background:#0a1f4f; color:#fff; border-color:#d4af37; }
    .galeri-pager-info{ font-size:13px; color:#4b5563; white-space:nowrap; }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      // Debug: Log semua data gallery ke console
      console.log('=== GALLERY DEBUG INFO ===');
      @foreach($items->items() as $idx => $it)
        console.log('Gallery #{{ $it->id }}:', {
          id: {{ $it->id }},
          title: '{{ $it->title }}',
          image_path: '{{ $it->image ?? "NULL" }}',
          image_empty: {{ empty($it->image) ? 'true' : 'false' }},
          image_url: '{{ !empty($it->image) ? str_replace("/storage/", "/files/", asset("storage/".$it->image)) : "NO_URL" }}',
          category: '{{ optional($it->category)->name ?? "NULL" }}'
        });
      @endforeach
      
      // Debug: Log semua image elements
      const images = document.querySelectorAll('#galeriTable tbody img');
      console.log('Total images found:', images.length);
      images.forEach((img, idx) => {
        console.log(`Image ${idx + 1}:`, {
          src: img.src,
          alt: img.alt,
          onerror: img.onerror ? 'has handler' : 'no handler',
          naturalWidth: img.naturalWidth,
          naturalHeight: img.naturalHeight,
          complete: img.complete
        });
        
        // Monitor image load errors
        img.addEventListener('error', function(e) {
          console.error(`Image ${idx + 1} failed to load:`, {
            src: this.src,
            attempted_url: this.src,
            error: e
          });
        });
        
        // Monitor image load success
        img.addEventListener('load', function(e) {
          console.log(`Image ${idx + 1} loaded successfully:`, {
            src: this.src,
            dimensions: `${this.naturalWidth}x${this.naturalHeight}`
          });
        });
      });
      
      // Debug: Check for "No Image" placeholders
      const noImageDivs = document.querySelectorAll('#galeriTable tbody div[style*="No Image"]');
      console.log('No Image placeholders found:', noImageDivs.length);
      noImageDivs.forEach((div, idx) => {
        const row = div.closest('tr');
        const title = row ? row.querySelector('td:nth-child(2)')?.textContent?.trim() : 'unknown';
        console.log(`No Image placeholder ${idx + 1} in row:`, {
          title: title,
          parent_row: row
        });
      });
      
      const input = document.getElementById('galeriSearch');
      const rows = Array.from(document.querySelectorAll('#galeriTable tbody .gl-row'));
      if(!input) return;
      input.addEventListener('input', function(){
        const q = this.value.toLowerCase();
        rows.forEach(r=>{
          const text = r.innerText.toLowerCase();
          r.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
  </script>

  <div style="margin-top:12px">
    <div class="galeri-pager-wrap">
      <div class="galeri-pager">
        @if ($items->onFirstPage())
          <button type="button" class="galeri-pager-btn disabled" disabled>&lt;</button>
        @else
          <button type="button" class="galeri-pager-btn" onclick="window.location='{{ $items->previousPageUrl() }}'">&lt;</button>
        @endif

        <span class="galeri-pager-info">Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}</span>

        @if ($items->hasMorePages())
          <button type="button" class="galeri-pager-btn" onclick="window.location='{{ $items->nextPageUrl() }}'">&gt;</button>
        @else
          <button type="button" class="galeri-pager-btn disabled" disabled>&gt;</button>
        @endif
      </div>
    </div>
    @if ($items->total() > 0)
      <div style="text-align:center;margin-top:6px;font-size:12px;color:#6b7280;">
        Menampilkan {{ $items->firstItem() }}&ndash;{{ $items->lastItem() }} dari {{ $items->total() }} foto
      </div>
    @endif
  </div>
</div>
@endsection
