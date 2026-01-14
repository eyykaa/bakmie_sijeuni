@extends('layouts.app')
@section('is_dashboard', true)

@section('content')
<div class="row g-5 align-items-start">

  <!-- LEFT -->
  <div class="col-lg-6">
    <h1 class="hero-title">
      Selamat Datang di
      <span class="accent">Bakmie Sijeuni</span>
    </h1>

    <p class="hero-sub">
      Silakan pesan menu favorit anda langsung dari meja dengan pengalaman digital yang nyaman dan cepat.</p>

    <div class="section-label">CARA MEMESAN</div>

    <div class="timeline">
      <div class="t-item">
        <div class="t-dot"><i class="bi bi-qr-code-scan"></i></div>
        <div class="t-content">
          <h6>Scan Kode QR</h6>
          <p>Tertera di Meja</p>
        </div>
      </div>

       <div class="t-item">
        <div class="t-dot"><i class="bi bi-table"></i></div>
        <div class="t-content">
          <h6>Pilih Nomor Meja</h6>
          <p>1-10</p>
        </div>
      </div>

      <div class="t-item">
        <div class="t-dot"><i class="bi bi-journal-text"></i></div>
        <div class="t-content">
          <h6>Pilih Menu Favorit</h6>
          <p>Menu Autentik Jepang</p>
        </div>
      </div>

      <div class="t-item">
        <div class="t-dot"><i class="bi bi-cash-stack"></i></div>
        <div class="t-content">
          <h6>Bayar Pesanan</h6>
          <p>Digital atau via kasir</p>
        </div>
      </div>

      <div class="t-item">
        <div class="t-dot"><i class="bi bi-hourglass-split"></i></div>
        <div class="t-content">
          <h6>Tunggu Masakan</h6>
          <p>Chef sedang menyiapkan hidangan</p>
        </div>
      </div>

      <div class="t-item">
        <div class="t-dot"><i class="bi bi-emoji-smile"></i></div>
        <div class="t-content">
          <h6>Nikmati Hidangan</h6>
          <p><em>Itadakimasu!</em></p>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="col-lg-6">
    <div class="panel">

      <div class="food-card mb-3">
        <!-- Kamu boleh ganti gambar ini -->
        <img src="{{ asset('images/tampilan1.jpg') }}" alt="Ramen">
        <div class="food-overlay">
          <small>Menu Rekomendasi</small>
          <div class="food-name">Special Bakmie Cinta</div>
        </div>
      </div>

      <div class="d-grid gap-3">
        <a href="{{ url('/pilih-meja') }}" class="btn btn-brand">
          <i class="bi bi-bag-check me-2"></i> Pesan Menu Sekarang
        </a>
        

        <a href="{{ url('/status-pesanan') }}" class="btn btn-outline-brand">
          <i class="bi bi-receipt me-2"></i> Lihat Status Pesanan
        </a>
      </div>

      <div class="row g-3 mt-3">
        <div class="col-6">
          <div class="stat-box">
            <div class="num">10+</div>
            <div class="label">Pilihan Meja</div>
          </div>
        </div>
        <div class="col-6">
          <div class="stat-box">
            <div class="num">15 Menit</div>
            <div class="label">Rata-rata Sajian</div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection