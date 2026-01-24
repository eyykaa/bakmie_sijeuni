<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Struk #{{ $order->order_code }}</title>

  <style>
    body{font-family:Arial, sans-serif; padding:18px;}
    .wrap{max-width:380px; margin:0 auto;}
    .center{text-align:center;}
    .muted{opacity:.75; font-size:12px;}
    .line{border-top:1px dashed #999; margin:12px 0;}
    .row{display:flex; justify-content:space-between; gap:12px; font-size:13px; margin:6px 0;}
    .bold{font-weight:900;}
    .total{display:flex; justify-content:space-between; font-weight:800; font-size:16px; margin-top:10px;}
  </style>
  
</head>
<body onload="window.print()">
  @php
    $method = strtolower((string)$order->payment_method);
    $methodText = $method === 'cash' ? 'Tunai (Cash)' : 'Bank Transfer';
    $bankText = $method === 'transfer' ? ' BCA' : '';
  @endphp

  <div class="wrap">
    <div class="center">
      <div class="bold">BAKMIE SIJEUNI</div>
      <div class="muted">Bali, Indonesia</div>
      <div class="muted">Jl. Mawar No. 123, Denpasar</div>
    </div>

    <div class="line"></div>

    <div class="row"><span>ORDER ID</span><span class="bold">#{{ $order->order_code }}</span></div>
    <div class="row"><span>MEJA</span><span class="bold">TABLE {{ $order->table_no }}</span></div>
    <div class="row"><span>METODE</span><span class="bold">{{ $methodText }}{{ $bankText }}</span></div>
    <div class="row"><span>TANGGAL</span><span class="bold">{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</span></div>

    <div class="line"></div>

    @foreach($order->items as $it)
      <div class="row">
        <span>{{ (int)$it->qty }}x {{ $it->name }}</span>
        <span>Rp {{ number_format((int)$it->subtotal,0,',','.') }}</span>
      </div>
    @endforeach

    <div class="line"></div>

    <div class="row"><span>SUBTOTAL</span><span>Rp {{ number_format((int)$order->subtotal,0,',','.') }}</span></div>
    <div class="row"><span>PAJAK (10%)</span><span>Rp {{ number_format((int)$order->tax,0,',','.') }}</span></div>

    <div class="total">
      <span>TOTAL</span>
      <span>Rp {{ number_format((int)$order->total,0,',','.') }}</span>
    </div>

    <div class="line"></div>
    <div class="center muted">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
  </div>
</body>
</html>