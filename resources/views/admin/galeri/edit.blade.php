@extends('layouts.app')
@section('title', __('admin.gallery.edit_photo'))

@section('content')
<div class="card" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.05);overflow:hidden">
  <h2 style="margin:0 0 12px;font-size:18px;font-weight:800">{{ __('admin.gallery.edit_photo') }}</h2>

  @if ($errors->any())
    <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:10px;margin-bottom:10px">{{ $errors->first() }}</div>
  @endif

  <form action="{{ route('admin.galeri.update', $item->id) }}" method="POST" enctype="multipart/form-data" style="display:grid;gap:12px;max-width:640px;width:100%">
    @csrf @method('PUT')
    <div>
      <label>{{ __('admin.gallery.form.title') }}</label>
      <input type="text" name="title" value="{{ old('title', $item->title) }}" required style="width:100%;max-width:100%;box-sizing:border-box;padding:10px;border:1px solid #d1d5db;border-radius:10px">
    </div>
    <div>
      <label>{{ __('admin.gallery.form.description') }}</label>
      <textarea name="description" rows="3" style="width:100%;max-width:100%;box-sizing:border-box;padding:10px;border:1px solid #d1d5db;border-radius:10px">{{ old('description', $item->description) }}</textarea>
    </div>
    <div>
      <label>{{ __('admin.gallery.form.category') }}</label>
      <select name="category_id" required style="width:100%;max-width:100%;box-sizing:border-box;padding:10px;border:1px solid #d1d5db;border-radius:10px">
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ old('category_id', $item->category_id)==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label>{{ __('admin.gallery.form.current_image') }}</label>
      <div><img src="{{ asset('storage/'.$item->image) }}" alt="img" style="width:160px;height:120px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb"></div>
    </div>
    <div>
      <label>{{ __('admin.gallery.form.change_image_optional') }}</label>
      <input type="file" name="image" accept="image/*" style="max-width:100%">
    </div>
    <div class="actions">
      <a href="{{ route('admin.galeri.index') }}" class="btn ghost">{{ __('admin.common.cancel') }}</a>
      <button type="submit" class="btn primary">{{ __('admin.common.save') }}</button>
    </div>
  </form>
</div>

<style>
.actions{display:flex;gap:10px;margin-top:4px;width:100%;box-sizing:border-box}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border-radius:12px;font-weight:800;border:1px solid transparent;cursor:pointer;max-width:100%;box-sizing:border-box;-webkit-appearance:none;appearance:none}
.btn.primary{background:#0a1f4f;color:#fff;border:2px solid #d4af37}
.btn.ghost{background:#fff;border:1px solid #e5e7eb;color:#0f172a;text-decoration:none}
.btn.primary{background:#0a1f4f!important;color:#fff;border:2px solid #d4af37;padding:10px 16px;border-radius:12px;font-weight:800;-webkit-appearance:none;appearance:none}
.btn.primary:hover{filter:brightness(1.05)}
@media (max-width: 768px){
  .actions{width:100%; flex-direction:column; align-items:stretch}
  .btn{width:100%; max-width:none; margin:0}
  .actions .btn.primary{ order:-1; }
}
</style>
@endsection
