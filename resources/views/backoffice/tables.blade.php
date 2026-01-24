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
    @forelse($tables as $t)
      <div class="bo-table-card">
        <div class="bo-table-top">
          <div>
            <div class="bo-table-name">Meja {{ $t->table_no }}</div>
            <div class="bo-table-status {{ $t->is_active ? 'on' : 'off' }}">
              {{ $t->is_active ? 'AKTIF' : 'NONAKTIF' }}
            </div>
          </div>

          <form method="POST" action="{{ route('backoffice.tables.toggle', $t->id) }}">
            @csrf
            <button class="bo-switch {{ $t->is_active ? 'on' : 'off' }}" type="submit" title="Toggle">
              <span></span>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="bo-empty">Belum ada data meja.</div>
    @endforelse
  </div>
</div>
@endsection