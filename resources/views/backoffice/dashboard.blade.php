@extends('layouts.backoffice')

@section('content')
@php
  $countBaru     = $countBaru ?? 0;
  $countDiproses = $countDiproses ?? 0;
  $countSelesai  = $countSelesai ?? 0;
  $totalSales    = $totalSales ?? 0;
  $latestOrders  = $latestOrders ?? collect();
@endphp

<div class="bo-page">

  <div class="bo-head">
    <h1 class="bo-title">Ringkasan Operasional</h1>
  </div>

  <div class="bo-cards">
    <div class="bo-card">
      <div class="bo-card-top">
        <div class="bo-card-ic">🆕</div>
        <div class="bo-card-label">Pesanan Baru</div>
      </div>
      <div class="bo-card-value">{{ $countBaru }}</div>
    </div>

    <div class="bo-card">
      <div class="bo-card-top">
        <div class="bo-card-ic">👨‍🍳</div>
        <div class="bo-card-label">Diproses</div>
      </div>
      <div class="bo-card-value">{{ $countDiproses }}</div>
    </div>

    <div class="bo-card">
      <div class="bo-card-top">
        <div class="bo-card-ic">✅</div>
        <div class="bo-card-label">Selesai</div>
      </div>
      <div class="bo-card-value">{{ $countSelesai }}</div>
    </div>
  </div>

  <div class="bo-sales">
    <div class="bo-sales-label">Total Penjualan</div>
    <div class="bo-sales-value">Rp {{ number_format((int)$totalSales, 0, ',', '.') }}</div>
  </div>

  <div class="bo-section">
    <div class="bo-section-head">
      <div class="bo-section-title">Pesanan Terakhir</div>
      <a class="bo-link" href="{{ route('backoffice.orders.index') }}">Lihat Semua →</a>
    </div>

    <div class="bo-table-wrap">
      <table class="bo-table">
        <thead>
          <tr>
            <th>Order</th>
            <th>Meja</th>
            <th>Waktu</th>
            <th>Total</th>
            <th>Status</th>
            <th>Pembayaran</th>
          </tr>
        </thead>
        <tbody>
          @forelse($latestOrders as $o)
            <tr>
              <td>#{{ $o->order_code }}</td>
              <td>Meja {{ $o->table_no }}</td>
              <td>{{ optional($o->created_at)->format('d M Y H:i') }}</td>
              <td>Rp {{ number_format((int)($o->total ?? 0), 0, ',', '.') }}</td>
              <td>{{ strtoupper($o->status ?? '-') }}</td>
              <td>{{ strtoupper($o->payment_status ?? '-') }} ({{ strtoupper($o->payment_method ?? '-') }})</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; opacity:.7; padding:18px;">
                Belum ada data pesanan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection