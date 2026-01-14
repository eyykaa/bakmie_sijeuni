@extends('layouts.app')

@section('back_url', route('home')) 
@section('page_title', 'Pilih Nomor Meja')

@section('content')
<div class="table-page">
  <div class="table-wrap">
    <h1 class="table-title">Pilih Nomor Meja</h1>
    <p class="table-sub">Klik nomor meja untuk melanjutkan ke menu</p>

    <form method="POST" action="{{ route('tables.store') }}" id="tableForm">
      @csrf

      {{-- simpan meja terpilih ke session --}}
      <input type="hidden" name="table_no" id="tableInput" value="{{ old('table_no', $selected ?? 1) }}">

      <div class="table-grid" id="tableGrid">
        @foreach(($tables ?? []) as $t)
          <button type="button"
            class="table-item {{ (int)($selected ?? 1) === (int)$t ? 'active' : '' }}"
            data-table="{{ $t }}">
            {{ $t }}
          </button>
        @endforeach
      </div>

      <div class="table-picked">
        Meja Terpilih: <b id="pickedText">{{ old('table_no', $selected ?? 1) }}</b>
      </div>

      <div class="table-actions">
        <button type="submit" class="table-btn">
          Lanjut ke Menu <span class="ms-2">→</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const grid = document.getElementById('tableGrid');
  const input = document.getElementById('tableInput');
  const picked = document.getElementById('pickedText');

  if (grid) {
    grid.addEventListener('click', (e) => {
      const btn = e.target.closest('.table-item');
      if (!btn) return;

      // set active
      grid.querySelectorAll('.table-item').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // set value
      const val = btn.getAttribute('data-table');
      input.value = val;
      picked.textContent = val;
    });
  }
</script>
@endsection