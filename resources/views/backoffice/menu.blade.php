@extends('layouts.backoffice')

@section('content')
<div class="bo-page">
  <div class="bo-head">
    <div>
      <h1 class="bo-title">Manajemen Menu</h1>
      <div class="bo-subtitle">Aktifkan / Nonaktifkan menu.</div>
    </div>
  </div>

  <div class="bo-table-grid">
    @foreach($menus as $menu)
     <div class="bo-table-card">
  <img
    class="bo-menu-img"
    src="{{ $menu->image ? asset('images/' . $menu->image) : asset('images/default.jpg') }}"
    alt="{{ $menu->name }}"
  >

  <div class="bo-menu-name">{{ $menu->name }}</div>
  <div class="bo-menu-price">
    Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}
  </div>

  <div class="bo-menu-bottom">
    <span class="bo-table-status {{ ($menu->is_active ?? 0) ? 'on' : 'off' }}">
      {{ ($menu->is_active ?? 0) ? 'AKTIF' : 'NONAKTIF' }}
    </span>

    <form method="POST" action="{{ route('backoffice.menu.toggle', $menu->id) }}">
      @csrf
      <button class="bo-switch {{ ($menu->is_active ?? 0) ? 'on' : 'off' }}" type="submit">
        <span></span>
      </button>
    </form>
  </div>
</div>
    @endforeach
  </div>
</div>
@endsection