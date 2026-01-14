@extends('layouts.app')
@section('back_url', route('pembayaran.show'))
@section('page_title', 'Struk Pembayaran')

@section('content')
@php
  $cart = $cart ?? session('cart', []);
  $dateText = now()->format('d M Y, H:i');

  // meja utama dari session (yang kamu simpan di TableController)
  $tableNo = $tableNo ?? session('table_no');

  // fallback kalau sewaktu-waktu masih ada session('meja') = "MEJA 7"
  if (!$tableNo && session('meja')) {
      if (preg_match('/\d+/', session('meja'), $m)) $tableNo = (int)$m[0];
  }

  // orderCode fallback
  $orderCode = $orderCode ?? session('order_code', '#SJ-?????');

  // barcode text fallback
  $barcodeText = $barcodeText ?? session('cash_barcode', 'SJ-XXXXX-X-CASH');
@endphp

<div class="cash-page">

  {{-- STATUS --}}
  <div class="cash-status">
    <div class="cash-status-icon">📄</div>
    <div class="cash-status-title">MENUNGGU KONFIRMASI KASIR</div>
    <div class="cash-status-sub">
      Segera menuju ke kasir untuk menyelesaikan transaksi Anda secara tunai.
    </div>
  </div>

  {{-- RECEIPT --}}
  <div class="cash-receipt">

    <div class="cash-receipt-head">
      <div class="cash-receipt-brand">SIJEUNI RESTAURANT</div>
      <div class="cash-receipt-loc">GRAND INDONESIA, JAKARTA</div>
    </div>

    <div class="cash-receipt-meta">
      <div class="meta-row">
        <div class="meta-key">ORDER ID</div>
        <div class="meta-val">{{ $orderCode }}</div>
      </div>
      <div class="meta-row">
        <div class="meta-key">TABLE</div>
        <div class="meta-val">MEJA {{ $tableNo ?? '-' }}</div>
      </div>
      <div class="meta-row">
        <div class="meta-key">DATE</div>
        <div class="meta-val">{{ $dateText }}</div>
      </div>
    </div>

    {{-- ITEMS --}}
    <div class="cash-items">
      @foreach($cart as $row)
        @php
          $name  = $row['name'] ?? '-';
          $qty   = (int)($row['qty'] ?? 0);
          $price = (int)($row['price'] ?? 0);
          $line  = $qty * $price;
        @endphp

        <div class="cash-item">
          <div>
            <div class="cash-item-name">{{ $name }}</div>
            <div class="cash-item-qty">Qty: {{ $qty }}</div>
          </div>
          <div class="cash-item-price">Rp {{ number_format($line,0,',','.') }}</div>
        </div>
      @endforeach
    </div>

    {{-- TOTALS --}}
    <div class="cash-totals">
      <div class="tot-row">
        <div class="tot-key">Subtotal</div>
        <div class="tot-val">Rp {{ number_format($subtotal ?? 0,0,',','.') }}</div>
      </div>
      <div class="tot-row">
        <div class="tot-key">Pajak (10%)</div>
        <div class="tot-val">Rp {{ number_format($tax ?? 0,0,',','.') }}</div>
      </div>

      <div class="tot-row tot-grand">
        <div class="tot-key">TOTAL</div>
        <div class="tot-val">Rp {{ number_format($total ?? 0,0,',','.') }}</div>
      </div>
    </div>

     {{-- BARCODE --}}
    <div class="cash-barcode">
      <div class="barcode-lines"></div>
      <div class="barcode-text">{{ $barcodeText }}</div>
    </div>

    {{-- BUTTON --}}
    <div class="cash-finish">
      <form method="POST" action="{{ route('pembayaran.cash.done') }}">
        @csrf
        <button type="submit" class="cash-finish-btn">Saya sudah bayar ke kasir</button>
      </form>
    </div>

  </div>

  <div class="cash-note">
    <span>Silakan tunjukkan struk ini ke kasir untuk melakukan pembayaran.</span>
  </div>

</div>
@endsection