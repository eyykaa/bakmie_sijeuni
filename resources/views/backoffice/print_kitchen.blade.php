<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Kitchen #{{ $order->order_code }}</title>

  <style>
    body{font-family:Arial, sans-serif; padding:24px;}
    .wrap{max-width:420px; margin:0 auto;}
    .top{display:flex; justify-content:space-between; align-items:flex-start;}
    .bold{font-weight:900;}
    .line{border-top:2px solid #000; margin:10px 0;}
    .it{display:flex; justify-content:space-between; margin:8px 0; font-size:14px;}
    .muted{opacity:.75; font-size:12px;}
  </style>

</head>
<body onload="window.print()">
  <div class="wrap">
    <div class="top">
      <div>
        <div class="bold">KITCHEN TICKET</div>
        <div class="muted">Order #{{ $order->order_code }}</div>
        <div class="muted">Meja: {{ $order->table_no }}</div>
      </div>
      <div class="muted">{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
    </div>

    <div class="line"></div>

    @foreach($order->items as $it)
      <div class="it">
        <div class="bold">{{ (int)$it->qty }}x</div>
        <div style="flex:1; padding-left:10px;">{{ $it->name }}</div>
      </div>
    @endforeach

    <div class="line"></div>
    <div class="muted">Cetak Kitchen - Sijeuni</div>
  </div>
</body>
</html>