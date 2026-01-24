@extends('layouts.backoffice')

@section('content')
<div class="bo-page">

  <div class="bo-detail-topbar">
    <a class="bo-back" href="{{ route('backoffice.orders.index', ['tab'=>$order->status]) }}">←</a>
    <div class="bo-detail-title">Detail Pesanan & Konfirmasi</div>
  </div>

  @php
    $method = strtolower((string)$order->payment_method); // transfer / cash
    $methodText = $method === 'cash' ? 'Tunai (Cash)' : 'Bank Transfer';
    $bankText = $method === 'transfer' ? ' (BCA)' : ''; // kalau kamu simpan bank lain, nanti bisa dinamis
    $payStatus = strtolower((string)$order->payment_status) === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar';
  @endphp

  <div class="bo-detail-grid">

    {{-- KIRI --}}
    <div class="bo-detail-left">

      <div class="bo-info-cards">
        <div class="bo-info-card">
          <div class="bo-info-title">{{ $payStatus }}</div>
          <div class="bo-info-sub">Status Pembayaran: {{ $methodText }}{{ $bankText }}</div>
        </div>

        <div class="bo-info-card">
          <div class="bo-info-title">Meja {{ $order->table_no }}</div>
          <div class="bo-info-sub">
            #{{ $order->order_code }} &nbsp; • &nbsp; {{ $order->created_at?->format('H:i') ?? '-' }} WIB
          </div>
        </div>
      </div>

      <div class="bo-panel">
        <div class="bo-panel-title">Rincian Menu</div>

        <div class="bo-items">
          @foreach($order->items as $it)
            <div class="bo-item-row">
              <div class="bo-item-left">
                <div class="bo-item-name">{{ $it->name }}</div>
                <div class="bo-item-sub">{{ (int)$it->qty }}x Rp {{ number_format((int)$it->price,0,',','.') }}</div>
              </div>
              <div class="bo-item-right">
                Rp {{ number_format((int)$it->subtotal,0,',','.') }}
              </div>
            </div>
          @endforeach
        </div>

        <div class="bo-sum">
          <div class="bo-sum-row">
            <div>Subtotal</div>
            <div>Rp {{ number_format((int)$order->subtotal,0,',','.') }}</div>
          </div>
          <div class="bo-sum-row">
            <div>Tax (10%)</div>
            <div>Rp {{ number_format((int)$order->tax,0,',','.') }}</div>
          </div>
          <div class="bo-sum-row bo-sum-total">
            <div>Total Bayar</div>
            <div>Rp {{ number_format((int)$order->total,0,',','.') }}</div>
          </div>
        </div>

      </div>
    </div>

    {{-- KANAN --}}
    <div class="bo-detail-right">

      <div class="bo-panel">
        <div class="bo-panel-title">Pratinjau Struk</div>

        <div class="bo-receipt-preview">
          <div class="r-head">
            <div class="r-brand">Bakmie Sijeuni</div>
            <div class="r-sub">Bali, Indonesia</div>
            <div class="r-sub">Jl. Mawar No. 123, Denpasar</div>
          </div>

          <div class="r-meta">
            <div class="r-row"><span>ORDER ID:</span><b>#{{ $order->order_code }}</b></div>
            <div class="r-row"><span>MEJA:</span><b>TABLE {{ $order->table_no }}</b></div>
            <div class="r-row"><span>METODE:</span><b>{{ $methodText }}{{ $bankText }}</b></div>
            <div class="r-row"><span>TANGGAL:</span><b>{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</b></div>
          </div>

          <div class="r-line"></div>

          <div class="r-items">
            @foreach($order->items as $it)
              <div class="r-it">
                <div>{{ (int)$it->qty }}x {{ $it->name }}</div>
                <div>Rp {{ number_format((int)$it->subtotal,0,',','.') }}</div>
              </div>
            @endforeach
          </div>

          <div class="r-line"></div>

          <div class="r-sum">
            <div class="r-it"><span>SUBTOTAL</span><span>Rp {{ number_format((int)$order->subtotal,0,',','.') }}</span></div>
            <div class="r-it"><span>PAJAK (10%)</span><span>Rp {{ number_format((int)$order->tax,0,',','.') }}</span></div>
            <div class="r-total">
              <div>TOTAL</div>
              <div>Rp {{ number_format((int)$order->total,0,',','.') }}</div>
            </div>
          </div>

          <div class="r-foot">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
        </div>

        {{-- ACTION UTAMA --}}
        @if($order->status === 'baru')
          <form method="POST" action="{{ route('backoffice.orders.verify', $order->id) }}">
            @csrf
            <button class="bo-btn bo-btn-big" type="submit">Verifikasi & Konfirmasi</button>
          </form>
        @elseif($order->status === 'diproses')
          <form method="POST" action="{{ route('backoffice.orders.done', $order->id) }}">
            @csrf
            <button class="bo-btn bo-btn-big" type="submit">Selesai</button>
          </form>
        @else
          <div class="bo-done-note">Pesanan sudah selesai.</div>
        @endif

       <div class="bo-print-actions">
  <a class="bo-btn bo-btn-outline" target="_blank"
     href="{{ route('backoffice.orders.print.receipt', $order) }}">
    🧾 Cetak Struk
  </a>

  <a class="bo-btn bo-btn-outline" target="_blank"
     href="{{ route('backoffice.orders.print.kitchen', $order) }}">
    🍳 Cetak Kitchen
  </a>
</div>
        </div>

      </div>

    </div>
  </div>
</div>
@endsection