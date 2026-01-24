<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan {{ $start }} - {{ $end }}</title>
  <style>
    body{font-family:Arial, sans-serif; padding:24px;}
    .wrap{max-width:420px; margin:0 auto;}
    h2{margin:0 0 6px; font-size:18px;}
    .muted{opacity:.7; font-size:12px; margin-bottom:16px;}
    .row{display:flex; justify-content:space-between; margin:10px 0;}
    .label{font-weight:700;}
    .value{font-weight:700;}
    hr{border:0; border-top:1px dashed #aaa; margin:14px 0;}
    @media print { .no-print{display:none;} body{padding:0;} }
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Laporan Transaksi</h2>
    <div class="muted">Periode: {{ $start }} s/d {{ $end }}</div>

    <hr>

    <div class="row">
      <div class="label">Total Penjualan</div>
      <div class="value">Rp {{ number_format((float)($totalPenjualan ?? 0), 0, ',', '.') }}</div>
    </div>

    <div class="row">
      <div class="label">Jumlah Transaksi</div>
      <div class="value">{{ (int)($jumlahTransaksi ?? 0) }}</div>
    </div>

    <hr>

    <button class="no-print" onclick="window.print()">Print</button>
  </div>

  <script>
    window.onload = function () { window.print(); }
  </script>
</body>
</html>