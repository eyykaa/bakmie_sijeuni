@extends('layouts.backoffice')

@section('content')
<div class="bo-page">

  {{-- HEADER --}}
  <div class="bo-orders-head">
    <h1 class="bo-orders-title">Orders Dashboard</h1>

    <a href="{{ route('home') }}" class="bo-btn bo-btn-primary">
      + Tambah Pesanan
    </a>
  </div>

  {{-- TABS --}}
  <div class="bo-tabs">
    <a class="bo-tab {{ $tab==='baru' ? 'active' : '' }}"
       href="{{ route('backoffice.orders.index', ['tab'=>'baru','q'=>$q]) }}">Baru</a>

    <a class="bo-tab {{ $tab==='diproses' ? 'active' : '' }}"
       href="{{ route('backoffice.orders.index', ['tab'=>'diproses','q'=>$q]) }}">Diproses</a>

    <a class="bo-tab {{ $tab==='selesai' ? 'active' : '' }}"
       href="{{ route('backoffice.orders.index', ['tab'=>'selesai','q'=>$q]) }}">Selesai</a>
  </div>

  {{-- SEARCH --}}
  <form class="bo-searchrow" method="GET" action="{{ route('backoffice.orders.index') }}">
    <input type="hidden" name="tab" value="{{ $tab }}">

    <div class="bo-search">
      <span class="bo-search-ic">🔎</span>
      <input class="bo-search-input" name="q" value="{{ $q }}" placeholder="Cari meja atau order ID..." />
    </div>

    <button class="bo-btn bo-btn-light" type="submit">Filters</button>
  </form>

  {{-- GRID CARDS --}}
  <div class="bo-orders-grid">
    @forelse($orders as $o)
      <div class="bo-order-card">
        <div class="bo-order-top">
          <div class="bo-order-title">Order #{{ $o->order_code }}</div>
          <div class="bo-pill">{{ strtoupper($o->status) }}</div>
        </div>

        <div class="bo-order-mid">
          <div class="bo-order-meta">
            <span class="bo-meta-ic">🍽</span>
            <span>Meja {{ $o->table_no }}</span>
          </div>

          <div class="bo-order-meta">
            <span class="bo-meta-ic">🕒</span>
            <span>{{ $o->created_at ? $o->created_at->format('H:i') : '-' }} WIB</span>
          </div>

          <div class="bo-order-meta">
            <span class="bo-meta-ic">💳</span>
            <span>{{ ucfirst($o->payment_method) }}</span>
          </div>
        </div>

        <div class="bo-order-total">
          <div class="bo-order-total-label">TOTAL TAGIHAN</div>
          <div class="bo-order-total-val">Rp {{ number_format((int)$o->total,0,',','.') }}</div>
        </div>

        {{-- Nanti kita sambung ke route detail beneran --}}
        <a href="{{ route('backoffice.orders.show', $o->id) }}" class="bo-btn bo-btn-card">
          Lihat Detail →
        </a>
      </div>
    @empty
      <div class="bo-empty">
        Belum ada data pesanan untuk tab <b>{{ $tab }}</b>.
      </div>
    @endforelse
  </div>

  {{-- PAGINATION --}}
  <div class="bo-paginate">
    {{ $orders->links() }}
  </div>

</div>
@endsection