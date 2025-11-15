@extends('layouts.app')
@section('title', __('admin.activity.title'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.05)">
  <div class="act-top" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:12px">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <h2 style="margin:0;font-size:18px;font-weight:800;color:#0a1f4f">{{ __('admin.activity.title') }}</h2>
      <div class="tabs" style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.aktivitas', array_filter(['q'=>$q])) }}" class="tab {{ empty($type) ? 'active' : '' }}">{{ __('admin.activity.all') }}</a>
        <a href="{{ route('admin.aktivitas', array_filter(['type'=>'comment','q'=>$q])) }}" class="tab {{ ($type ?? null) === 'comment' ? 'active' : '' }}">{{ __('admin.activity.comments') }} ({{ $countComment ?? ($items->total() ?? 0) }})</a>
        <a href="{{ route('admin.aktivitas', array_filter(['type'=>'like','q'=>$q])) }}" class="tab {{ ($type ?? null) === 'like' ? 'active' : '' }}">{{ __('admin.activity.likes') }} ({{ $countLike ?? ($items->total() ?? 0) }})</a>
      </div>
    </div>
    <form method="GET" action="{{ route('admin.aktivitas') }}" class="act-search" style="position:relative">
      @if(!empty($type))<input type="hidden" name="type" value="{{ $type }}">@endif
      <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="{{ __('admin.activity.search_placeholder') }}" style="padding:10px 12px 10px 36px;border:1px solid #e5e7eb;border-radius:10px;outline:none;">
      <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
    </form>
  </div>
  <style>
    .tab{border:1px solid #e5e7eb;border-radius:999px;padding:6px 12px;font-weight:700;color:#0a1f4f;background:#fff}
    .tab.active{background:#0a1f4f;color:#fff;border-color:#0a1f4f}
    /* Header responsive */
    .act-top input[name="q"]{ width:260px; max-width:100%; box-sizing:border-box; }
    @media (max-width: 992px){
      .act-top{ gap:10px; flex-direction:column; align-items:stretch; }
      .act-top .tabs{ order:2; }
      .act-top .act-search{ order:3; width:100%; }
      .act-top input[name="q"]{ width:100% !important; min-width:0 !important; }
    }
  </style>

  @if(isset($items) && ($items->total() ?? 0) === 0)
    <div style="padding:12px;color:#64748b;border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc">
      {{ __('admin.activity.no_activity') }}
    </div>
  @else
  <style>
    .act-list{display:flex;flex-direction:column;gap:12px;position:relative}
    .act-item{display:flex;gap:14px;align-items:center;border:1px solid #eef0f4;border-radius:16px;padding:12px 14px;background:linear-gradient(180deg,#ffffff, #fbfdff);box-shadow:0 10px 22px rgba(2,6,23,.06);transition:.2s; position:relative}
    .act-item:hover{transform:translateY(-2px); box-shadow:0 16px 30px rgba(2,6,23,.12)}
    .act-thumb{width:72px;height:54px;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;flex:0 0 72px}
    .act-thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .act-body{display:flex;flex-direction:column;gap:4px;min-width:0}
    .act-title{font-weight:900;color:#0a1f4f;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;letter-spacing:.2px}
    .act-meta{color:#64748b;font-size:.9rem;display:flex;gap:10px;flex-wrap:wrap;min-width:0}
    .act-meta > *{min-width:0}
    .act-cmt{ color:#475569; flex:1 1 auto; min-width:0; overflow-wrap:break-word; word-break:break-word; white-space:normal; display:block; }
    .act-right{margin-left:auto;display:flex;align-items:center;gap:10px;white-space:nowrap}
    .badge-like{background:#fff1f2;color:#e11d48;border:1px solid #fecdd3;padding:6px 12px;border-radius:999px;font-weight:900;font-size:12px;letter-spacing:.3px}
    .badge-comment{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:6px 12px;border-radius:999px;font-weight:900;font-size:12px;letter-spacing:.3px}
    .act-view{border:none;background:#004aad;color:#fff;padding:8px 12px;border-radius:10px;font-weight:800;cursor:pointer;}
    .act-view:hover{background:#003a8e}
    .timeline-dot{position:absolute;left:-10px;top:50%;transform:translateY(-50%);width:12px;height:12px;border-radius:50%;background:#d4af37;box-shadow:0 0 0 4px rgba(212,175,55,.18)}
    @media (max-width: 768px){
      .act-item{ align-items:flex-start; flex-wrap:wrap; }
      .act-right{ margin-left:0; width:100%; order:3; display:flex; justify-content:flex-end; }
      .act-title{ white-space:normal; }
      .act-meta{ font-size:.85rem; align-items:flex-start; }
      /* show full comment on mobile as well */
      .act-cmt{ flex: 1 0 100%; width:100%; }
    }
  </style>
  @if(isset($type) && $type)
  <div class="act-list">
    @foreach($items as $it)
      <div class="act-item">
        <span class="timeline-dot"></span>
        <div class="act-thumb">
          @if(!empty($it->gallery?->image))
            <img src="{{ asset('storage/'.$it->gallery->image) }}" alt="thumb">
          @else
            <img src="https://via.placeholder.com/128x96?text=No+Image" alt="thumb">
          @endif
        </div>
        <div class="act-body">
          <div class="act-title">{{ $it->gallery->title ?? __('admin.activity.untitled') }}</div>
          <div class="act-meta">
            <span>{{ $it->actor->name ?? 'User' }}</span>
            <span>•</span>
            <span>{{ $it->created_at->format('d/m/Y H:i') }}</span>
            @if($it->type==='comment' && $it->comment?->text)
              <span>•</span>
              <span class="act-cmt">"{{ $it->comment->text }}"</span>
            @endif
          </div>
        </div>
        <div class="act-right">
          @if($it->type==='like')
            <span class="badge-like">{{ __('admin.activity.badge_like') }}</span>
          @else
            <span class="badge-comment">{{ __('admin.activity.badge_comment') }}</span>
            @if(!empty($it->comment?->id))
              <form action="{{ route('admin.comment.destroy', $it->comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="act-view" style="background:#dc2626">Hapus</button>
              </form>
            @endif
          @endif
        </div>
      </div>
    @endforeach
  </div>
  <div style="margin-top:12px">{{ $items->links() }}</div>
  @else
    <h3 style="margin:6px 2px 8px;color:#0a1f4f">{{ __('admin.activity.comments') }} ({{ ($countComment ?? 0) }})</h3>
    <div class="act-list">
      @forelse(($comments ?? collect()) as $it)
      <div class="act-item">
        <span class="timeline-dot"></span>
        <div class="act-thumb">
          @if(!empty($it->gallery?->image))
            <img src="{{ asset('storage/'.$it->gallery->image) }}" alt="thumb">
          @else
            <img src="https://via.placeholder.com/128x96?text=No+Image" alt="thumb">
          @endif
        </div>
        <div class="act-body">
          <div class="act-title">{{ $it->gallery->title ?? 'Tanpa Judul' }}</div>
          <div class="act-meta">
            <span>{{ $it->actor->name ?? 'User' }}</span>
            <span>•</span>
            <span>{{ $it->created_at->format('d/m/Y H:i') }}</span>
            @if($it->comment?->text)
              <span>•</span>
              <span class="act-cmt">"{{ $it->comment->text }}"</span>
            @endif
          </div>
        </div>
        <div class="act-right">
          <span class="badge-comment">{{ __('admin.activity.badge_comment') }}</span>
          @if(!empty($it->comment?->id))
            <form action="{{ route('admin.comment.destroy', $it->comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="act-view" style="background:#dc2626">Hapus</button>
            </form>
          @endif
        </div>
      </div>
      @empty
      <div style="padding:12px;color:#94a3b8">{{ __('admin.activity.no_comments') }}</div>
      @endforelse
    </div>
    <div style="margin-top:10px">{{ ($comments ?? collect())->links() }}</div>

    <h3 style="margin:18px 2px 8px;color:#0a1f4f">{{ __('admin.activity.likes') }} ({{ ($countLike ?? 0) }})</h3>
    <div class="act-list">
      @forelse(($likes ?? collect()) as $it)
      <div class="act-item">
        <span class="timeline-dot"></span>
        <div class="act-thumb">
          @if(!empty($it->gallery?->image))
            <img src="{{ asset('storage/'.$it->gallery->image) }}" alt="thumb">
          @else
            <img src="https://via.placeholder.com/128x96?text=No+Image" alt="thumb">
          @endif
        </div>
        <div class="act-body">
          <div class="act-title">{{ $it->gallery->title ?? 'Tanpa Judul' }}</div>
          <div class="act-meta">
            <span>{{ $it->actor->name ?? 'User' }}</span>
            <span>•</span>
            <span>{{ $it->created_at->format('d/m/Y H:i') }}</span>
          </div>
        </div>
        <div class="act-right"><span class="badge-like">{{ __('admin.activity.badge_like') }}</span></div>
      </div>
      @empty
      <div style="padding:12px;color:#94a3b8">{{ __('admin.activity.no_likes') }}</div>
      @endforelse
    </div>
    <div style="margin-top:10px">{{ ($likes ?? collect())->links() }}</div>
  @endif
  @endif
</div>
@endsection
