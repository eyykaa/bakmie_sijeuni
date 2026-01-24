@extends('layouts.backoffice')

@section('content')
<div class="bo-page">
  <div class="bo-head">
    <div>
      <h1 class="bo-title">Laporan Transaksi</h1>
      <div class="bo-subtitle">Ringkasan laporan.</div>
    </div>
  </div>

  <div class="bo-report-cards">
    <div class="bo-report-card">
      <div class="bo-report-label">Total Penjualan</div>
      <div class="bo-report-value">
        Rp {{ number_format((float)($totalPenjualan ?? 0), 0, ',', '.') }}
      </div>
    </div>

    <div class="bo-report-card">
      <div class="bo-report-label">Jumlah Transaksi</div>
      <div class="bo-report-value">
        {{ (int)($jumlahTransaksi ?? 0) }}
      </div>
    </div>
  </div>

  <div class="bo-report-actions">
    <a class="bo-btn bo-btn-outline"
       href="{{ route('backoffice.reports.print', ['start'=>$start,'end'=>$end]) }}"
       target="_blank">
      PDF
    </a>

    <a class="bo-btn bo-btn-outline"
       href="{{ route('backoffice.reports.excel', ['start'=>$start,'end'=>$end]) }}">
      Excel
    </a>
  </div>
</div>
@endsection