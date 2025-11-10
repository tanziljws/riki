@extends('layouts.app')
@section('title', __('admin.teachers.title'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.05)">
  <div class="guru-top" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;align-items:center;margin-bottom:12px">
    <h2 style="margin:0;font-size:18px;font-weight:800;color:#0a1f4f">{{ __('admin.teachers.title') }}</h2>
    <div style="display:flex;gap:10px;align-items:center">
      <div class="search-wrap" style="position:relative">
        <input id="guruSearch" type="text" placeholder="{{ __('admin.teachers.search_placeholder') }}" style="padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
      </div>
      <a class="add-btn" href="{{ route('admin.guru.create') }}" style="display:inline-flex;align-items:center;gap:8px;background:#0a1f4f;color:#fff;padding:10px 12px;border-radius:10px;border:2px solid #d4af37;text-decoration:none;font-weight:700">
        <i class="fa-solid fa-plus"></i> {{ __('admin.teachers.add') }}
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:10px 12px;border-radius:10px;margin-bottom:10px">{{ session('success') }}</div>
  @endif

  <div class="tbl-wrap" style="overflow:auto;border:1px solid #eef0f4;border-radius:12px">
    <table id="guruTable" style="width:100%;border-collapse:separate;border-spacing:0">
      <thead>
        <tr style="background:#0a1f4f;color:#fff">
          <th style="text-align:left;padding:12px 14px;font-weight:800;position:sticky;top:0">{{ __('admin.teachers.table.no') }}</th>
          <th style="text-align:left;padding:12px 14px;font-weight:800;position:sticky;top:0">{{ __('admin.teachers.table.name') }}</th>
          <th style="text-align:left;padding:12px 14px;font-weight:800;position:sticky;top:0">{{ __('admin.teachers.table.position') }}</th>
          <th style="text-align:left;padding:12px 14px;font-weight:800;position:sticky;top:0">{{ __('admin.teachers.table.created') }}</th>
          <th style="text-align:left;padding:12px 14px;font-weight:800;position:sticky;top:0">{{ __('admin.teachers.table.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($guru as $i => $g)
          <tr class="row-item">
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#0f172a">{{ $i + 1 }}</td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#0f172a;display:flex;align-items:center;gap:10px">
              <span style="width:34px;height:34px;border-radius:10px;background:#0a1f4f;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800">{{ strtoupper(substr($g->nama,0,1)) }}</span>
              <span style="font-weight:800">{{ $g->nama }}</span>
            </td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#475569">{{ $g->jabatan ?? '-' }}</td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#475569">{{ optional($g->created_at)->format('d M Y') }}</td>
            <td style="padding:12px 14px;border-top:1px solid #eef0f4">
              <div style="display:flex;gap:8px">
                <a href="{{ route('admin.guru.edit',$g->id) }}" title="{{ __('admin.common.edit') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:#0a1f4f;color:#fff;border:2px solid #d4af37;font-weight:700;font-size:12px"><i class="fa-solid fa-pen"></i> {{ __('admin.common.edit') }}</a>
                <form action="{{ route('admin.guru.destroy',$g->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.teachers.confirm_delete') }}')">
                  @csrf @method('DELETE')
                  <button type="submit" title="{{ __('admin.common.delete') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:10px;background:#ef4444;color:#fff;border:none;font-weight:700;font-size:12px"><i class="fa-solid fa-trash"></i> {{ __('admin.common.delete') }}</button>
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
    #guruTable tbody tr:hover{background:#f8fafc}
    #guruTable thead th:first-child{border-top-left-radius:12px}
    #guruTable thead th:last-child{border-top-right-radius:12px}
    #guruTable thead th{ position:sticky; top:0; z-index:3; background:#0a1f4f; }
    .guru-top{ position:relative; z-index:2; margin-bottom:12px; }
    .tbl-wrap{ margin-top:8px; position:relative; z-index:1; }
    .guru-top input#guruSearch{ width:260px; max-width:100%; box-sizing:border-box; }
    /* Mobile horizontal scroll */
    @media (max-width: 992px){
      #guruTable{ min-width: 760px; }
      #guruTable td{ padding:10px 12px !important; }
      .guru-top{ gap:10px !important; flex-direction:column; align-items:stretch !important; }
      .guru-top > div{ flex:1 1 100%; width:100%; display:flex; flex-direction:column; align-items:stretch; gap:10px; }
      .guru-top input{ min-width:0 !important; width:100% !important; }
      .guru-top a.add-btn{ width:100%; justify-content:center; display:block; box-sizing:border-box; }
      .tbl-wrap{ margin-top:10px; }
    }
    @media (max-width: 768px){
      .guru-top{ flex-direction:column; align-items:stretch !important; gap:10px !important; }
      .guru-top > div{ width:100%; display:flex; flex-direction:column; align-items:stretch; gap:10px; }
      .guru-top input{ width:100% !important; min-width:0 !important; }
      .guru-top a.add-btn{ width:100%; justify-content:center; }
    }
    @media (max-width: 480px){
      #guruTable{ min-width: 700px; }
      .guru-top{ margin-bottom:14px !important; }
      .tbl-wrap{ margin-top:12px; }
    }
    .card > .tbl-wrap{ -webkit-overflow-scrolling: touch; }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('guruSearch');
      const rows = Array.from(document.querySelectorAll('#guruTable tbody .row-item'));
      if(!input) return;
      input.addEventListener('input', function(){
        const q = this.value.toLowerCase();
        rows.forEach(r => {
          const text = r.innerText.toLowerCase();
          r.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
  </script>
</div>
@endsection
