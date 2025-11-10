@extends('layouts.app')

@section('title', __('admin.accounts.title'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.06);">
  <div style="padding:16px 18px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
    <h2 style="margin:0;font-size:1.25rem;color:#0a1f4f;">{{ __('admin.accounts.title') }}</h2>
    <div style="display:flex;gap:10px;align-items:center">
      @if (session('status'))
        <div style="background:#ecfdf5;color:#065f46;padding:8px 12px;border-radius:10px;border:1px solid #a7f3d0;">{{ session('status') }}</div>
      @endif
      <div style="position:relative">
        <input id="acctSearch" type="text" placeholder="{{ __('admin.accounts.search_placeholder') }}" style="padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;min-width:240px">
        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
      </div>
    </div>
  </div>
  <div style="padding:0 16px 16px 16px;overflow:auto;">
    <div style="overflow:auto;border:1px solid #eef0f4;border-radius:12px;margin-top:16px">
      <table id="acctTable" style="width:100%;border-collapse:separate;border-spacing:0">
        <thead>
          <tr style="background:#0a1f4f;color:#fff">
            <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.accounts.table.name') }}</th>
            <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.accounts.table.email') }}</th>
            <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.accounts.table.role') }}</th>
            <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.accounts.table.status') }}</th>
            <th style="padding:12px 14px;text-align:left;position:sticky;top:0">{{ __('admin.accounts.table.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
            <tr class="acct-row">
              <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#0f172a;font-weight:700">{{ $u->name }}</td>
              <td style="padding:12px 14px;border-top:1px solid #eef0f4;color:#475569">{{ $u->email }}</td>
              <td style="padding:12px 14px;border-top:1px solid #eef0f4">
                @php($role = $u->role ?? '-')
                <span style="display:inline-block;background:#f1f5f9;color:#0f172a;border:1px solid #e2e8f0;padding:6px 10px;border-radius:999px;font-weight:800;font-size:12px">{{ $role }}</span>
              </td>
              <td style="padding:12px 14px;border-top:1px solid #eef0f4">
                @if($u->is_active)
                  <span style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;padding:6px 10px;border-radius:9999px;font-weight:800;font-size:12px;">{{ __('admin.accounts.active') }}</span>
                @else
                  <span style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:6px 10px;border-radius:9999px;font-weight:800;font-size:12px;">{{ __('admin.accounts.inactive') }}</span>
                @endif
              </td>
              <td style="padding:12px 14px;border-top:1px solid #eef0f4;white-space:nowrap;">
                @php($isMaulana = strtolower($u->name) === 'maulana')
                @if($isMaulana)
                  <span style="background:#f1f5f9;color:#0f172a;border:1px solid #e2e8f0;padding:6px 10px;border-radius:999px;font-weight:800;font-size:12px">{{ __('admin.accounts.locked') }}</span>
                @else
                  <form method="POST" action="{{ route('admin.management.toggle', $u) }}" onsubmit="return confirm('{{ $u->is_active ? __('admin.accounts.confirm_toggle_off') : __('admin.accounts.confirm_toggle_on') }}');" style="display:inline-block;margin-right:6px;">
                    @csrf
                    <button type="submit" style="border:none;background:{{ $u->is_active ? '#dc2626' : '#0ea5e9' }};color:#fff;padding:8px 12px;border-radius:10px;font-weight:800;cursor:pointer;box-shadow:0 4px 10px rgba(2,6,23,.08)">{{ $u->is_active ? __('admin.accounts.deactivate') : __('admin.accounts.activate') }}</button>
                  </form>
                  <form method="POST" action="{{ route('admin.management.delete', $u) }}" onsubmit="return confirm('{{ __('admin.accounts.confirm_delete') }}');" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="border:none;background:#6b7280;color:#fff;padding:8px 12px;border-radius:10px;font-weight:800;cursor:pointer;box-shadow:0 4px 10px rgba(2,6,23,.08)">{{ __('admin.accounts.delete') }}</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="padding:14px;color:#6b7280">{{ __('admin.accounts.empty') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <style>
      #acctTable tbody tr:hover{background:#f8fafc}
      #acctTable thead th:first-child{border-top-left-radius:12px}
      #acctTable thead th:last-child{border-top-right-radius:12px}
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function(){
        const input = document.getElementById('acctSearch');
        const rows = Array.from(document.querySelectorAll('#acctTable tbody .acct-row'));
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

    <div style="margin-top:12px;">
      {{ $users->links() }}
    </div>
  </div>
</div>
@endsection
