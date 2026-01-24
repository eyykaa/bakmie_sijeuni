@extends('layouts.backoffice')

@section('content')
<div class="bo-page" style="max-width:900px">

  <div class="bo-detail-topbar">
    <a class="bo-back" href="{{ route('backoffice.menus.index') }}">←</a>
    <div class="bo-detail-title">Edit Menu</div>
  </div>

  <div class="bo-panel" style="padding:16px">
    <form method="POST" action="{{ route('backoffice.menus.update', $menu->id) }}" class="bo-form">
      @csrf
      @method('PUT')

      <div class="bo-form-row">
        <label>Nama</label>
        <input name="name" value="{{ old('name', $menu->name) }}" required>
      </div>

      <div class="bo-form-row">
        <label>Kategori</label>
        <select name="category" required>
          @foreach(['food'=>'Food','drink'=>'Drink','dessert'=>'Dessert'] as $k=>$v)
            <option value="{{ $k }}" @selected(old('category' ,$menu->category)===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div class="bo-form-row">
        <label>Harga</label>
        <input type="number" name="price" min="0" value="{{ old('price', (int)$menu->price) }}" required>
      </div>

      <div class="bo-form-row">
        <label>Stock (boleh kosong)</label>
        <input type="number" name="stock" min="0" value="{{ old('stock', $menu->stock) }}">
      </div>

      <div class="bo-form-row">
        <label>Image URL (opsional)</label>
        <input name="image" value="{{ old('image', $menu->image) }}">
      </div>

      <button class="bo-btn bo-btn-primary" type="submit">Simpan</button>
    </form>
  </div>

</div>
@endsections