<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Bakmie Sijeuni' }}</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons (Bootstrap Icons) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- CSS Project -->
  <link href="{{ asset('css/sijeuni.css') }}" rel="stylesheet">

</head>

<body>

  <!-- Navbar -->
<nav class="navbar navbar-sijeuni py-2">
  <div class="container d-flex align-items-center justify-content-between">

    {{-- KIRI: kalau ada back_url -> tampilkan tombol back + judul halaman --}}
    @hasSection('back_url')
      <div class="d-flex align-items-center gap-2">
        <a href="@yield('back_url')" class="back-btn" aria-label="Kembali">
          <i class="bi bi-arrow-left"></i>
        </a>
        <div class="page-title">@yield('page_title')</div>
      </div>
    @else
      {{-- DEFAULT: brand hanya untuk dashboard/halaman tanpa back --}}
      <div class="d-flex align-items-center gap-3">
        <div class="brand-badge">
          <i class="bi bi-egg-fried"></i>
        </div>
        <div class="brand-name">Bakmie Sijeuni</div>
      </div>
    @endif

    {{-- KANAN: kosong / bisa isi icon nanti --}}
    <div></div>
  </div>
</nav>

  <main class="container py-5">
    @yield('content')
  </main>

  <div class="footer-note">
    © 2026 Bakmie Sijeuni. Pesan Kuy.
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>